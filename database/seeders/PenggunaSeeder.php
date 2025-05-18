<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        // Generate NIK unik (16 digit)
        $nik = date('ymd') . str_pad(mt_rand(1, 999999), 10, '0', STR_PAD_LEFT);
        
        // Cek apakah admin sudah ada
        if (!Pengguna::where('email', 'admin@posyandu.com')->exists()) {
            // Data admin
            Pengguna::create([
                'nik' => $nik,
                'nama' => 'Admin Posyandu',
                'email' => 'admin@posyandu.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Posyandu No. 1'
            ]);
        }
    }
}
