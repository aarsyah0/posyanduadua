<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imunisasi extends Model
{
    use HasFactory;

    protected $table = 'imunisasi';
    protected $primaryKey = 'id';
    
    // Constants for status enum values
    const STATUS_BELUM = 'Belum';
    const STATUS_SELESAI_SESUAI = 'Selesai Sesuai';
    const STATUS_SELESAI_TIDAK_SESUAI = 'Selesai Tidak Sesuai';
    
    // List of all valid status values
    public static $statusList = [
        self::STATUS_BELUM,
        self::STATUS_SELESAI_SESUAI,
        self::STATUS_SELESAI_TIDAK_SESUAI,
    ];
    
    protected $fillable = [
        'anak_id',
        'jenis_id',
        'tanggal',
        'status',
        'jadwal_imunisasi_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id');
    }
    
    public function jenisImunisasi()
    {
        return $this->belongsTo(JenisImunisasi::class, 'jenis_id');
    }
}
