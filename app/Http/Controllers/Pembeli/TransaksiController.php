<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\AlamatPembeli;
use App\Models\DetailTransaksi;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Rating;

class TransaksiController extends Controller
{
    public function listTransaksiDisiapkan()
{
    $user = Auth::guard('pembeli')->user();

    if (!$user) {
        // Jika belum login, tampilkan view dengan data kosong
        return view('pembeli.transaksi_batal', [
            'transaksis' => collect(),
            'user' => null
        ]);
    }

    $transaksis = Transaksi::with('detail.barang')
        ->where('pembeli_id', $user->id)
        ->where('status', 'disiapkan')
        ->get();

    return view('pembeli.transaksi_batal', compact('transaksis', 'user'));
}


    public function batalkanTransaksiPembeli($id)
    {
        $transaksi = Transaksi::with(['detail.barang', 'pembeli'])->findOrFail($id);

        if ($transaksi->pembeli_id !== Auth::guard('pembeli')->id()) {
            abort(403, 'Anda tidak berhak membatalkan transaksi ini.');
        }

        if ($transaksi->status !== 'disiapkan') {
            return back()->with('error', 'Transaksi sudah tidak bisa dibatalkan.');
        }

        if ($transaksi->detail->count() < 2) {
            return back()->with('error', 'Transaksi tidak valid: kurang dari 2 barang.');
        }

        $poinBaru = floor($transaksi->total / 10000);

        $transaksi->status = 'dibatalkan pembeli';
        $transaksi->save();

        $pembeli = $transaksi->pembeli;
        $pembeli->poin += $poinBaru;
        $pembeli->save();

        foreach ($transaksi->detail as $detail) {
            $barang = $detail->barang;
            if ($barang) {
                $barang->status = 'available';
                $barang->transaksi_id = null;
                $barang->terjual = 0;
                $barang->save();
            }
        }

        return redirect()->route('pembeli.transaksi.batal')->with('success', 'Transaksi berhasil dibatalkan dan poin telah ditambahkan.');
    }
    public function showDetail($transaksiId, $barangId)
    {
        $transaksi = Transaksi::with(['detail.barang', 'pembeli'])->findOrFail($transaksiId);

        // Cek apakah transaksi milik pembeli yang login
        if ($transaksi->pembeli_id !== Auth::guard('pembeli')->id()) {
            abort(403);
        }

        $detail = $transaksi->detail->firstWhere('barang_id', $barangId);

        if (!$detail) {
            abort(404, 'Barang tidak ditemukan dalam transaksi ini.');
        }

        $barang = $detail->barang;
        $jumlah = $detail->jumlah;
        $subtotal = $detail->subtotal;

        $tanggalKirim = $transaksi->tanggal;
        $tanggalSampai = \Carbon\Carbon::parse($tanggalKirim)->addDays(3);
        $alamatTujuan = $transaksi->alamat_pengiriman ?? $transaksi->pembeli->alamat_pembeli->id ?? 'Alamat tidak tersedia';

        return view('pembeli.detail_transaksi', compact(
            'barang', 'jumlah', 'subtotal', 'tanggalKirim', 'tanggalSampai', 'alamatTujuan'
        ));
    }

    public function pembayaranForm()
    {
        $pembeli = Auth::guard('pembeli')->user();
        $items = Keranjang::with('barang')->where('pembeli_id', $pembeli->id)->get();
        $alamatList = $pembeli->alamat;
        $alamatDefault = $pembeli->defaultAlamat;
        $poinPembeli = $pembeli->poin;

        return view('pembeli.pembayaran', compact('items', 'alamatList', 'alamatDefault', 'poinPembeli'));
    }

