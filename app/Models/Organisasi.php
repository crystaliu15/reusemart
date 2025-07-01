<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Organisasi extends Authenticatable
{
    use Notifiable;
    
    protected $fillable = [
        'username',
        'email',
        'no_telp',
        'password',
        'alamat',
        'plaintext_password',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function requestDonasis()
    {
        return $this->hasMany(RequestDonasi::class);
    }

    public function donasiBarangs()
    {
        return $this->hasMany(DonasiBarang::class);
    }
}
