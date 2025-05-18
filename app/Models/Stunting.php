<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stunting extends Model
{
    use HasFactory;

    protected $table = 'stunting';
    protected $primaryKey = 'id';
    
    // Constants for status enum values
    const STATUS_STUNTING = 'Stunting';
    const STATUS_TIDAK_STUNTING = 'Tidak Stunting';
    
    // List of all valid status values
    public static $statusList = [
        self::STATUS_STUNTING,
        self::STATUS_TIDAK_STUNTING,
    ];
    
    protected $fillable = [
        'anak_id',
        'tanggal',
        'usia',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'catatan',
        'status',
        'perkembangan_id'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
        'lingkar_kepala' => 'decimal:2'
    ];
    
    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id');
    }
    
    public function perkembangan()
    {
        return $this->belongsTo(PerkembanganAnak::class, 'perkembangan_id');
    }
}
