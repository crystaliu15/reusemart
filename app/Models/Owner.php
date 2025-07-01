<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Owner extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $guard = 'owner';

    protected $table = 'owners';

    protected $fillable = [
        'username', 'email', 'nama_lengkap', 'no_telp', 'tanggal_lahir', 'alamat_rumah', 'password',
    ];

    protected $hidden = [
        'password',
    ];
}
