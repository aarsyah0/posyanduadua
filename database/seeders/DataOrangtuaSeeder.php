<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DataOrangtuaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('data_orangtua')->insert([
            'nik' => '1223344556452345',
            'nama_ibu' => 'Elly Nur Azizah',
            'no_telp' => '081216530913',
            'alamat' => 'Karang semanding',
            'nama_anak' => 'Adinda Widya Putri',
            'created_at' => Carbon::parse('2025-03-07 15:28:28'),
            'updated_at' => Carbon::parse('2025-03-11 20:45:13'),
        ]);
    }
}
