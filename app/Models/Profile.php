<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'pengguna';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nik',
        'nama',
        'email',
        'no_telp',
        'alamat',
        'role'
    ];

    // In this system, the 'pengguna' table is used for all user types
    // We use the 'role' column to determine if a user is admin or parent
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
