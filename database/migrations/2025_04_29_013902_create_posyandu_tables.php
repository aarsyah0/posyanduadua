<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table: pengguna
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama', 100);
            $table->string('email', 100)->nullable()->unique();
            $table->string('password', 255);
            $table->enum('role', ['parent', 'admin'])->default('parent');
            $table->string('no_telp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });

        // Table: anak
        Schema::create('anak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->string('nama_anak', 100);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('usia', 20)->nullable();
            $table->timestamps();
        });

        // Table: jenis_imunisasi
        Schema::create('jenis_imunisasi', function (Blueprint $table) {
            $table->id();
            $table->enum('nama', [
                'HB-0', 
                'BCG & Polio 1', 
                'DPT-HB-HIP 1 & Polio 2', 
                'DPT-HB-HIP 2 & Polio 3', 
                'DPT-HB-HIP 3 & Polio 4', 
                'Campak'
            ]);
            $table->integer('min_umur_hari');
            $table->integer('max_umur_hari');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Table: jenis_vitamin
        Schema::create('jenis_vitamin', function (Blueprint $table) {
            $table->id();
            $table->enum('nama', ['A Biru', 'A Merah']);
            $table->integer('min_umur_bulan');
            $table->integer('max_umur_bulan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Table: jadwal_pemeriksaan
        Schema::create('jadwal_pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 100)->default('Posyandu');
            $table->date('tanggal');
            $table->time('waktu');
            $table->timestamps();
        });
        
        // Table: jadwal_imunisasi
        Schema::create('jadwal_imunisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_imunisasi_id')->constrained('jenis_imunisasi')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->boolean('is_implemented')->default(false);
            $table->timestamps();
        });

        // Table: jadwal_vitamin
        Schema::create('jadwal_vitamin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_vitamin_id')->constrained('jenis_vitamin')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->boolean('is_implemented')->default(false);
            $table->timestamps();
        });

        // Table: imunisasi
        Schema::create('imunisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anak')->onDelete('cascade');
            $table->foreignId('jenis_id')->nullable()->constrained('jenis_imunisasi')->onDelete('set null');
            $table->foreignId('jadwal_imunisasi_id')->nullable()->constrained('jadwal_imunisasi')->onDelete('set null');
            $table->date('tanggal');
            $table->enum('status', ['Belum', 'Selesai Sesuai', 'Selesai Tidak Sesuai'])->default('Belum');
            $table->timestamps();
        });

        // Table: vitamin
        Schema::create('vitamin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anak')->onDelete('cascade');
            $table->foreignId('jenis_id')->nullable()->constrained('jenis_vitamin')->onDelete('set null');
            $table->foreignId('jadwal_vitamin_id')->nullable()->constrained('jadwal_vitamin')->onDelete('set null');
            $table->date('tanggal');
            $table->enum('status', ['Belum', 'Selesai'])->default('Belum');
            $table->timestamps();
        });
        
        // Table: perkembangan_anak
        Schema::create('perkembangan_anak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anak')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('berat_badan', 5, 2);
            $table->decimal('tinggi_badan', 5, 2);
            $table->foreignId('updated_from_id')->nullable()->constrained('perkembangan_anak')->onDelete('set null');
            $table->boolean('is_updated')->default(false);
            $table->foreignId('updated_by_id')->nullable()->constrained('perkembangan_anak')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['anak_id', 'tanggal']);
            $table->index('is_updated');
        });

        // Table: stunting
        Schema::create('stunting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anak')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('usia', 10);
            $table->decimal('berat_badan', 5, 2);
            $table->decimal('tinggi_badan', 5, 2);
            $table->text('catatan')->nullable();
            $table->enum('status', ['Stunting', 'Tidak Stunting']);
            $table->foreignId('perkembangan_id')->constrained('perkembangan_anak')->onDelete('cascade');
            $table->timestamps();
        });
        
        // Table: artikel
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->string('gambar_artikel', 255);
            $table->text('isi_artikel');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel');
        Schema::dropIfExists('stunting');
        Schema::dropIfExists('perkembangan_anak');
        Schema::dropIfExists('jadwal_vitamin');
        Schema::dropIfExists('jadwal_imunisasi');
        Schema::dropIfExists('jadwal_pemeriksaan');
        Schema::dropIfExists('vitamin');
        Schema::dropIfExists('imunisasi');
        Schema::dropIfExists('jenis_vitamin');
        Schema::dropIfExists('jenis_imunisasi');
        Schema::dropIfExists('anak');
        Schema::dropIfExists('pengguna');
    }
};