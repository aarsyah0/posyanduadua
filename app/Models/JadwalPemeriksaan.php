<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pemeriksaan';
    protected $fillable = ['judul', 'tanggal', 'waktu', 'is_implemented'];
    
    protected $casts = [
        'is_implemented' => 'boolean',
    ];
} 