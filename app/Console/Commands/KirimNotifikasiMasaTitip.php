<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Models\Penitip;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class KirimNotifikasiTitipCommand extends Command
{
    protected $signature = 'notifikasi:kirim-titip';
    protected $description = 'Mengirim notifikasi ke penitip jika barang mendekati akhir masa titip (H-3 dan H)';

    public function handle()
    {
        $this->info("🔁 Command notifikasi:kirim-titip dijalankan!");
        $today = Carbon::today();
        $h3 = $today->copy()->addDays(3);

        $barangList = Barang::whereDate('batas_waktu_titip', $today)
            ->orWhereDate('batas_waktu_titip', $h3)
            ->get();

        foreach ($barangList as $barang) {
            $penitip = Penitip::find($barang->penitip_id);
            if (!$penitip || !$penitip->fcm_token) continue;

            $tanggalTitip = Carbon::parse($barang->batas_waktu_titip);
            $selisih = $tanggalTitip->diffInDays($today, false);

            $judul = "⏳ Pengingat Titipan";
            $pesan = match ($selisih) {
                0 => "Hari ini masa titip barang '{$barang->nama}' berakhir. Segera ambil atau perpanjang!",
                -3 => "3 hari lagi masa titip barang '{$barang->nama}' akan habis. Lakukan perpanjangan jika barang masih ingin dititipkan!",
                default => null,
            };

            if (!$pesan) continue;

            try {
                Firebase::messaging()->send(
                    CloudMessage::withTarget('token', $penitip->fcm_token)
                        ->withNotification(Notification::create($judul, $pesan))
                );

                $this->info("✅ Notif dikirim ke {$penitip->username} (barang ID {$barang->id})");
            } catch (\Throwable $e) {
                $this->error("❌ Gagal kirim ke {$penitip->username}: " . $e->getMessage());
            }
        }

        return Command::SUCCESS;
    }
}
