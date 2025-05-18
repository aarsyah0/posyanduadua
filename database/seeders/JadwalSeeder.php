<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jadwal')->insert([
            [
                'tanggal' => '2025-03-03',
                'waktu' => '19:00:00',
                'created_at' => Carbon::parse('2025-03-07 05:00:31'),
                'updated_at' => Carbon::parse('2025-03-07 05:00:31'),
            ],
            [
                'tanggal' => '2025-03-05',
                'waktu' => '19:01:00',
                'created_at' => Carbon::parse('2025-03-07 05:00:51'),
                'updated_at' => Carbon::parse('2025-03-07 12:08:46'),
            ],
        ]);
    }
}
