<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PerkembanganAnakSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('perkembangan_anak')->insert([
            [
                'nama_anak' => 'Adinda Widya Putri',
                'tanggal' => '2025-03-08',
                'berat_badan' => 7.50,
                'ket_berat_badan' => 'balance',
                'tinggi_badan' => 165.00,
                "ket_tinggi_badan" => 'balance',
                'created_at' => Carbon::parse('2025-03-07 12:29:46'),
                'updated_at' => Carbon::parse('2025-03-07 12:29:46'),
            ],
            [
                'nama_anak' => 'diana ratnasari',
                'tanggal' => '2025-03-09',
                'berat_badan' => 9.00,
                'ket_berat_badan' => 'balance',
                'tinggi_badan' => 80.00,
                'ket_tinggi_badan' => 'balance',
                'created_at' => Carbon::parse('2025-03-07 22:56:52'),
                'updated_at' => Carbon::parse('2025-03-07 22:56:52'),
            ],
        ]);
    }
}
