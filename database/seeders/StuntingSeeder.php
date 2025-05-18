<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class StuntingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stunting')->insert([
            'nama_anak' => 'diana ratnasari',
            'tanggal' => '2025-03-08',
            'usia' => '3 Tahun',
            'berat_badan' => 18.00,
            'tinggi_badan' => 80.00,
            'lingkar_kepala' => 15.00,
            'catatan' => 'normal',
            'status' => 'Normal',
            'created_at' => Carbon::parse('2025-03-07 23:35:28'),
            'updated_at' => Carbon::parse('2025-03-07 23:35:28'),
        ]);
    }
}
