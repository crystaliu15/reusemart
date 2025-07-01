<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'jumlah',
        'subtotal',
    ];

    public function barang()
    {
        return $this->belongsTo(\App\Models\Barang::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(\App\Models\Transaksi::class, 'id_transaksi');
    }
}
