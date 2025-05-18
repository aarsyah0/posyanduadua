<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Memperbaiki rentang usia untuk jenis imunisasi
        // DPT-HB-HIP 1 & Polio 2 (id=3)
        DB::table('jenis_imunisasi')
            ->where('id', 3)
            ->update([
                'min_umur_hari' => 31, // Mengubah dari 0 hari menjadi 30 hari (1 bulan)
                'max_umur_hari' => 60
            ]);

        // DPT-HB-HIP 2 & Polio 3 (id=4)
        DB::table('jenis_imunisasi')
            ->where('id', 4)
            ->update([
                'min_umur_hari' => 61, // Mengubah dari 0 hari menjadi 60 hari (2 bulan)
                'max_umur_hari' => 90
            ]);

        // DPT-HB-HIP 3 & Polio 4 (id=5)
        DB::table('jenis_imunisasi')
            ->where('id', 5)
            ->update([
                'min_umur_hari' => 91, // Mengubah dari 0 hari menjadi 90 hari (3 bulan)
                'max_umur_hari' => 120
            ]);

        // Campak (id=6)
        DB::table('jenis_imunisasi')
            ->where('id', 6)
            ->update([
                'min_umur_hari' => 270, // Mengubah dari 0 hari menjadi 270 hari (9 bulan)
                'max_umur_hari' => 360
            ]);

        // HB-0 (id=1)
        DB::table('jenis_imunisasi')
            ->where('id', 1)
            ->update([
                'min_umur_hari' => 0,
                'max_umur_hari' => 7
            ]);

        // BCG & Polio 1 (id=2)
        DB::table('jenis_imunisasi')
            ->where('id', 2)
            ->update([
                'min_umur_hari' => 8,
                'max_umur_hari' => 30
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke nilai awal
        DB::table('jenis_imunisasi')
            ->where('id', 3)
            ->update([
                'min_umur_hari' => 0,
                'max_umur_hari' => 60
            ]);

        DB::table('jenis_imunisasi')
            ->where('id', 4)
            ->update([
                'min_umur_hari' => 0,
                'max_umur_hari' => 90
            ]);

        DB::table('jenis_imunisasi')
            ->where('id', 5)
            ->update([
                'min_umur_hari' => 0,
                'max_umur_hari' => 120
            ]);

        DB::table('jenis_imunisasi')
            ->where('id', 6)
            ->update([
                'min_umur_hari' => 0,
                'max_umur_hari' => 270
            ]);

        // HB-0 (id=1)
        DB::table('jenis_imunisasi')
            ->where('id', 1)
            ->update([
                'min_umur_hari' => 0,
                'max_umur_hari' => 7
            ]);

        // BCG & Polio 1 (id=2)
        DB::table('jenis_imunisasi')
            ->where('id', 2)
            ->update([
                'min_umur_hari' => 0,
                'max_umur_hari' => 30
            ]);
    }
};
