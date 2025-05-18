<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add is_implemented column to jadwal_imunisasi if it doesn't exist
        if (Schema::hasTable('jadwal_imunisasi') && !Schema::hasColumn('jadwal_imunisasi', 'is_implemented')) {
            Schema::table('jadwal_imunisasi', function (Blueprint $table) {
                $table->boolean('is_implemented')->default(false)->after('waktu');
            });
        }
        
        // Add is_implemented column to jadwal_vitamin if it doesn't exist
        if (Schema::hasTable('jadwal_vitamin') && !Schema::hasColumn('jadwal_vitamin', 'is_implemented')) {
            Schema::table('jadwal_vitamin', function (Blueprint $table) {
                $table->boolean('is_implemented')->default(false)->after('waktu');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove is_implemented column from jadwal_imunisasi if it exists
        if (Schema::hasTable('jadwal_imunisasi') && Schema::hasColumn('jadwal_imunisasi', 'is_implemented')) {
            Schema::table('jadwal_imunisasi', function (Blueprint $table) {
                $table->dropColumn('is_implemented');
            });
        }
        
        // Remove is_implemented column from jadwal_vitamin if it exists
        if (Schema::hasTable('jadwal_vitamin') && Schema::hasColumn('jadwal_vitamin', 'is_implemented')) {
            Schema::table('jadwal_vitamin', function (Blueprint $table) {
                $table->dropColumn('is_implemented');
            });
        }
    }
};
