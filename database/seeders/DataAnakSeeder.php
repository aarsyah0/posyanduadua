<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DataAnakSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('data_anak')->insert([
            'nik' => '1233456763477733',
            'nama_ibu' => 'Elly Nur Azizah',
            'nama_anak' => 'Adinda Widya Putri',
            'tempat_lahir' => 'Jember',
            'tanggal_lahir' => '2025-03-08',
            'usia' => '2 Tahun',
            'jenis_kelamin' => 'Perempuan',
            'created_at' => Carbon::parse('2025-03-07 15:50:40'),
            'updated_at' => Carbon::parse('2025-03-07 15:51:03'),
        ]);
    }
}

