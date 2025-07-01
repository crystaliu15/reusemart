<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDonasi extends Model
{
    protected $fillable = ['organisasi_id','jenis_barang', 'alasan'];
    use HasFactory;

    public function organisasi()
    {
        return $this->belongsTo(\App\Models\Organisasi::class);
    }
}
