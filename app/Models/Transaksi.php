<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembeli_id',
        'tanggal',
        'total',
        'status',
        'alamat_pengiriman_id',
        'tipe_pengiriman',
        'poin_ditukar',
        'potongan',
        'bukti_transfer',
        'deadline_pembayaran',
        'jadwal_pengambilan_id',
        'no_nota',
        'jumlah_barang',
    ];

    public function pembeli()
    {
        return $this->belongsTo(\App\Models\Pembeli::class);
    }

    public function detail()
    {
        return $this->hasMany(\App\Models\DetailTransaksi::class);
    }

    public function alamat()
    {
        return $this->belongsTo(AlamatPembeli::class, 'alamat_pengiriman_id');
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class);
    }

    public function barangs()
    {
        return $this->hasMany(\App\Model\Barang::class);
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function jadwalPengambilan()
    {
        return $this->belongsTo(JadwalPengambilan::class, 'jadwal_pengambilan_id');
    }

}
