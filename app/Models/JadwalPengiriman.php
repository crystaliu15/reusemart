<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPengiriman extends Model
{
    protected $fillable = ['barang_id', 'pegawai_id', 'jadwal_kirim'];

    public function barangs()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}


