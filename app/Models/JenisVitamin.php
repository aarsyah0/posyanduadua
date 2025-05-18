<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisVitamin extends Model
{
    use HasFactory;

    protected $table = 'jenis_vitamin';
    protected $primaryKey = 'id';
    
    // Konstanta untuk nilai enum
    const JENIS_A_BIRU = 'A Biru';
    const JENIS_A_MERAH = 'A Merah';
    
    // Daftar semua jenis vitamin yang valid
    public static $jenisVitamin = [
        self::JENIS_A_BIRU,
        self::JENIS_A_MERAH,
    ];
    
    protected $fillable = [
        'nama',
        'min_umur_bulan',
        'max_umur_bulan',
        'keterangan'
    ];
    
    public function vitamin()
    {
        return $this->hasMany(Vitamin::class, 'jenis_id');
    }
} 