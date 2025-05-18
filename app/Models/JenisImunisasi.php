<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisImunisasi extends Model
{
    use HasFactory;

    protected $table = 'jenis_imunisasi';
    protected $primaryKey = 'id';
    
    // Konstanta untuk nilai enum
    const JENIS_HB0 = 'HB-0';
    const JENIS_BCG_POLIO1 = 'BCG & Polio 1';
    const JENIS_DPT_HB_HIP1_POLIO2 = 'DPT-HB-HIP 1 & Polio 2';
    const JENIS_DPT_HB_HIP2_POLIO3 = 'DPT-HB-HIP 2 & Polio 3';
    const JENIS_DPT_HB_HIP3_POLIO4 = 'DPT-HB-HIP 3 & Polio 4';
    const JENIS_CAMPAK = 'Campak';
    
    // Daftar semua jenis imunisasi yang valid
    public static $jenisImunisasi = [
        self::JENIS_HB0,
        self::JENIS_BCG_POLIO1,
        self::JENIS_DPT_HB_HIP1_POLIO2,
        self::JENIS_DPT_HB_HIP2_POLIO3,
        self::JENIS_DPT_HB_HIP3_POLIO4,
        self::JENIS_CAMPAK,
    ];
    
    protected $fillable = [
        'nama',
        'min_umur_hari',
        'max_umur_hari',
        'keterangan'
    ];
    
    public function imunisasi()
    {
        return $this->hasMany(Imunisasi::class, 'jenis_id');
    }
} 