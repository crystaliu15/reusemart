<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY status ENUM(
            'menunggu pembayaran',
            'menunggu konfirmasi',
            'diproses',
            'selesai',
            'pembayaran gagal',
            'pembayaran ditolak'
        ) NOT NULL DEFAULT 'menunggu pembayaran'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY status ENUM(
            'menunggu pembayaran',
            'menunggu konfirmasi',
            'diproses',
            'selesai'
        ) NOT NULL DEFAULT 'menunggu pembayaran'");
    }
};
