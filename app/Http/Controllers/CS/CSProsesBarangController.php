<?php

namespace App\Http\Controllers\CS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Owner;
use App\Models\KomisiLog;
use App\Models\Penitip;
use App\Models\Pembeli;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class CSProsesBarangController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::with(['pembeli', 'detail.barang'])
            ->where('status', 'diproses')
            ->get();

        return view('cs.barang-diproses', compact('transaksis'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['pembeli', 'detail.barang'])->findOrFail($id);
        return view('cs.barang-diproses-detail', compact('transaksi'));
    }

    public function selesaikan($id)
    {
        $transaksi = Transaksi::with('detail.barang.penitip', 'pembeli')->findOrFail($id);

        // Update status transaksi
        $transaksi->status = 'selesai';
        $transaksi->save();

        // Ambil owner (diasumsikan hanya ada satu, dengan id = 1)
        $owner = Owner::find(1);
        if (!$owner) {
            return back()->with('error', 'Owner tidak ditemukan.');
        }

        foreach ($transaksi->detail as $detail) {
            $barang = $detail->barang;

            if ($barang) {
                // 1. Tandai barang terjual
                $barang->terjual = 1;
                $barang->status = 'sold out';
                $barang->save();

                $penitip = $barang->penitip;
                $harga = $detail->subtotal;
                $isPerpanjangan = $barang->status_perpanjangan == 1;

                // 2. Hitung komisi owner dan penitip
                $komisiOwner = $isPerpanjangan ? 0.30 * $harga : 0.20 * $harga;
                $komisiPenitip = $harga - $komisiOwner;

                // 3. Cek apakah laku dalam <= 7 hari
                $bonus = 0;
                if ($barang->created_at->diffInDays($transaksi->created_at) <= 7) {
                    $bonus = 0.10 * $komisiOwner;
                    $komisiPenitip += $bonus;
                }

                // 4. Tambahkan saldo penitip
                $penitip->saldo += $komisiPenitip;
                $penitip->save();

                // 5. Tambahkan saldo owner
                $owner->saldo += $komisiOwner;
                $owner->save();

                // 6. Simpan log
                KomisiLog::create([
                    'transaksi_id' => $transaksi->id,
                    'barang_id' => $barang->id,
                    'penitip_id' => $penitip->id,
                    'total_harga' => $harga,
                    'komisi_owner' => $komisiOwner,
                    'komisi_penitip' => $harga - $komisiOwner,
                    'bonus_penitip' => $bonus,
                ]);
            }
        }

        // ✅ Kirim notifikasi jika tipe pengiriman adalah 'ambil'
        if (strtolower($transaksi->tipe_pengiriman) === 'ambil') {
            \Log::info("🔔 Transaksi tipe ambil, kirim notifikasi ke pembeli {$transaksi->pembeli_id}");
            $this->kirimNotifikasiPembeli(
                $transaksi->pembeli_id,
                'Pesanan Selesai',
                'Pesanan Anda telah berhasil diambil dan selesai diproses.'
            );
        }

        return redirect()->route('cs.barang.diproses')->with('success', 'Transaksi telah diselesaikan & komisi berhasil dibagikan.');
    }

    private function kirimNotifikasiPembeli($pembeliId, $judul, $pesan)
{
    $pembeli = \App\Models\Pembeli::find($pembeliId);

    if (!$pembeli || !$pembeli->fcm_token) {
        \Log::warning("❌ Pembeli tidak ditemukan atau token kosong");
        return;
    }

    try {
        $messaging = (new Factory)
            ->withServiceAccount(storage_path(env('FIREBASE_CREDENTIALS')))
            ->createMessaging();

        $message = CloudMessage::withTarget('token', $pembeli->fcm_token)
            ->withNotification(Notification::create($judul, $pesan))
            ->withData(['click_action' => 'FLUTTER_NOTIFICATION_CLICK']);

        $messaging->send($message);

        \Log::info("✅ Notifikasi FCM v1 terkirim ke pembeli ID: $pembeliId");

    } catch (\Throwable $e) {
        \Log::error("❌ Gagal kirim notifikasi FCM v1: " . $e->getMessage());
    }
}
}