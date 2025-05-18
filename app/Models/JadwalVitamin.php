<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalVitamin extends Model
{
    use HasFactory;

    protected $table = 'jadwal_vitamin';
    protected $fillable = [
        'jenis_vitamin_id',
        'tanggal',
        'waktu',
        'is_implemented'
    ];

    protected $appends = ['status'];
    
    public function jenisVitamin()
    {
        return $this->belongsTo(JenisVitamin::class, 'jenis_vitamin_id');
    }
    
    public function vitamin()
    {
        return $this->hasMany(Vitamin::class, 'jadwal_vitamin_id');
    }

    public function getStatusAttribute()
    {
        return $this->is_implemented ? 'Selesai' : 'Belum';
    }
} 