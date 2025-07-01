<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use App\Models\Pembeli;
use Carbon\Carbon;

class BatalkanTransaksiKadaluarsa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaksi:batalkan-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan transaksi yang melewati batas pembayaran';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transaksis = Transaksi::where('status', 'menunggu pembayaran')
            ->where('deadline_pembayaran', '<', now())
            ->get();

        foreach ($transaksis as $transaksi) {
            $pembeli = $transaksi->pembeli;

            // Kembalikan poin yang ditukar
            $pembeli->poin += $transaksi->poin_ditukar;

            // Ambil detail transaksi dan rollback barang
            $details = DetailTransaksi::where('transaksi_id', $transaksi->id)->get();
            $poinReward = 0;

            foreach ($details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->terjual = 0;
                    $barang->save();

                    // Hitung poin reward yang sempat ditambahkan
                    $poin = floor($barang->harga / 10000);
                    if ($barang->harga > 500000) {
                        $bonus = floor($barang->harga * 0.2 / 10000);
                        $poin += $bonus;
                    }
                    $poinReward += $poin;
                }
            }

            // Kurangi poin reward yang sempat ditambahkan
            $pembeli->poin = max(0, $pembeli->poin - $poinReward);
            $pembeli->save();

            // Update status transaksi
            $transaksi->status = 'dibatalkan';
            $transaksi->save();

            $this->info("Transaksi #{$transaksi->id} dibatalkan.");
        }

        return 0;
    }
}
