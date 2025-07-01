<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembeli_id',
        'penitip_id',
        'barang_id',
        'transaksi_id',
        'rating',
    ];

    public function pembeli()
    {
        return $this->belongsTo(\App\Models\Pembeli::class);
    }

    public function penitip()
    {
        return $this->belongsTo(\App\Models\Penitip::class);
    }

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(\App\Models\Transaksi::class);
    }
}
