<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisVitamin;

class JenisVitaminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisVitamins = [
            [
                'nama' => 'A Biru',
                'min_umur_bulan' => 6,
                'max_umur_bulan' => 11,
                'keterangan' => 'Vitamin A dosis biru untuk bayi usia 6-11 bulan'
            ],
            [
                'nama' => 'A Merah',
                'min_umur_bulan' => 12,
                'max_umur_bulan' => 59,
                'keterangan' => 'Vitamin A dosis merah untuk anak usia 12-59 bulan'
            ],
        ];

        foreach ($jenisVitamins as $vitamin) {
            JenisVitamin::create($vitamin);
        }
    }
} 