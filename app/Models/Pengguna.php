<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'nik';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'nik',
        'nama',
        'email',
        'password',
        'role',
        'no_telp',
        'alamat'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function anak()
    {
        return $this->hasMany(Anak::class, 'pengguna_id', 'id');
    }
} 