<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPengambilan extends Model
{
    use HasFactory;
    
    protected $fillable = ['pembeli_id', 'barang_id', 'jadwal_pengambilan','diambil_pada'];

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }

}


