<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnakController;
use App\Http\Controllers\Api\PerkembanganAnakApiController;
use App\Http\Controllers\Api\JadwalApiController;
use App\Http\Controllers\Api\ImunisasiApiController;
use App\Http\Controllers\Api\VitaminApiController;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rute publik
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Endpoint untuk testing (tidak memerlukan autentikasi)
Route::post('/test-jadwal-complete/{id}', [ImunisasiApiController::class, 'completeJadwal']);
Route::post('/test-vitamin-complete/{id}', [VitaminApiController::class, 'completeJadwal']);
Route::post('/test-check-imunisasi-status/{id}', [JadwalApiController::class, 'checkImunisasiStatus']);
Route::post('/test-check-vitamin-status/{id}', [JadwalApiController::class, 'checkVitaminStatus']);
Route::post('/test-update-imunisasi-status/{id}', [JadwalApiController::class, 'updateImunisasiStatus']);
Route::post('/test-update-vitamin-status/{id}', [JadwalApiController::class, 'updateVitaminStatus']);
Route::post('/test-update-pemeriksaan-status/{id}', [JadwalApiController::class, 'updatePemeriksaanStatus']);

// Rute yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Info pengguna
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Endpoint Anak CRUD
    Route::apiResource('anak', AnakController::class);
    
    // Endpoint tambahan untuk Anak
    Route::get('/anak/pengguna/nik/{nik}', [AnakController::class, 'findByPenggunaNik']);
    Route::post('/anak/link-to-parent', [AnakController::class, 'linkToParent']);
    
    // Endpoint khusus untuk aplikasi mobile
    Route::get('/mobile/anak/pengguna/{pengguna_id}', [AnakController::class, 'getAnakByPenggunaId']);
    
    // Endpoint Perkembangan Anak
    Route::get('/perkembangan/anak/{anak_id}', [PerkembanganAnakApiController::class, 'getByAnakId']);
    Route::get('/perkembangan/{id}', [PerkembanganAnakApiController::class, 'show']);
    Route::post('/perkembangan', [PerkembanganAnakApiController::class, 'store']);
    Route::put('/perkembangan/{id}', [PerkembanganAnakApiController::class, 'update']);
    Route::delete('/perkembangan/{id}', [PerkembanganAnakApiController::class, 'destroy']);
    
    // Endpoint Jadwal (Schedules)
    Route::get('/jadwal', [JadwalApiController::class, 'index']);
    Route::get('/jadwal/upcoming', [JadwalApiController::class, 'upcoming']);
    Route::get('/jadwal/upcoming/anak/{anakId}', [JadwalApiController::class, 'upcomingForChild']);
    Route::get('/jadwal/imunisasi/anak/{anakId}', [JadwalApiController::class, 'imunisasiForChild']);
    Route::get('/jadwal/vitamin/anak/{anakId}', [JadwalApiController::class, 'vitaminForChild']);
    Route::get('/jadwal/imunisasi/age-ranges', [JadwalApiController::class, 'imunisasiAgeRanges']);
    Route::get('/jadwal/vitamin/age-ranges', [JadwalApiController::class, 'vitaminAgeRanges']);
    Route::get('/jadwal/pemeriksaan', [JadwalApiController::class, 'pemeriksaan']);
    Route::get('/jadwal/imunisasi', [JadwalApiController::class, 'imunisasi']);
    Route::get('/jadwal/vitamin', [JadwalApiController::class, 'vitamin']);
    Route::get('/jenis-imunisasi', [JadwalApiController::class, 'jenisImunisasi']);
    Route::get('/jenis-vitamin', [JadwalApiController::class, 'jenisVitamin']);
    Route::get('/jadwal/riwayat/anak/{anakId}', [JadwalApiController::class, 'riwayatAnak']);
    Route::get('/jadwal/nearest/{anakId}', [JadwalApiController::class, 'nearestSchedule']);
    
    // Endpoint Imunisasi
    Route::get('/imunisasi', [ImunisasiApiController::class, 'index']);
    Route::get('/imunisasi/{id}', [ImunisasiApiController::class, 'show']);
    Route::put('/imunisasi/{id}', [ImunisasiApiController::class, 'update']);
    Route::get('/imunisasi/anak/{anakId}', [ImunisasiApiController::class, 'getByAnakId']);
    Route::get('/imunisasi/jadwal/status', [ImunisasiApiController::class, 'checkImplementationStatus']);
    Route::get('/imunisasi/jadwal/eligible-children/{jadwalId}', [ImunisasiApiController::class, 'getEligibleChildren']);
    Route::post('/imunisasi/jadwal/confirm/{id}', [ImunisasiApiController::class, 'confirmImplementation']);
    Route::post('/imunisasi/jadwal/complete/{id}', [ImunisasiApiController::class, 'completeJadwal']);
    Route::get('/imunisasi/jadwal/anak/{anakId}', [ImunisasiApiController::class, 'getJadwalForAnak']);
    Route::post('/imunisasi/create-from-jadwal', [ImunisasiApiController::class, 'createFromJadwal']);
    
    // Endpoint Vitamin
    Route::get('/vitamin', [VitaminApiController::class, 'index']);
    Route::get('/vitamin/{id}', [VitaminApiController::class, 'show']);
    Route::put('/vitamin/{id}', [VitaminApiController::class, 'update']);
    Route::get('/vitamin/anak/{anakId}', [VitaminApiController::class, 'getByAnakId']);
    Route::get('/vitamin/jadwal/status', [VitaminApiController::class, 'checkImplementationStatus']);
    Route::get('/vitamin/jadwal/eligible-children/{jadwalId}', [VitaminApiController::class, 'getEligibleChildren']);
    Route::post('/vitamin/jadwal/confirm/{id}', [VitaminApiController::class, 'confirmImplementation']);
    Route::post('/vitamin/jadwal/complete/{id}', [VitaminApiController::class, 'completeJadwal']);
    Route::get('/vitamin/jadwal/anak/{anakId}', [VitaminApiController::class, 'getJadwalForAnak']);
    Route::post('/vitamin/create-from-jadwal', [VitaminApiController::class, 'createFromJadwal']);
});

