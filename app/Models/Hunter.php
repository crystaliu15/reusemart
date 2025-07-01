<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hunter extends Authenticatable
{
    use HasFactory;
    protected $guard = 'hunter';

    protected $fillable = [
        'username',
        'email',
        'no_telp',
        'password',
        'nama_lengkap',
        'profile_picture',
    ];

    protected $hidden = ['password'];
}
