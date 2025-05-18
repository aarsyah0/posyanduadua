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
        Schema::table('jadwal_pemeriksaan', function (Blueprint $table) {
            $table->boolean('is_implemented')->default(false)->after('waktu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pemeriksaan', function (Blueprint $table) {
            $table->dropColumn('is_implemented');
        });
    }
};
