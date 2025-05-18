<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Create new tables if they don't exist
        if (!Schema::hasTable('jadwal_pemeriksaan')) {
            Schema::create('jadwal_pemeriksaan', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 100)->default('Posyandu');
                $table->date('tanggal');
                $table->time('waktu');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('jadwal_imunisasi')) {
            Schema::create('jadwal_imunisasi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jenis_imunisasi_id')->constrained('jenis_imunisasi')->onDelete('cascade');
                $table->date('tanggal');
                $table->time('waktu');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('jadwal_vitamin')) {
            Schema::create('jadwal_vitamin', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jenis_vitamin_id')->constrained('jenis_vitamin')->onDelete('cascade');
                $table->date('tanggal');
                $table->time('waktu');
                $table->timestamps();
            });
        }

        // Migrate data from old jadwal table if it exists
        if (Schema::hasTable('jadwal')) {
            // Get all records from the 'jadwal' table
            $jadwalRecords = DB::table('jadwal')->get();
            
            foreach ($jadwalRecords as $record) {
                switch ($record->jenis) {
                    case 'pemeriksaan rutin':
                        // Insert into jadwal_pemeriksaan
                        DB::table('jadwal_pemeriksaan')->insert([
                            'judul' => $record->judul,
                            'tanggal' => $record->tanggal,
                            'waktu' => $record->waktu,
                            'created_at' => $record->created_at,
                            'updated_at' => $record->updated_at
                        ]);
                        break;
                        
                    case 'imunisasi':
                        // Find suitable jenis_imunisasi_id
                        $jenisImunisasi = DB::table('jenis_imunisasi')->first();
                        $jenisImunisasiId = $jenisImunisasi ? $jenisImunisasi->id : null;
                        
                        if ($jenisImunisasiId) {
                            // Insert into jadwal_imunisasi
                            DB::table('jadwal_imunisasi')->insert([
                                'jenis_imunisasi_id' => $jenisImunisasiId,
                                'tanggal' => $record->tanggal,
                                'waktu' => $record->waktu,
                                'created_at' => $record->created_at,
                                'updated_at' => $record->updated_at
                            ]);
                        }
                        break;
                        
                    case 'vitamin':
                        // Find suitable jenis_vitamin_id
                        $jenisVitamin = DB::table('jenis_vitamin')->first();
                        $jenisVitaminId = $jenisVitamin ? $jenisVitamin->id : null;
                        
                        if ($jenisVitaminId) {
                            // Insert into jadwal_vitamin
                            DB::table('jadwal_vitamin')->insert([
                                'jenis_vitamin_id' => $jenisVitaminId,
                                'tanggal' => $record->tanggal,
                                'waktu' => $record->waktu,
                                'created_at' => $record->created_at,
                                'updated_at' => $record->updated_at
                            ]);
                        }
                        break;
                }
            }
            
            // Drop the old 'jadwal' table after migrating the data
            Schema::dropIfExists('jadwal');
        }
    }

    public function down(): void
    {
        // Create the old 'jadwal' table if it doesn't exist
        if (!Schema::hasTable('jadwal')) {
            Schema::create('jadwal', function (Blueprint $table) {
                $table->id();
                $table->string('judul', 100)->default('Posyandu');
                $table->enum('jenis', ['imunisasi', 'vitamin', 'pemeriksaan rutin']);
                $table->date('tanggal');
                $table->time('waktu');
                $table->timestamps();
            });
            
            // Migrate data back from jadwal_pemeriksaan if it exists
            if (Schema::hasTable('jadwal_pemeriksaan')) {
                $pemeriksaanRecords = DB::table('jadwal_pemeriksaan')->get();
                foreach ($pemeriksaanRecords as $record) {
                    DB::table('jadwal')->insert([
                        'judul' => $record->judul,
                        'jenis' => 'pemeriksaan rutin',
                        'tanggal' => $record->tanggal,
                        'waktu' => $record->waktu,
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at
                    ]);
                }
            }
            
            // Migrate data back from jadwal_imunisasi if it exists
            if (Schema::hasTable('jadwal_imunisasi') && Schema::hasTable('jenis_imunisasi')) {
                $imunisasiRecords = DB::table('jadwal_imunisasi')
                    ->join('jenis_imunisasi', 'jadwal_imunisasi.jenis_imunisasi_id', '=', 'jenis_imunisasi.id')
                    ->select('jadwal_imunisasi.*', 'jenis_imunisasi.nama')
                    ->get();
                    
                foreach ($imunisasiRecords as $record) {
                    DB::table('jadwal')->insert([
                        'judul' => $record->nama ?? 'Imunisasi',
                        'jenis' => 'imunisasi',
                        'tanggal' => $record->tanggal,
                        'waktu' => $record->waktu,
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at
                    ]);
                }
            }
            
            // Migrate data back from jadwal_vitamin if it exists
            if (Schema::hasTable('jadwal_vitamin') && Schema::hasTable('jenis_vitamin')) {
                $vitaminRecords = DB::table('jadwal_vitamin')
                    ->join('jenis_vitamin', 'jadwal_vitamin.jenis_vitamin_id', '=', 'jenis_vitamin.id')
                    ->select('jadwal_vitamin.*', 'jenis_vitamin.nama')
                    ->get();
                    
                foreach ($vitaminRecords as $record) {
                    DB::table('jadwal')->insert([
                        'judul' => $record->nama ?? 'Vitamin',
                        'jenis' => 'vitamin',
                        'tanggal' => $record->tanggal,
                        'waktu' => $record->waktu,
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at
                    ]);
                }
            }
        }

        // Only drop these tables if they exist and the old jadwal table has been created
        if (Schema::hasTable('jadwal')) {
            Schema::dropIfExists('jadwal_pemeriksaan');
            Schema::dropIfExists('jadwal_imunisasi');
            Schema::dropIfExists('jadwal_vitamin');
        }
    }
}; 