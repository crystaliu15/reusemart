<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Owner::create([
            'username' => 'Abhinaya',
            'email' => 'abhinprasada@gmail.com',
            'nama_lengkap' => 'Owner Abhinaya',
            'no_telp' => '082261558052',
            'tanggal_lahir' => '2004-08-28',
            'alamat_rumah' => 'Jl. ReuseMart No.1',
            'password' => Hash::make('123456'),
        ]);
    }
}
