<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataOrangTua extends Model
{
    use HasFactory;

    protected $table = 'data_orangtua';
    protected $primaryKey = 'nik';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'nik',
        'nama_ibu',
        'no_telp',
        'alamat',
        'nama_anak'
    ];
}
