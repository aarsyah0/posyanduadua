<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\DataOrangTuaController;
use App\Http\Controllers\DataAnakController;
use App\Http\Controllers\PerkembanganAnakController;
use App\Http\Controllers\ImunisasiController;
use App\Http\Controllers\VitaminController;
use App\Http\Controllers\StuntingController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\JenisImunisasiController;
use App\Http\Controllers\JenisVitaminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route untuk autentikasi
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/store', [DashboardController::class, 'store'])->name('dashboard.store');

    // Jadwal
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
    
    // Jadwal Pemeriksaan routes
    Route::post('/jadwal/pemeriksaan', [JadwalController::class, 'storePemeriksaan'])->name('jadwal.pemeriksaan.store');
    Route::get('/jadwal/pemeriksaan/{id}/edit', [JadwalController::class, 'editPemeriksaan'])->name('jadwal.pemeriksaan.edit');
    Route::put('/jadwal/pemeriksaan/{id}', [JadwalController::class, 'updatePemeriksaan'])->name('jadwal.pemeriksaan.update');
    Route::delete('/jadwal/pemeriksaan/{id}', [JadwalController::class, 'destroyPemeriksaan'])->name('jadwal.pemeriksaan.destroy');
    
    // Jadwal Imunisasi routes
    Route::post('/jadwal/imunisasi', [JadwalController::class, 'storeImunisasi'])->name('jadwal.imunisasi.store');
    Route::get('/jadwal/imunisasi/{id}/edit', [JadwalController::class, 'editImunisasi'])->name('jadwal.imunisasi.edit');
    Route::put('/jadwal/imunisasi/{id}', [JadwalController::class, 'updateImunisasi'])->name('jadwal.imunisasi.update');
    Route::delete('/jadwal/imunisasi/{id}', [JadwalController::class, 'destroyImunisasi'])->name('jadwal.imunisasi.destroy');
    
    // Jadwal Vitamin routes
    Route::post('/jadwal/vitamin', [JadwalController::class, 'storeVitamin'])->name('jadwal.vitamin.store');
    Route::get('/jadwal/vitamin/{id}/edit', [JadwalController::class, 'editVitamin'])->name('jadwal.vitamin.edit');
    Route::put('/jadwal/vitamin/{id}', [JadwalController::class, 'updateVitamin'])->name('jadwal.vitamin.update');
    Route::delete('/jadwal/vitamin/{id}', [JadwalController::class, 'destroyVitamin'])->name('jadwal.vitamin.destroy');

    // Artikel
    Route::resource('artikel', ArtikelController::class);
    Route::get('/artikel/search', [ArtikelController::class, 'search'])->name('artikel.search');

    // Data Orang Tua
    Route::resource('data_orangtua', DataOrangTuaController::class);

    // Data Anak
    Route::resource('anak', DataAnakController::class)->except(['create', 'store', 'edit', 'update']);
    
    // AnakController dari Api untuk diakses lewat web
    Route::prefix('api-anak')->group(function() {
        Route::get('/', [App\Http\Controllers\Api\AnakController::class, 'index'])->name('api-anak.index');
        Route::get('/{id}', [App\Http\Controllers\Api\AnakController::class, 'show'])->name('api-anak.show');
        Route::post('/', [App\Http\Controllers\Api\AnakController::class, 'store'])->name('api-anak.store');
        Route::put('/{id}', [App\Http\Controllers\Api\AnakController::class, 'update'])->name('api-anak.update');
        Route::delete('/{id}', [App\Http\Controllers\Api\AnakController::class, 'destroy'])->name('api-anak.destroy');
    });

    // Perkembangan Anak
    // Route::resource('perkembangan', PerkembanganAnakController::class);
    // Mendefinisikan route secara manual
    Route::get('/perkembangan', [PerkembanganAnakController::class, 'index'])->name('perkembangan.index');
    Route::get('/perkembangan/create', [PerkembanganAnakController::class, 'create'])->name('perkembangan.create');
    Route::post('/perkembangan', [PerkembanganAnakController::class, 'store'])->name('perkembangan.store');
    Route::get('/perkembangan/riwayat/{anak_id}', [PerkembanganAnakController::class, 'riwayat'])->name('perkembangan.riwayat');
    Route::get('/perkembangan/{id}', [PerkembanganAnakController::class, 'show'])->name('perkembangan.show');
    Route::get('/perkembangan/{id}/edit', [PerkembanganAnakController::class, 'edit'])->name('perkembangan.edit');
    Route::put('/perkembangan/{id}', [PerkembanganAnakController::class, 'update'])->name('perkembangan.update');
    Route::delete('/perkembangan/{id}', [PerkembanganAnakController::class, 'destroy'])->name('perkembangan.destroy');

    // API untuk modal data
    Route::get('/api/anak', function() {
        return \App\Models\Anak::all(['id', 'nama_anak']);
    });

    // Imunisasi
    Route::resource('imunisasi', ImunisasiController::class);
    Route::post('/imunisasi/register-from-jadwal', [ImunisasiController::class, 'registerFromJadwal'])->name('imunisasi.register-from-jadwal');

    // Vitamin
    Route::resource('vitamin', VitaminController::class);
    Route::post('/vitamin/register-from-jadwal', [VitaminController::class, 'registerFromJadwal'])->name('vitamin.register-from-jadwal');

    // Stunting
    Route::resource('stunting', StuntingController::class);

    // Data Pengguna
    Route::resource('pengguna', PenggunaController::class);

    // Data Petugas
    Route::resource('petugas', PetugasController::class);

    // Jenis Imunisasi
    Route::resource('jenis_imunisasi', JenisImunisasiController::class);

    // Jenis Vitamin
    Route::resource('jenis_vitamin', JenisVitaminController::class);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

