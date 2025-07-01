<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE transaksis MODIFY status ENUM(
            'menunggu pembayaran',
            'menunggu konfirmasi',
            'diproses',
            'selesai',
            'pembayaran gagal',
            'dibatalkan'
        ) NOT NULL DEFAULT 'menunggu pembayaran'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Kembalikan jika perlu
        DB::statement("ALTER TABLE transaksis MODIFY status ENUM(
            'menunggu pembayaran',
            'menunggu konfirmasi',
            'diproses',
            'selesai'
        ) NOT NULL DEFAULT 'menunggu pembayaran'");
    }
};
