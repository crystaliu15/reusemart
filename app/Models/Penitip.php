<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Penitip extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'email',
        'no_telp',
        'alamat',
        'no_ktp',
        'foto_ktp',
        'password',
        'profile_picture',
        'plaintext_password',
        'poin',
        'saldo',
        'fcm_token',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}
