<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonasiBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'organisasi_id', 'nama_penerima', 'kategori_id','nama_barang', 'deskripsi', 'tanggal_donasi'
    ];

    public function organisasi()
    {
        return $this->belongsTo(\App\Models\Organisasi::class, 'organisasi_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function penitip()
{
    return $this->belongsTo(Penitip::class, 'id');
}

    }
