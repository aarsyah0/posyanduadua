<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PerkembanganAnak extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perkembangan_anak';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'anak_id',
        'tanggal',
        'berat_badan',
        'tinggi_badan',
        'updated_from_id',
        'is_updated',
        'updated_by_id'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
        'is_updated' => 'boolean'
    ];

    // Validasi saat menyimpan
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Validasi berat badan
            if ($model->berat_badan <= 0) {
                throw new \Exception('Berat badan harus lebih dari 0');
            }
            
            // Validasi tinggi badan
            if ($model->tinggi_badan <= 0) {
                throw new \Exception('Tinggi badan harus lebih dari 0');
            }
        });
    }

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id');
    }
    
    public function stunting()
    {
        return $this->hasMany(Stunting::class, 'perkembangan_id');
    }

    // Helper method untuk mendapatkan status pertumbuhan
    public function getStatusPertumbuhan()
    {
        return [
            'status_bb' => $this->hitungStatusBB(),
            'status_tb' => $this->hitungStatusTB(),
        ];
    }

    private function hitungStatusBB()
    {
        // TODO: Implementasi perhitungan status berat badan
        // Sesuaikan dengan standar WHO atau standar lokal
        return 'Normal'; // Placeholder
    }

    private function hitungStatusTB()
    {
        // TODO: Implementasi perhitungan status tinggi badan
        // Sesuaikan dengan standar WHO atau standar lokal
        return 'Normal'; // Placeholder
    }

    // Relationships
    public function oldRecord()
    {
        return $this->belongsTo(PerkembanganAnak::class, 'updated_from_id');
    }

    public function newRecord()
    {
        return $this->hasOne(PerkembanganAnak::class, 'updated_from_id');
    }

    // Validation rules
    public static function rules()
    {
        return [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'berat_badan' => 'required|numeric|min:0',
            'tinggi_badan' => 'required|numeric|min:0',
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_updated', false);
    }

    public function scopeUpdated($query)
    {
        return $query->where('is_updated', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal', 'desc');
    }

    public function scopeOldest($query)
    {
        return $query->orderBy('tanggal', 'asc');
    }
}
