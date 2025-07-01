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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->enum('status', ['menunggu pembayaran', 'menunggu konfirmasi', 'diproses', 'selesai'])->default('menunggu pembayaran');
            $table->unsignedBigInteger('alamat_pengiriman_id')->nullable()->after('pembeli_id');
            $table->enum('tipe_pengiriman', ['ambil', 'kirim'])->default('ambil');
            $table->integer('poin_ditukar')->default(0);
            $table->integer('potongan')->default(0);
            $table->string('bukti_transfer')->nullable();
            $table->timestamp('deadline_pembayaran')->nullable();

            $table->foreign('alamat_pengiriman_id')->references('id')->on('alamat_pembelis')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['alamat_pengiriman_id']);
            $table->dropColumn([
                'status',
                'alamat_pengiriman_id',
                'tipe_pengiriman',
                'poin_ditukar',
                'potongan',
                'bukti_transfer',
                'deadline_pembayaran',
            ]);
        });
    }
};
