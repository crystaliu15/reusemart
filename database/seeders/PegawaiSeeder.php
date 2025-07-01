<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pegawais')->insert([
            [
                'username' => 'admin',
                'nama_lengkap' => 'Admin ReuseMart',
                'tanggal_lahir' => '1995-02-15',
                'password' => Hash::make('admin'),
                'jabatan_id' => 1, // Admin
                'alamat_rumah' => 'Jl. Kedua No.2, Bandung',
            ],
        ]);
    }
}
