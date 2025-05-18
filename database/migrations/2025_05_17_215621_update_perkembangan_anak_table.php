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
        if (!Schema::hasColumn('perkembangan_anak', 'updated_from_id')) {
            Schema::table('perkembangan_anak', function (Blueprint $table) {
                // Add columns only if they don't exist
                $table->foreignId('updated_from_id')->nullable()->constrained('perkembangan_anak')->onDelete('set null');
                $table->boolean('is_updated')->default(false)->after('updated_from_id');
                $table->foreignId('updated_by_id')->nullable()->after('is_updated')
                    ->constrained('perkembangan_anak')->onDelete('set null');
                
                // Add soft deletes
                $table->softDeletes();

                // Add indexes
                $table->index(['anak_id', 'tanggal']);
                $table->index('is_updated');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perkembangan_anak', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['anak_id', 'tanggal']);
            $table->dropIndex(['is_updated']);

            // Drop soft deletes
            $table->dropSoftDeletes();

            // Drop columns
            $table->dropForeign(['updated_from_id']);
            $table->dropForeign(['updated_by_id']);
            $table->dropColumn(['updated_from_id', 'is_updated', 'updated_by_id']);
        });
    }
};
