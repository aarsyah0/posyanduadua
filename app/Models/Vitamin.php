<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vitamin extends Model
{
    use HasFactory;

    protected $table = 'vitamin';
    protected $primaryKey = 'id';
    
    // Constants for status enum values
    const STATUS_BELUM = 'Belum';
    const STATUS_SELESAI = 'Selesai';
    
    // List of all valid status values
    public static $statusList = [
        self::STATUS_BELUM,
        self::STATUS_SELESAI,
    ];
    
    protected $fillable = [
        'anak_id',
        'jenis_id',
        'tanggal',
        'status',
        'jadwal_vitamin_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
    
    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id');
    }
    
    public function jenisVitamin()
    {
        return $this->belongsTo(JenisVitamin::class, 'jenis_id');
    }
}
