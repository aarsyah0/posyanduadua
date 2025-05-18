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
        // Tambahkan field jika belum ada
        if (!Schema::hasColumn('jenis_imunisasi', 'min_umur_hari')) {
            Schema::table('jenis_imunisasi', function (Blueprint $table) {
                $table->integer('min_umur_hari')->default(0);
            });
        }
        if (!Schema::hasColumn('jenis_imunisasi', 'max_umur_hari')) {
            Schema::table('jenis_imunisasi', function (Blueprint $table) {
                $table->integer('max_umur_hari')->default(0);
            });
        }
        // Memperbaiki rentang usia untuk BCG & Polio 1 (id=2)
        DB::table('jenis_imunisasi')
            ->where('id', 2)
            ->update([
                'min_umur_hari' => 7, // Mengubah dari 0 hari menjadi 7 hari
                'max_umur_hari' => 30 // Tetap 30 hari (1 bulan)
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke nilai awal
        DB::table('jenis_imunisasi')
            ->where('id', 2)
            ->update([
                'min_umur_hari' => 0,
                'max_umur_hari' => 30
            ]);
    }
}; 