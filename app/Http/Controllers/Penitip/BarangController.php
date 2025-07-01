<?php

namespace App\Http\Controllers\Penitip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barang;
use App\Models\Pembeli;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Carbon\Carbon;

class BarangController extends Controller
{
        public function index(Request $request)
    {
        $penitip = Auth::guard('penitip')->user();
        $keyword = $request->input('cari'); // Ambil keyword dari input cari

        // Barang yang belum terjual (aktif)
        $barangAktifQuery = \App\Models\Barang::where('penitip_id', $penitip->id)
                            ->where('terjual', false);

        // Jika ada keyword pencarian
        if ($keyword) {
            $barangAktifQuery->where('nama', 'like', '%' . $keyword . '%');
        }

        $barangAktif = $barangAktifQuery->paginate(6)->withQueryString();

        // Barang yang sudah terjual
        $barangTerjual = \App\Models\Barang::where('penitip_id', $penitip->id)
                            ->where('terjual', true)
                            ->paginate(6);

        return view('penitip.dashboard', compact('penitip', 'barangAktif', 'barangTerjual'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'thumbnail' => 'required|image',
        ]);

        $barang = new \App\Models\Barang();
        $barang->nama = $request->nama;
        $barang->harga = $request->harga;
        $barang->deskripsi = $request->deskripsi;
        $barang->kategori_id = $request->kategori_id;
        $barang->penitip_id = Auth::guard('penitip')->id(); // ⬅️ baris penting

        // Simpan thumbnail ke storage/public/images/barang/{id}
        $barang->save(); // simpan dulu untuk mendapatkan ID

        $thumbnailPath = $request->file('thumbnail')->storeAs(
            'public/images/barang/' . $barang->id,
            'thumb.jpg'
        );
        $barang->thumbnail = 'thumb.jpg';
        $barang->save();

        return redirect()->route('penitip.dashboard')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show($id)
    {
        $barang = \App\Models\Barang::with('kategori')->findOrFail($id);

        if ($barang->penitip_id !== Auth::guard('penitip')->id()) {
            abort(403, 'Barang ini bukan milik Anda.');
        }

        return view('penitip.barang-show', compact('barang'));
    }

    public function riwayat($id)
    {
        $barang = Barang::findOrFail($id);

        // Ambil transaksi terakhir berdasarkan relasi yang benar
        $detail = $barang->detailTransaksis()->latest()->first();

        $pembeli = null;
        $poinPembeli = 0;

        if ($detail && $detail->transaksi && $detail->transaksi->pembeli) {
            $pembeli = $detail->transaksi->pembeli;
            $poinPembeli = $pembeli->poin;
        }

        $komisi = $barang->harga * 0.1;

        return view('penitip.penitip_riwayat', compact('barang', 'pembeli', 'poinPembeli', 'komisi'));
    }

    public function perpanjang($id)
    {
        $barang = Barang::findOrFail($id);

        if (!$barang->status_perpanjangan) {
            $barang->batas_waktu_titip = Carbon::parse($barang->batas_waktu_titip)->addDays(30);
            $barang->status_perpanjangan = true;
            $barang->save();

            return redirect()->back()->with('success', 'Masa penitipan berhasil diperpanjang 30 hari.');
        }

        return redirect()->back()->with('info', 'Barang ini sudah diperpanjang sebelumnya.');
    }

    public function konfirmasiPengambilan($id)
    {
        $barang = Barang::findOrFail($id);

        // Pastikan hanya pemilik yang bisa konfirmasi
        if ($barang->penitip_id !== Auth::guard('penitip')->id()) {
            abort(403, 'Anda tidak bisa mengambil barang ini.');
        }

        // Set status pengambilan menjadi true
        $barang->status_pengambilan = true;
        $barang->save();

        return redirect()->back()->with('success', 'Konfirmasi pengambilan barang berhasil dikirim.');
    }

}
