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
        Schema::table('penitips', function (Blueprint $table) {
            $table->string('no_ktp')->unique()->after('no_telp');
            $table->string('foto_ktp')->nullable()->after('no_ktp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penitips', function (Blueprint $table) {
            $table->dropColumn(['no_ktp', 'foto_ktp']);
        });
    }
};
