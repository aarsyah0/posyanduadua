<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalImunisasi extends Model
{
    use HasFactory;

    protected $table = 'jadwal_imunisasi';
    protected $fillable = [
        'jenis_imunisasi_id',
        'tanggal',
        'waktu',
        'is_implemented'
    ];

    protected $appends = ['status'];
    
    public function jenisImunisasi()
    {
        return $this->belongsTo(JenisImunisasi::class, 'jenis_imunisasi_id');
    }
    
    public function imunisasi()
    {
        return $this->hasMany(Imunisasi::class, 'jadwal_imunisasi_id');
    }

    public function getStatusAttribute()
    {
        return $this->is_implemented ? 'Selesai' : 'Belum';
    }
} 