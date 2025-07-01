<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskusi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barang_id',
        'isi',
    ];

    public function user()
    {
        return $this->belongsTo(Pembeli::class, 'user_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