// Endpoint untuk debugging (Hanya untuk development)
if (app()->environment('local')) {
    Route::get('/debug/auth-check', function (Request $request) {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->user(),
            'token_valid' => $request->bearerToken() ? true : false,
        ]);
    })->middleware('auth:sanctum');
    
    // Debugging route for jadwal filter
    Route::get('/debug/jadwal-filter/{anakId}', function ($anakId) {
        $anak = App\Models\Anak::findOrFail($anakId);
        
        $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
        $usiaBulan = Carbon::now()->diffInMonths($tanggalLahir);
        $usiaHari = Carbon::now()->diffInDays($tanggalLahir);
        
        // Get all jenis imunisasi for reference
        $jenisImunisasi = App\Models\JenisImunisasi::all();
        
        // Get all jenis vitamin for reference
        $jenisVitamin = App\Models\JenisVitamin::all();
        
        // Filter jenis imunisasi yang sesuai usia
        $filteredJenisImunisasi = $jenisImunisasi->filter(function($jenis) use ($usiaHari) {
            return ($usiaHari >= $jenis->min_umur_hari && $usiaHari <= $jenis->max_umur_hari);
        })->values();
            
        // Filter jenis vitamin yang sesuai usia
        $filteredJenisVitamin = $jenisVitamin->filter(function($jenis) use ($usiaBulan) {
            return ($usiaBulan >= $jenis->min_umur_bulan && $usiaBulan <= $jenis->max_umur_bulan);
        })->values();
        
        // Test controller methods directly
        $jadwalApiController = new App\Http\Controllers\Api\JadwalApiController();
        $today = Carbon::today()->format('Y-m-d');
        $resultImunisasi = $jadwalApiController->getAgeAppropriateImunisasi($usiaBulan, $today);
        $resultVitamin = $jadwalApiController->getAgeAppropriateVitamin($usiaBulan, $today);
        
        return response()->json([
            'debug_info' => 'Jadwal Filter Debugging - Database Based',
            'anak' => [
                'id' => $anak->id,
                'nama' => $anak->nama_anak,
                'tanggal_lahir' => $anak->tanggal_lahir->format('Y-m-d'),
                'usia_bulan' => $usiaBulan,
                'usia_hari' => $usiaHari
            ],
            'jenis_imunisasi' => [
                'all' => $jenisImunisasi->toArray(),
                'filtered' => $filteredJenisImunisasi->toArray()
            ],
            'jenis_vitamin' => [
                'all' => $jenisVitamin->toArray(),
                'filtered' => $filteredJenisVitamin->toArray()
            ],
            'controller_results' => [
                'imunisasi_count' => count($resultImunisasi), 
                'imunisasi' => $resultImunisasi,
                'vitamin_count' => count($resultVitamin),
                'vitamin' => $resultVitamin
            ]
        ]);
    });
    
    // Test endpoint to call the controller directly
    Route::get('/debug/test-filter-imunisasi/{usiaBulan}', function ($usiaBulan) {
        $jadwalApiController = new App\Http\Controllers\Api\JadwalApiController();
        $today = Carbon::today()->format('Y-m-d');
        $result = $jadwalApiController->getAgeAppropriateImunisasi($usiaBulan, $today);
        
        return response()->json([
            'usia_bulan' => $usiaBulan,
            'result_count' => count($result),
            'results' => $result
        ]);
    });
    
    Route::get('/debug/test-filter-vitamin/{usiaBulan}', function ($usiaBulan) {
        $jadwalApiController = new App\Http\Controllers\Api\JadwalApiController();
        $today = Carbon::today()->format('Y-m-d');
        $result = $jadwalApiController->getAgeAppropriateVitamin($usiaBulan, $today);
        
        return response()->json([
            'usia_bulan' => $usiaBulan,
            'result_count' => count($result),
            'results' => $result
        ]);
    });
}
