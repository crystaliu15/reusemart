<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use Carbon\Carbon;

class CekTransaksiHangus extends Command
{
    protected $signature = 'transaksi:cek-hangus';
    protected $description = 'Ubah status transaksi menjadi hangus jika lebih dari 2 hari dari jadwal_pengambilan dan belum diambil';

    public function handle()
    {
        $now = Carbon::now();

        // Ambil transaksi yang selesai tapi belum diambil (ambil sendiri)
        $transaksis = Transaksi::with(['jadwalPengambilan', 'detail.barang'])
            ->where('tipe_pengiriman', 'ambil')
            ->where('status', 'selesai')
            ->get();

        foreach ($transaksis as $transaksi) {
            $jadwal = $transaksi->jadwalPengambilan;

            // Lewati jika tidak ada jadwal atau sudah diambil
            if (!$jadwal || $jadwal->diambil_pada !== null) {
                continue;
            }

            $jadwalTanggal = Carbon::parse($jadwal->jadwal_pengambilan);

            // Cek apakah sudah lewat lebih dari 2 hari dari jadwal_pengambilan
            if ($jadwalTanggal->addDays(2)->lt($now)) {
                // Update status transaksi menjadi hangus
                $transaksi->update(['status' => 'hangus']);

                // Update semua barang pada transaksi menjadi "barang untuk donasi"
                foreach ($transaksi->detail as $detail) {
                    if ($detail->barang) {
                        $detail->barang->update(['status' => 'barang untuk donasi']);
                    }
                }

                $this->info("Transaksi ID {$transaksi->id} dihanguskan dan barang dialihkan untuk donasi.");
            }
        }

        $this->info('Selesai memproses transaksi yang hangus.');
    }
}
