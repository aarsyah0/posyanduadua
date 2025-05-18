<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('petugas')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('adminpassword'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'username' => 'mamida',
                'password' => Hash::make('mamidapassword'),
                'created_at' => Carbon::parse('2025-03-08 06:16:33'),
                'updated_at' => Carbon::parse('2025-03-08 06:16:33'),
            ],
        ]);
    }
}
