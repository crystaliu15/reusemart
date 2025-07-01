<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatPembeli extends Model
{
    protected $table = 'alamat_pembelis';
    protected $fillable = ['pembeli_id','alamat'];
    use HasFactory;

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class);
    }
}
