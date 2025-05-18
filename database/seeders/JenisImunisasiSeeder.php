<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisImunisasi;
use Illuminate\Support\Carbon;

class JenisImunisasiSeeder extends Seeder
{
    public function run()
    {
        $jenisData = [
            [
                'nama' => 'HB-0',
                'min_umur_hari' => 0,
                'max_umur_hari' => 7,
                'keterangan' => 'Imunisasi hepatitis B pertama',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'BCG & Polio 1',
                'min_umur_hari' => 8,
                'max_umur_hari' => 30,
                'keterangan' => 'BCG untuk mencegah TBC dan Polio 1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'DPT-HB-HIP 1 & Polio 2',
                'min_umur_hari' => 31,
                'max_umur_hari' => 60,
                'keterangan' => 'Imunisasi DPT-HB-HIP pertama dan Polio 2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'DPT-HB-HIP 2 & Polio 3',
                'min_umur_hari' => 61,
                'max_umur_hari' => 90,
                'keterangan' => 'Imunisasi DPT-HB-HIP kedua dan Polio 3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'DPT-HB-HIP 3 & Polio 4',
                'min_umur_hari' => 91,
                'max_umur_hari' => 120,
                'keterangan' => 'Imunisasi DPT-HB-HIP ketiga dan Polio 4',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nama' => 'Campak',
                'min_umur_hari' => 270,
                'max_umur_hari' => 360,
                'keterangan' => 'Imunisasi campak',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($jenisData as $data) {
            JenisImunisasi::updateOrCreate(
                ['nama' => $data['nama']],
                $data
            );
        }
    }
} 