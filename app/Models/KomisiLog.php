<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomisiLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaksi_id', 'barang_id', 'penitip_id',
        'total_harga', 'komisi_owner', 'komisi_penitip', 'bonus_penitip'
    ];

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class);
    }

    public function penitip()
    {
        return $this->belongsTo(\App\Models\Penitip::class);
    }

}