    public function prosesPembayaran(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $request->validate([
            'tipe_pengiriman' => 'required|in:ambil,kirim',
            'poin_ditukar' => 'required|integer|min:0',
            'alamat_pengiriman_id' => 'nullable|exists:alamat_pembelis,id'
        ]);

        $keranjang = Keranjang::with('barang')->where('pembeli_id', $pembeli->id)->get();

        if ($keranjang->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong.');
        }

        $totalBarang = 0;
        foreach ($keranjang as $item) {
            $totalBarang += $item->barang->harga;
        }

        $ongkir = $request->tipe_pengiriman === 'kirim' && $totalBarang < 1500000 ? 100000 : 0;

        if ($request->poin_ditukar > 0 && $request->poin_ditukar < 100) {
            return redirect()->route('cart')->with('error', 'Poin minimal yang dapat ditukar adalah 100.');
        }

        $poinDitukar = min($request->poin_ditukar, $pembeli->poin);
        $potongan = $poinDitukar * 10000;
        $totalAkhir = max(0, $totalBarang + $ongkir - $potongan);

        // Simpan transaksi awal
        $transaksi = Transaksi::create([
            'pembeli_id' => $pembeli->id,
            'tanggal' => now(),
            'alamat_pengiriman_id' => $request->tipe_pengiriman === 'kirim' ? $request->alamat_pengiriman_id : null,
            'tipe_pengiriman' => $request->tipe_pengiriman,
            'poin_ditukar' => $poinDitukar,
            'potongan' => $potongan,
            'total' => $totalAkhir,
            'status' => 'menunggu pembayaran',
            'deadline_pembayaran' => now()->addMinutes(1),
            'jadwal_pengambilan_id' => null,
        ]);

        // ✅ Tambahkan no_nota
        $tahun = now()->format('y');
        $bulan = now()->format('m');
        $transaksi->no_nota = "{$tahun}.{$bulan}.{$transaksi->id}";

        $jumlahBarang = 0;
        $poinBaru = 0;

        foreach ($keranjang as $item) {
            $barang = $item->barang;

            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id' => $barang->id,
                'jumlah' => 1,
                'subtotal' => $barang->harga,
            ]);

            $barang->update([
                'terjual' => 1,
                'transaksi_id' => $transaksi->id,
                'status' => 'Sold Out', // ✅ Barang ditandai terjual
            ]);

            $jumlahBarang += 1;

