<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel'; // Menentukan nama tabel
    protected $fillable = ['judul', 'gambar_artikel', 'isi_artikel', 'tanggal']; // Kolom yang dapat diisi
}
