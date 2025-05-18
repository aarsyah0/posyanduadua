<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class VitaminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vitamin')->insert([
            'nama_anak' => 'Adinda Widya Putri',
            'tanggal_vitamin' => '2025-03-08',
            'vitamin' => 'Kapsul Merah',
            'status' => 'Tidak Selesai',
            'created_at' => Carbon::parse('2025-03-07 15:57:45'),
            'updated_at' => Carbon::parse('2025-03-11 20:45:38'),
        ]);
    }
}