            $poin = floor($barang->harga / 10000);
            if ($barang->harga > 500000) {
                $bonus = floor($barang->harga * 0.2 / 10000);
                $poin += $bonus;
            }
            $poinBaru += $poin;
        }

        // ✅ Tambahkan jumlah_barang
        $transaksi->jumlah_barang = $jumlahBarang;
        $transaksi->save();

        $pembeli->poin = max(0, $pembeli->poin - $poinDitukar + $poinBaru);
        $pembeli->save();

        Keranjang::where('pembeli_id', $pembeli->id)->delete();

        return redirect()->route('pembeli.transaksi.uploadBuktiForm', $transaksi->id)
            ->with('success', 'Transaksi berhasil dibuat. Silakan upload bukti pembayaran.');
    }

    public function uploadBuktiForm($id)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $transaksi = Transaksi::where('id', $id)->where('pembeli_id', $pembeli->id)->firstOrFail();

        return view('pembeli.upload_bukti', compact('transaksi'));
    }

    public function submitBuktiTransfer(Request $request, $id)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $transaksi = Transaksi::where('id', $id)->where('pembeli_id', $pembeli->id)->firstOrFail();

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg|max:2048',
        ]);

        // Simpan ke storage/app/public/bukti_transfer
        $file = $request->file('bukti_transfer');
        $filename = 'bukti_' . $transaksi->id . '.jpg';

        $path = $file->storeAs('bukti_transfer', $filename, 'public'); // simpan ke storage/app/public

        // Simpan path ke database (relatif dari public/storage/)
        $transaksi->update([
            'bukti_transfer' => $path, // contoh: "bukti_transfer/bukti_123.jpg"
            'status' => 'menunggu konfirmasi',
        ]);

        return redirect('/')->with('success', 'Bukti transfer berhasil diupload. Menunggu konfirmasi.');
    }

    public function gagalBayar($id)
    {
        $transaksi = \App\Models\Transaksi::with(['pembeli', 'detail.barang'])->findOrFail($id);

        // Cegah jika status transaksi bukan "menunggu pembayaran"
        if ($transaksi->status !== 'menunggu pembayaran') {
            return redirect()->route('home');
        }

        // Pastikan relasi pembeli ada
        if (!$transaksi->pembeli) {
            return redirect()->route('home')->with('error', 'Data pembeli tidak ditemukan.');
        }

        $pembeli = $transaksi->pembeli;

        // ✅ 1. Kembalikan poin potongan (jika ada)
        $pembeli->poin += $transaksi->poin_ditukar;

        // ✅ 2. Batalkan poin reward yang didapat dari pembelian
        $poinReward = 0;

        foreach ($transaksi->detail as $detail) {
            $barang = $detail->barang;

            if ($barang) {
                // Ubah status barang jadi tersedia
                $barang->terjual = 0;
                $barang->save();

                // Hitung poin yang sempat didapatkan
                $poin = floor($barang->harga / 10000);
                if ($barang->harga > 500000) {
                    $bonus = floor($barang->harga * 0.2 / 10000);
                    $poin += $bonus;
                }
                $poinReward += $poin;
            }
        }

        // Kurangi poin reward (tidak jadi dapat)
        $pembeli->poin = max(0, $pembeli->poin - $poinReward);
        $pembeli->save();

        // ✅ 3. Update status transaksi
        $transaksi->update([
            'status' => 'pembayaran gagal'
        ]);

        // ✅ 4. Redirect ke home dengan notifikasi modal
        return redirect()->route('home')->with('gagal_pembayaran', true);
    }

    public function riwayat()
    {
        $pembeli = Auth::guard('pembeli')->user();

        $transaksis = Transaksi::with(['detail.barang'])
            ->where('pembeli_id', $pembeli->id)
            ->latest()
            ->get();

        return view('pembeli.riwayatpembelian', compact('transaksis', 'pembeli'));
    }

    public function detail($id)
    {
        $transaksi = Transaksi::with(['detail.barang', 'alamat'])->findOrFail($id);
        $pembeli = Auth::guard('pembeli')->user();

        // Ambil semua rating untuk transaksi ini oleh pembeli ini
        $ratings = \App\Models\Rating::where('pembeli_id', $pembeli->id)
            ->where('transaksi_id', $transaksi->id)
            ->get()
            ->keyBy('barang_id'); // supaya bisa diakses langsung pakai barang_id

        return view('pembeli.riwayatdetail', compact('transaksi', 'ratings'));
    }

    public function cetakNota($id)
    {
        $pembeli = Auth::guard('pembeli')->user();

        $transaksi = \App\Models\Transaksi::with(['detail.barang', 'alamat'])
            ->where('id', $id)
            ->where('pembeli_id', $pembeli->id)
            ->firstOrFail();

        $pdf = Pdf::loadView('pembeli.nota', compact('transaksi'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream("nota-transaksi-{$transaksi->id}.pdf");
    }

    public function beriRating(Request $request, $barang_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'transaksi_id' => 'required|exists:transaksis,id',
            'penitip_id' => 'required|exists:penitips,id',
        ]);

        $pembeli = Auth::guard('pembeli')->user();

        // Cek apakah sudah memberi rating
        $existing = Rating::where('pembeli_id', $pembeli->id)
            ->where('barang_id', $barang_id)
            ->where('transaksi_id', $request->transaksi_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memberi rating untuk barang ini.');
        }

        Rating::create([
            'pembeli_id' => $pembeli->id,
            'barang_id' => $barang_id,
            'transaksi_id' => $request->transaksi_id,
            'penitip_id' => $request->penitip_id,
            'rating' => $request->rating,
        ]);

        return redirect()->route('pembeli.transaksi.detail', $request->transaksi_id)
            ->with('success', 'Rating berhasil dikirim.');
    }

}