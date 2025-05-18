<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Anak;
use App\Models\JenisImunisasi;

class ImunisasiSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test JenisImunisasi if it doesn't exist
        $jenisImunisasi = JenisImunisasi::firstOrCreate(
            ['nama' => 'Vaksin Rubela'],
            [
                'min_umur_bulan' => 9,
                'max_umur_bulan' => 15,
                'keterangan' => 'Untuk mencegah penyakit Rubela',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
        
        // Get the first anak or create a dummy if none exists
        $anak = Anak::first();
        
        if ($anak) {
            DB::table('imunisasi')->insert([
                'anak_id' => $anak->id,
                'jenis_id' => $jenisImunisasi->id,
                'tanggal' => '2025-03-08',
                'status' => 'Tidak Sesuai',
                'created_at' => Carbon::parse('2025-03-07 15:21:47'),
                'updated_at' => Carbon::parse('2025-03-07 15:21:47'),
            ]);
        } else {
            echo "No anak records found. Please seed the anak table first.\n";
        }
    }
}
