<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class Petugas extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'petugas';
    protected $fillable = [
        'nik',
        'nama',
        'email',
        'password',
        'no_telp',
        'alamat'
    ];
    public $timestamps = false; // Nonaktifkan timestamps
}
