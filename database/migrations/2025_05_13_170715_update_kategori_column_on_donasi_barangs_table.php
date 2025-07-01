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
        Schema::table('donasi_barangs', function (Blueprint $table) {
        $table->dropColumn('kategori'); // hapus kolom lama
        $table->unsignedBigInteger('kategori_id')->after('organisasi_id')->nullable();

        // Jika relasi kategori sudah ada, bisa tambahkan foreign key:
        $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donasi_barangs', function (Blueprint $table) {
        $table->dropForeign(['kategori_id']);
        $table->dropColumn('kategori_id');
        $table->string('kategori')->nullable(); // kembalikan string jika rollback
    });
    }
};
