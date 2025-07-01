<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Owner', 'Admin', 'CS', 'Pegawai Gudang', 'Kurir'];

        foreach ($roles as $jabatan) {
            DB::table('jabatans')->updateOrInsert(
                ['nama_jabatan' => $jabatan],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
