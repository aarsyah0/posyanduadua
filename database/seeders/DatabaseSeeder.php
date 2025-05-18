<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\DataAnak;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PenggunaSeeder::class,
            JenisImunisasiSeeder::class,
            JenisVitaminSeeder::class,
            // ArtikelSeeder::class,
            // DataAnakSeeder::class,
            // DataOrangtuaSeeder::class,
            // ImunisasiSeeder::class,
            // JadwalSeeder::class,
            // PerkembanganAnakSeeder::class,
            // PetugasSeeder::class,
            // StuntingSeeder::class,
            // VitaminSeeder::class,
        ]);

        // Tambahkan kode untuk membuat admin default
        Pengguna::create([
            'nama' => 'Admin Default',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nik' => '1234567890123456',
        ]);
    }
}
