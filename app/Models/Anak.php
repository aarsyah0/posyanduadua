<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    use HasFactory;

    protected $table = 'anak';
    protected $primaryKey = 'id';
    public $incrementing = true;
    
    protected $fillable = [
        'pengguna_id',
        'nama_anak',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'usia',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Get the pengguna that owns the anak.
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id', 'id');
    }
    
    public function imunisasi()
    {
        return $this->hasMany(Imunisasi::class, 'anak_id');
    }

    /**
     * Get the perkembangan records for the anak.
     */
    public function perkembanganAnak()
    {
        return $this->hasMany(PerkembanganAnak::class, 'anak_id');
    }
} 