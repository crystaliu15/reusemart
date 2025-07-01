<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Barang extends Model
{
    use HasFactory;
    protected $fillable = [
        'kategori_id',
        'nama',
        'deskripsi',
        'status',
        'harga',
        'berat',
        'thumbnail',
        'foto_lain',
        'garansi_berlaku_hingga',
        'quality_check',
        'terjual',
        'batas_waktu_titip',
        'status_perpanjangan',
        'status_pengambilan',
        'diambil_kembali',
        'tanggal_diambil_kembali',
        'transaksi_id',
        'penitip_id',
    ];

    protected $casts = [
        'garansi_berlaku_hingga' => 'date',
        'batas_waktu_titip' => 'date',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function diskusis()
    {
        return $this->hasMany(Diskusi::class);
    }

    // Opsional helper untuk cek status garansi
    public function isGaransiAktif()
    {
        return $this->garansi_berlaku_hingga && $this->garansi_berlaku_hingga->isFuture();
    }

    public function penitip()
    {
        return $this->belongsTo(\App\Models\Penitip::class);
    }

    public function detailTransaksis()
    {
        return $this->hasMany(\App\Models\DetailTransaksi::class, 'barang_id'); // BUKAN 'id_barang'
    }

    public function qualityChecker()
    {
        return $this->belongsTo(\App\Models\Pegawai::class, 'quality_check');
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(\App\Models\Transaksi::class, 'transaksi_id');
    }

    public function jadwalPengirimen()
    {
        return $this->hasOne(JadwalPengiriman::class);
    }

    public function jadwalPengambilan()
    {
        return $this->hasOne(JadwalPengambilan::class, 'barang_id');
    }

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id'); 
    }
    
}


