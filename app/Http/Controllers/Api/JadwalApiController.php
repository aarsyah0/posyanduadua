<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPemeriksaan;
use App\Models\JadwalImunisasi;
use App\Models\JadwalVitamin;
use App\Models\JenisImunisasi;
use App\Models\JenisVitamin;
use Carbon\Carbon;
use App\Models\Imunisasi;
use App\Models\Vitamin;
use Illuminate\Support\Facades\Validator;
use App\Models\Anak;

class JadwalApiController extends Controller
{
    /**
     * Get all schedules (combined from all types)
     */
    public function index()
    {
        $pemeriksaan = JadwalPemeriksaan::select(
                'id', 
                'judul as nama', 
                \DB::raw("'pemeriksaan rutin' as jenis"), 
                'tanggal', 
                'waktu', 
                'created_at',
                \DB::raw('NULL as min_umur_hari'),
                \DB::raw('NULL as max_umur_hari'),
                \DB::raw('NULL as min_umur_bulan'),
                \DB::raw('NULL as max_umur_bulan'),
                \DB::raw('NULL as keterangan'),
                'is_implemented'
            )
            ->orderBy('tanggal', 'desc')
            ->get();
            
        $imunisasi = JadwalImunisasi::select(
                'jadwal_imunisasi.id', 
                'jenis_imunisasi.nama', 
                \DB::raw("'imunisasi' as jenis"), 
                'jadwal_imunisasi.tanggal', 
                'jadwal_imunisasi.waktu', 
                'jadwal_imunisasi.created_at',
                'jenis_imunisasi.min_umur_hari',
                'jenis_imunisasi.max_umur_hari',
                \DB::raw('NULL as min_umur_bulan'),
                \DB::raw('NULL as max_umur_bulan'),
                'jenis_imunisasi.keterangan',
                'jadwal_imunisasi.is_implemented'
            )
            ->join('jenis_imunisasi', 'jadwal_imunisasi.jenis_imunisasi_id', '=', 'jenis_imunisasi.id')
            ->orderBy('jadwal_imunisasi.tanggal', 'desc')
            ->get();
            
        $vitamin = JadwalVitamin::select(
                'jadwal_vitamin.id', 
                'jenis_vitamin.nama', 
                \DB::raw("'vitamin' as jenis"), 
                'jadwal_vitamin.tanggal', 
                'jadwal_vitamin.waktu', 
                'jadwal_vitamin.created_at',
                \DB::raw('NULL as min_umur_hari'),
                \DB::raw('NULL as max_umur_hari'),
                'jenis_vitamin.min_umur_bulan',
                'jenis_vitamin.max_umur_bulan',
                'jenis_vitamin.keterangan',
                'jadwal_vitamin.is_implemented'
            )
            ->join('jenis_vitamin', 'jadwal_vitamin.jenis_vitamin_id', '=', 'jenis_vitamin.id')
            ->orderBy('jadwal_vitamin.tanggal', 'desc')
            ->get();
        
        $jadwal = $pemeriksaan->concat($imunisasi)->concat($vitamin)
            ->sortByDesc('tanggal')
            ->values()
            ->all();
            
        return response()->json([
            'status' => 'success',
            'data' => $jadwal
        ]);
    }

    /**
     * Get upcoming schedules (all types)
     */
    public function upcoming()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $pemeriksaan = JadwalPemeriksaan::select(
                'id', 
                'judul as nama', 
                \DB::raw("'pemeriksaan rutin' as jenis"), 
                'tanggal', 
                'waktu', 
                'created_at',
                \DB::raw('NULL as min_umur_hari'),
                \DB::raw('NULL as max_umur_hari'),
                \DB::raw('NULL as min_umur_bulan'),
                \DB::raw('NULL as max_umur_bulan'),
                \DB::raw('NULL as keterangan'),
                \DB::raw('CASE 
                    WHEN is_implemented = 1 THEN "Selesai"
                    ELSE "Belum Dilaksanakan"
                END as status'),
                'is_implemented'
            )
            ->where('tanggal', '>=', $today)
            ->orderBy('tanggal', 'asc')
            ->get();
            
        $imunisasi = JadwalImunisasi::select(
                'jadwal_imunisasi.id', 
                'jenis_imunisasi.nama', 
                \DB::raw("'imunisasi' as jenis"), 
                'jadwal_imunisasi.tanggal', 
                'jadwal_imunisasi.waktu', 
                'jadwal_imunisasi.created_at',
                'jenis_imunisasi.min_umur_hari',
                'jenis_imunisasi.max_umur_hari',
                \DB::raw('NULL as min_umur_bulan'),
                \DB::raw('NULL as max_umur_bulan'),
                'jenis_imunisasi.keterangan',
                \DB::raw('CASE 
                    WHEN jadwal_imunisasi.is_implemented = 1 THEN "Selesai"
                    ELSE "Belum Dilaksanakan"
                END as status'),
                'jadwal_imunisasi.is_implemented'
            )
            ->join('jenis_imunisasi', 'jadwal_imunisasi.jenis_imunisasi_id', '=', 'jenis_imunisasi.id')
            ->where('jadwal_imunisasi.tanggal', '>=', $today)
            ->orderBy('jadwal_imunisasi.tanggal', 'asc')
            ->get();
            
        $vitamin = JadwalVitamin::select(
                'jadwal_vitamin.id', 
                'jenis_vitamin.nama', 
                \DB::raw("'vitamin' as jenis"), 
                'jadwal_vitamin.tanggal', 
                'jadwal_vitamin.waktu', 
                'jadwal_vitamin.created_at',
                \DB::raw('NULL as min_umur_hari'),
                \DB::raw('NULL as max_umur_hari'),
                'jenis_vitamin.min_umur_bulan',
                'jenis_vitamin.max_umur_bulan',
                'jenis_vitamin.keterangan',
                \DB::raw('CASE 
                    WHEN jadwal_vitamin.is_implemented = 1 THEN "Selesai"
                    ELSE "Belum Dilaksanakan"
                END as status'),
                'jadwal_vitamin.is_implemented'
            )
            ->join('jenis_vitamin', 'jadwal_vitamin.jenis_vitamin_id', '=', 'jenis_vitamin.id')
            ->where('jadwal_vitamin.tanggal', '>=', $today)
            ->orderBy('jadwal_vitamin.tanggal', 'asc')
            ->get();
        
        $jadwal = $pemeriksaan->concat($imunisasi)->concat($vitamin)
            ->sortBy('tanggal')
            ->values()
            ->all();
            
        return response()->json([
            'status' => 'success',
            'data' => $jadwal
        ]);
    }

    /**
     * Get pemeriksaan schedules
     */
    public function pemeriksaan()
    {
        $jadwal = JadwalPemeriksaan::select(
                'id', 
                'judul as nama', 
                \DB::raw("'pemeriksaan rutin' as jenis"), 
                'tanggal', 
                'waktu', 
                'created_at',
                \DB::raw('NULL as min_umur_hari'),
                \DB::raw('NULL as max_umur_hari'),
                \DB::raw('NULL as min_umur_bulan'),
                \DB::raw('NULL as max_umur_bulan'),
                \DB::raw('NULL as keterangan'),
                'is_implemented'
            )
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $jadwal
        ]);
    }

    /**
     * Get imunisasi schedules
     */
    public function imunisasi()
    {
        $jadwal = JadwalImunisasi::select(
                'jadwal_imunisasi.id', 
                'jenis_imunisasi.nama', 
                \DB::raw("'imunisasi' as jenis"), 
                'jadwal_imunisasi.tanggal', 
                'jadwal_imunisasi.waktu', 
                'jadwal_imunisasi.created_at',
                'jenis_imunisasi.min_umur_hari',
                'jenis_imunisasi.max_umur_hari',
                \DB::raw('NULL as min_umur_bulan'),
                \DB::raw('NULL as max_umur_bulan'),
                'jenis_imunisasi.keterangan',
                'jadwal_imunisasi.is_implemented'
            )
            ->join('jenis_imunisasi', 'jadwal_imunisasi.jenis_imunisasi_id', '=', 'jenis_imunisasi.id')
            ->orderBy('jadwal_imunisasi.tanggal', 'desc')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $jadwal
        ]);
    }

    /**
     * Get vitamin schedules
     */
    public function vitamin()
    {
        $jadwal = JadwalVitamin::select(
                'jadwal_vitamin.id', 
                'jenis_vitamin.nama', 
                \DB::raw("'vitamin' as jenis"), 
                'jadwal_vitamin.tanggal', 
                'jadwal_vitamin.waktu', 
                'jadwal_vitamin.created_at',
                \DB::raw('NULL as min_umur_hari'),
                \DB::raw('NULL as max_umur_hari'),
                'jenis_vitamin.min_umur_bulan',
                'jenis_vitamin.max_umur_bulan',
                'jenis_vitamin.keterangan',
                'jadwal_vitamin.is_implemented'
            )
            ->join('jenis_vitamin', 'jadwal_vitamin.jenis_vitamin_id', '=', 'jenis_vitamin.id')
            ->orderBy('jadwal_vitamin.tanggal', 'desc')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $jadwal
        ]);
    }

    /**
     * Get available immunization types
     */
    public function jenisImunisasi()
    {
        $jenis = JenisImunisasi::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $jenis
        ]);
    }

    /**
     * Get available vitamin types
     */
    public function jenisVitamin()
    {
        $jenis = JenisVitamin::all();
        
        return response()->json([
            'status' => 'success',
            'data' => $jenis
        ]);
    }

    /**
     * Check and update implementation status of imunisasi jadwal
     */
    public function checkImunisasiStatus($id)
    {
        try {
            $jadwal = JadwalImunisasi::with('jenisImunisasi')->findOrFail($id);
            
            // Panggil metode updateJadwalStatus dari ImunisasiApiController
            $imunisasiApi = new \App\Http\Controllers\Api\ImunisasiApiController();
            $imunisasiApi->updateJadwalStatus($id);
            
            // Ambil data jadwal yang sudah diupdate
            $jadwal->refresh();
            
            // Hitung jumlah imunisasi yang telah selesai untuk jadwal ini
            $completedCount = Imunisasi::where('jadwal_imunisasi_id', $id)
                ->whereIn('status', ['Selesai Sesuai', 'Selesai Tidak Sesuai'])
                ->count();
            
            return response()->json([
                'success' => true,
                'is_implemented' => $jadwal->is_implemented,
                'completed_count' => $completedCount,
                'message' => $jadwal->is_implemented 
                    ? "Jadwal imunisasi {$jadwal->jenisImunisasi->nama} berhasil diperbarui. Status: Sudah dilaksanakan ($completedCount imunisasi selesai)"
                    : "Jadwal imunisasi {$jadwal->jenisImunisasi->nama} berhasil diperbarui. Status: Belum dilaksanakan (tidak ada imunisasi selesai)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status jadwal imunisasi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Check and update implementation status of vitamin jadwal
     */
    public function checkVitaminStatus($id)
    {
        try {
            $jadwal = JadwalVitamin::with('jenisVitamin')->findOrFail($id);
            
            // Panggil metode updateJadwalStatus dari VitaminApiController
            $vitaminApi = new \App\Http\Controllers\Api\VitaminApiController();
            $vitaminApi->updateJadwalStatus($id);
            
            // Ambil data jadwal yang sudah diupdate
            $jadwal->refresh();
            
            // Hitung jumlah vitamin yang telah selesai untuk jadwal ini
            $completedCount = Vitamin::where('jadwal_vitamin_id', $id)
                ->where('status', 'Selesai')
                ->count();
            
            return response()->json([
                'success' => true,
                'is_implemented' => $jadwal->is_implemented,
                'completed_count' => $completedCount,
                'message' => $jadwal->is_implemented 
                    ? "Jadwal vitamin {$jadwal->jenisVitamin->nama} berhasil diperbarui. Status: Sudah dilaksanakan ($completedCount vitamin selesai)"
                    : "Jadwal vitamin {$jadwal->jenisVitamin->nama} berhasil diperbarui. Status: Belum dilaksanakan (tidak ada vitamin selesai)"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status jadwal vitamin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update jadwal imunisasi status directly
     */
    public function updateImunisasiStatus(Request $request, $id)
    {
        try {
            $jadwal = JadwalImunisasi::with('jenisImunisasi')->findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'is_implemented' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update status jadwal secara langsung
            $jadwal->is_implemented = $request->is_implemented;
            $jadwal->save();
            
            return response()->json([
                'success' => true,
                'is_implemented' => $jadwal->is_implemented,
                'message' => $jadwal->is_implemented 
                    ? "Jadwal imunisasi {$jadwal->jenisImunisasi->nama} berhasil ditandai sebagai sudah dilaksanakan."
                    : "Jadwal imunisasi {$jadwal->jenisImunisasi->nama} berhasil ditandai sebagai belum dilaksanakan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status jadwal imunisasi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update jadwal vitamin status directly
     */
    public function updateVitaminStatus(Request $request, $id)
    {
        try {
            $jadwal = JadwalVitamin::with('jenisVitamin')->findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'is_implemented' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update status jadwal secara langsung
            $jadwal->is_implemented = $request->is_implemented;
            $jadwal->save();
            
            return response()->json([
                'success' => true,
                'is_implemented' => $jadwal->is_implemented,
                'message' => $jadwal->is_implemented 
                    ? "Jadwal vitamin {$jadwal->jenisVitamin->nama} berhasil ditandai sebagai sudah dilaksanakan."
                    : "Jadwal vitamin {$jadwal->jenisVitamin->nama} berhasil ditandai sebagai belum dilaksanakan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status jadwal vitamin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update jadwal pemeriksaan status directly
     */
    public function updatePemeriksaanStatus(Request $request, $id)
    {
        try {
            $jadwal = JadwalPemeriksaan::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'is_implemented' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update status jadwal secara langsung
            $jadwal->is_implemented = $request->is_implemented;
            $jadwal->save();
            
            return response()->json([
                'success' => true,
                'is_implemented' => $jadwal->is_implemented,
                'message' => $jadwal->is_implemented 
                    ? "Jadwal pemeriksaan '{$jadwal->judul}' berhasil ditandai sebagai sudah dilaksanakan."
                    : "Jadwal pemeriksaan '{$jadwal->judul}' berhasil ditandai sebagai belum dilaksanakan."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status jadwal pemeriksaan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming schedules filtered by child's age
     */
    public function upcomingForChild($anakId)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        try {
            // Get child data to calculate age
            $anak = Anak::findOrFail($anakId);
            $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
            $usiaBulan = Carbon::now()->diffInMonths($tanggalLahir);
            $usiaHari = Carbon::now()->diffInDays($tanggalLahir);
            
            // Log child age for debugging
            \Log::info("Anak ID: $anakId, Nama: {$anak->nama_anak}, Tanggal Lahir: {$anak->tanggal_lahir}");
            \Log::info("Usia: $usiaBulan bulan ($usiaHari hari)");
            
            // Always include pemeriksaan rutin
            $pemeriksaan = JadwalPemeriksaan::select(
                    'id', 
                    'judul', 
                    \DB::raw("'pemeriksaan rutin' as jenis"), 
                    'tanggal', 
                    'waktu', 
                    'created_at'
                )
                ->where('tanggal', '>=', $today)
                ->orderBy('tanggal', 'asc')
                ->get();
                
            // Get imunisasi that are appropriate for child's age
            $imunisasi = $this->getAgeAppropriateImunisasi($usiaBulan, $today, $anakId);
                
            // Get vitamin that are appropriate for child's age
            $vitamin = $this->getAgeAppropriateVitamin($usiaBulan, $today);
            
            $jadwal = $pemeriksaan->concat($imunisasi)->concat($vitamin)
                ->sortBy('tanggal')
                ->values()
                ->all();
            
            \Log::info("Total jadwal: " . count($jadwal) . 
                " (Pemeriksaan: " . count($pemeriksaan) . 
                ", Imunisasi: " . count($imunisasi) . 
                ", Vitamin: " . count($vitamin) . ")");
                
            return response()->json([
                'status' => 'success',
                'data' => $jadwal,
                'child_info' => [
                    'id' => $anakId,
                    'nama' => $anak->nama_anak,
                    'tanggal_lahir' => $anak->tanggal_lahir,
                    'age_months' => $usiaBulan,
                    'age_days' => $usiaHari
                ],
                'filter_info' => [
                    'filter_applied' => true,
                    'records_found' => count($jadwal),
                    'pemeriksaan_count' => count($pemeriksaan),
                    'imunisasi_count' => count($imunisasi),
                    'vitamin_count' => count($vitamin)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error getting age-appropriate schedules: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get age-appropriate schedules: ' . $e->getMessage(),
                'debug_trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Get imunisasi schedules filtered by child's age
     */
    public function imunisasiForChild($anakId)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        try {
            // Get child data to calculate age
            $anak = Anak::findOrFail($anakId);
            $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
            $usiaBulan = Carbon::now()->diffInMonths($tanggalLahir);
            $usiaHari = Carbon::now()->diffInDays($tanggalLahir);
            
            // Log child age for debugging
            \Log::info("Anak ID: $anakId, Nama: {$anak->nama_anak}, Tanggal Lahir: {$anak->tanggal_lahir}, Usia: $usiaBulan bulan");
            
            // Get imunisasi that are appropriate for child's age
            $jadwal = $this->getAgeAppropriateImunisasi($usiaBulan, $today, $anakId);
                
            return response()->json([
                'status' => 'success',
                'data' => $jadwal,
                'child_info' => [
                    'id' => $anakId,
                    'nama' => $anak->nama_anak,
                    'tanggal_lahir' => $anak->tanggal_lahir,
                    'age_months' => $usiaBulan
                ],
                'filter_info' => [
                    'filter_applied' => true,
                    'records_found' => count($jadwal)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error getting age-appropriate imunisasi: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get age-appropriate imunisasi: ' . $e->getMessage(),
                'debug_trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Get vitamin schedules filtered by child's age
     */
    public function vitaminForChild($anakId)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        try {
            // Get child data to calculate age
            $anak = Anak::findOrFail($anakId);
            $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
            $usiaBulan = Carbon::now()->diffInMonths($tanggalLahir);
            $usiaHari = Carbon::now()->diffInDays($tanggalLahir);
            
            // Log child age for debugging
            \Log::info("Anak ID: $anakId, Nama: {$anak->nama_anak}, Tanggal Lahir: {$anak->tanggal_lahir}, Usia: $usiaBulan bulan");
            
            // Get vitamin that are appropriate for child's age
            $jadwal = $this->getAgeAppropriateVitamin($usiaBulan, $today);
                
            return response()->json([
                'status' => 'success',
                'data' => $jadwal,
                'child_info' => [
                    'id' => $anakId,
                    'nama' => $anak->nama_anak,
                    'tanggal_lahir' => $anak->tanggal_lahir,
                    'age_months' => $usiaBulan
                ],
                'filter_info' => [
                    'filter_applied' => true,
                    'records_found' => count($jadwal)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error getting age-appropriate vitamin: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get age-appropriate vitamin: ' . $e->getMessage(),
                'debug_trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Helper method to get age-appropriate imunisasi
     */
    private function getAgeAppropriateImunisasi($usiaBulan, $today, $anakId = null)
    {
        // === METODE PERBAIKAN UNTUK MENGIKUTI DATA SEBENARNYA DI DATABASE ===
        
        // Konversi usia bulan ke hari
        $usiaHari = $usiaBulan * 30; // Perkiraan kasar
        
        // Log untuk debugging
        \Log::info("Filtering imunisasi untuk anak ID: $anakId, usia $usiaBulan bulan ($usiaHari hari)");
        
        // Dapatkan data imunisasi dari database
        $jenisImunisasi = JenisImunisasi::all();
        \Log::info("Jenis imunisasi yang tersedia: " . $jenisImunisasi->pluck('nama')->implode(', '));
        
        // Identifikasi jenis imunisasi yang cocok untuk usia anak
        $sesuaiUsiaList = $jenisImunisasi->filter(function($jenis) use ($usiaHari) {
            // Periksa apakah usia anak berada dalam rentang yang ditentukan
            $minUsia = $jenis->min_umur_hari;
            $maxUsia = $jenis->max_umur_hari;
            
            return ($usiaHari >= $minUsia && $usiaHari <= $maxUsia);
        });
        
        \Log::info("Jenis imunisasi sesuai usia: " . $sesuaiUsiaList->pluck('nama')->implode(', '));
        
        // Jika tidak ada jenis yang cocok, kembalikan array kosong
        if ($sesuaiUsiaList->isEmpty()) {
            \Log::info("Tidak ada jenis imunisasi yang sesuai untuk usia $usiaBulan bulan ($usiaHari hari)");
            return collect([]);
        }
        
        // ID jenis imunisasi yang sesuai untuk usia
        $jenisIds = $sesuaiUsiaList->pluck('id')->toArray();

        // Dapatkan jadwal imunisasi yang sesuai dengan jenis dan tanggal
        $jadwal = JadwalImunisasi::select(
                'jadwal_imunisasi.id', 
                \DB::raw('NULL as anak_id'), // Jadwal imunisasi tidak memiliki anak_id
                'jenis_imunisasi.nama as judul', 
                \DB::raw("'imunisasi' as jenis"), 
                'jadwal_imunisasi.tanggal', 
                'jadwal_imunisasi.waktu', 
                'jadwal_imunisasi.created_at',
                'jenis_imunisasi.min_umur_hari',
                'jenis_imunisasi.max_umur_hari',
                'jenis_imunisasi.keterangan',
                \DB::raw('CASE 
                    WHEN jadwal_imunisasi.is_implemented = 1 THEN "Selesai"
                    ELSE "Belum Dilaksanakan"
                END as status'),
                'jadwal_imunisasi.is_implemented'
            )
            ->join('jenis_imunisasi', 'jadwal_imunisasi.jenis_imunisasi_id', '=', 'jenis_imunisasi.id')
            ->where('jadwal_imunisasi.tanggal', '>=', $today)
            ->whereIn('jadwal_imunisasi.jenis_imunisasi_id', $jenisIds);

        // Jika ada anak_id, filter berdasarkan imunisasi yang sudah diambil
        if ($anakId) {
            // Subquery untuk mendapatkan jenis imunisasi yang sudah diambil anak
            $sudahDiambil = Imunisasi::where('anak_id', $anakId)
                ->whereIn('jenis_id', $jenisIds)
                ->whereIn('status', ['Selesai Sesuai', 'Selesai Tidak Sesuai'])
                ->pluck('jenis_id');

            // Exclude jadwal yang jenisnya sudah diambil
            $jadwal->whereNotIn('jadwal_imunisasi.jenis_imunisasi_id', $sudahDiambil);

            // Log untuk debugging
            \Log::info("Imunisasi yang sudah diambil anak ID $anakId: " . $sudahDiambil->implode(', '));
        }

        // Hanya ambil jadwal yang belum dilaksanakan
        $jadwal->where('jadwal_imunisasi.is_implemented', 0);

        $jadwal = $jadwal->orderBy('jadwal_imunisasi.tanggal', 'asc')
            ->orderBy('jadwal_imunisasi.waktu', 'asc')
            ->get();
            
        \Log::info("Jadwal imunisasi yang sesuai: " . $jadwal->count());
        foreach ($jadwal as $j) {
            \Log::info(" → Jadwal: {$j->judul}, tanggal: {$j->tanggal}, waktu: {$j->waktu}, jenis_id: {$j->jenis_imunisasi_id}");
        }
        
        return $jadwal;
    }
    
    /**
     * Helper method to get age-appropriate vitamin
     */
    private function getAgeAppropriateVitamin($usiaBulan, $today, $anakId = null)
    {
        // === METODE PERBAIKAN UNTUK MENGIKUTI DATA SEBENARNYA DI DATABASE ===
        
        // Log untuk debugging
        \Log::info("Filtering vitamin untuk anak ID: $anakId, usia $usiaBulan bulan");
        
        // Dapatkan data vitamin dari database
        $jenisVitamin = JenisVitamin::all();
        \Log::info("Jenis vitamin yang tersedia: " . $jenisVitamin->pluck('nama')->implode(', '));
        
        // Identifikasi jenis vitamin yang cocok untuk usia anak
        $sesuaiUsiaList = $jenisVitamin->filter(function($jenis) use ($usiaBulan) {
            // Periksa apakah usia anak berada dalam rentang yang ditentukan
            $minUsia = $jenis->min_umur_bulan;
            $maxUsia = $jenis->max_umur_bulan;
            
            return ($usiaBulan >= $minUsia && $usiaBulan <= $maxUsia);
        });
        
        \Log::info("Jenis vitamin sesuai usia: " . $sesuaiUsiaList->pluck('nama')->implode(', '));
        
        // Jika tidak ada jenis yang cocok, kembalikan array kosong
        if ($sesuaiUsiaList->isEmpty()) {
            \Log::info("Tidak ada jenis vitamin yang sesuai untuk usia $usiaBulan bulan");
            return collect([]);
        }
        
        // ID jenis vitamin yang sesuai untuk usia
        $jenisIds = $sesuaiUsiaList->pluck('id')->toArray();

        // Dapatkan jadwal vitamin yang sesuai dengan jenis dan tanggal
        $jadwal = JadwalVitamin::select(
                'jadwal_vitamin.id', 
                \DB::raw('NULL as anak_id'), // Jadwal vitamin tidak memiliki anak_id
                'jenis_vitamin.nama as judul', 
                \DB::raw("'vitamin' as jenis"), 
                'jadwal_vitamin.tanggal', 
                'jadwal_vitamin.waktu', 
                'jadwal_vitamin.created_at',
                'jenis_vitamin.min_umur_bulan',
                'jenis_vitamin.max_umur_bulan',
                'jenis_vitamin.keterangan',
                \DB::raw('CASE 
                    WHEN jadwal_vitamin.is_implemented = 1 THEN "Selesai"
                    ELSE "Belum Dilaksanakan"
                END as status'),
                'jadwal_vitamin.is_implemented'
            )
            ->join('jenis_vitamin', 'jadwal_vitamin.jenis_vitamin_id', '=', 'jenis_vitamin.id')
            ->where('jadwal_vitamin.tanggal', '>=', $today)
            ->whereIn('jadwal_vitamin.jenis_vitamin_id', $jenisIds);

        // Jika ada anak_id, filter berdasarkan vitamin yang sudah diambil
        if ($anakId) {
            // Subquery untuk mendapatkan jenis vitamin yang sudah diambil anak
            $sudahDiambil = Vitamin::where('anak_id', $anakId)
                ->whereIn('jenis_id', $jenisIds)
                ->where('status', 'Selesai')
                ->pluck('jenis_id');

            // Exclude jadwal yang jenisnya sudah diambil
            $jadwal->whereNotIn('jadwal_vitamin.jenis_vitamin_id', $sudahDiambil);

            // Log untuk debugging
            \Log::info("Vitamin yang sudah diambil anak ID $anakId: " . $sudahDiambil->implode(', '));
        }

        // Hanya ambil jadwal yang belum dilaksanakan
        $jadwal->where('jadwal_vitamin.is_implemented', 0);

        $jadwal = $jadwal->orderBy('jadwal_vitamin.tanggal', 'asc')
            ->orderBy('jadwal_vitamin.waktu', 'asc')
            ->get();
            
        \Log::info("Jadwal vitamin yang sesuai: " . $jadwal->count());
        foreach ($jadwal as $j) {
            \Log::info(" → Jadwal: {$j->judul}, tanggal: {$j->tanggal}, waktu: {$j->waktu}, jenis_id: {$j->jenis_vitamin_id}");
        }
        
        return $jadwal;
    }

    /**
     * Get list of immunization types with age ranges
     */
    public function imunisasiAgeRanges()
    {
        // Mengambil data langsung dari database untuk memastikan informasi terbaru
        $jenisImunisasi = JenisImunisasi::select('id', 'nama', 'min_umur_hari', 'max_umur_hari', 'keterangan')
            ->get();
            
        $formattedRanges = [];
        foreach ($jenisImunisasi as $jenis) {
            // Convert days to months for display (approximate)
            $minMonths = floor($jenis->min_umur_hari / 30);
            $minDays = $jenis->min_umur_hari % 30;
            $maxMonths = floor($jenis->max_umur_hari / 30);
            $maxDays = $jenis->max_umur_hari % 30;
            
            $minAgeText = $minMonths > 0 ? "$minMonths bulan " : "";
            $minAgeText .= $minDays > 0 ? "$minDays hari" : "";
            $minAgeText = $minAgeText ?: "0 hari";
            
            $maxAgeText = $maxMonths > 0 ? "$maxMonths bulan " : "";
            $maxAgeText .= $maxDays > 0 ? "$maxDays hari" : "";
            $maxAgeText = $maxAgeText ?: "0 hari";
            
            $formattedRanges[] = [
                'id' => $jenis->id,
                'nama' => $jenis->nama,
                'usia_min_hari' => $jenis->min_umur_hari,
                'usia_max_hari' => $jenis->max_umur_hari,
                'usia_min_text' => $minAgeText,
                'usia_max_text' => $maxAgeText,
                'deskripsi' => "Untuk anak usia {$minAgeText} sampai {$maxAgeText}",
                'keterangan' => $jenis->keterangan
            ];
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $formattedRanges
        ]);
    }
    
    /**
     * Get list of vitamin types with age ranges
     */
    public function vitaminAgeRanges()
    {
        // Mengambil data langsung dari database untuk memastikan informasi terbaru
        $jenisVitamin = JenisVitamin::select('id', 'nama', 'min_umur_bulan', 'max_umur_bulan', 'keterangan')
            ->get();
            
        $formattedRanges = [];
        foreach ($jenisVitamin as $jenis) {
            $formattedRanges[] = [
                'id' => $jenis->id,
                'nama' => $jenis->nama,
                'usia_min_bulan' => $jenis->min_umur_bulan,
                'usia_max_bulan' => $jenis->max_umur_bulan,
                'deskripsi' => "Untuk anak usia {$jenis->min_umur_bulan}-{$jenis->max_umur_bulan} bulan",
                'keterangan' => $jenis->keterangan
            ];
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $formattedRanges
        ]);
    }

    /**
     * Get riwayat jadwal anak (imunisasi dan vitamin yang sudah diikuti)
     */
    public function riwayatAnak($anakId)
    {
        // Riwayat imunisasi anak
        $imunisasi = Imunisasi::select(
                'imunisasi.id',
                'imunisasi.anak_id',
                'jenis_imunisasi.nama',
                'jenis_imunisasi.min_umur_hari',
                'jenis_imunisasi.max_umur_hari',
                'jenis_imunisasi.keterangan',
                \DB::raw("'imunisasi' as jenis"),
                'imunisasi.tanggal',
                'jadwal_imunisasi.waktu',
                \DB::raw('CASE 
                    WHEN jadwal_imunisasi.is_implemented = 1 THEN "Selesai"
                    ELSE "Belum Dilaksanakan"
                END as status'),
                'imunisasi.created_at',
                'imunisasi.updated_at'
            )
            ->join('jenis_imunisasi', 'imunisasi.jenis_id', '=', 'jenis_imunisasi.id')
            ->join('jadwal_imunisasi', function($join) {
                $join->on('imunisasi.tanggal', '=', 'jadwal_imunisasi.tanggal')
                    ->on('imunisasi.jenis_id', '=', 'jadwal_imunisasi.jenis_imunisasi_id');
            })
            ->where('imunisasi.anak_id', $anakId)
            ->orderBy('imunisasi.tanggal', 'desc')
            ->get();

        // Riwayat vitamin anak
        $vitamin = Vitamin::select(
                'vitamin.id',
                'vitamin.anak_id',
                'jenis_vitamin.nama',
                'jenis_vitamin.min_umur_bulan',
                'jenis_vitamin.max_umur_bulan',
                'jenis_vitamin.keterangan',
                \DB::raw("'vitamin' as jenis"),
                'vitamin.tanggal',
                'jadwal_vitamin.waktu',
                \DB::raw('CASE 
                    WHEN jadwal_vitamin.is_implemented = 1 THEN "Selesai"
                    ELSE "Belum Dilaksanakan"
                END as status'),
                'vitamin.created_at',
                'vitamin.updated_at'
            )
            ->join('jenis_vitamin', 'vitamin.jenis_id', '=', 'jenis_vitamin.id')
            ->join('jadwal_vitamin', function($join) {
                $join->on('vitamin.tanggal', '=', 'jadwal_vitamin.tanggal')
                    ->on('vitamin.jenis_id', '=', 'jadwal_vitamin.jenis_vitamin_id');
            })
            ->where('vitamin.anak_id', $anakId)
            ->orderBy('vitamin.tanggal', 'desc')
            ->get();

        // Jadwal pemeriksaan rutin untuk semua anak (tidak filter anak_id)
        $pemeriksaan = JadwalPemeriksaan::select(
                'jadwal_pemeriksaan.id',
                \DB::raw('NULL as anak_id'),
                'jadwal_pemeriksaan.judul as nama',
                \DB::raw('NULL as min_umur_hari'),
                \DB::raw('NULL as max_umur_hari'),
                \DB::raw('NULL as keterangan'),
                \DB::raw("'pemeriksaan rutin' as jenis"),
                'jadwal_pemeriksaan.tanggal',
                'jadwal_pemeriksaan.waktu',
                \DB::raw('CASE 
                    WHEN jadwal_pemeriksaan.is_implemented = 1 THEN "Selesai"
                    ELSE "Belum Dilaksanakan"
                END as status'),
                'jadwal_pemeriksaan.created_at',
                'jadwal_pemeriksaan.updated_at'
            )
            ->orderBy('jadwal_pemeriksaan.tanggal', 'desc')
            ->get();

        // Gabungkan dan urutkan
        $riwayat = collect($imunisasi)
            ->concat($vitamin)
            ->concat($pemeriksaan)
            ->sortByDesc('tanggal')
            ->values()
            ->all();

        return response()->json([
            'status' => 'success',
            'data' => $riwayat
        ]);
    }

    /**
     * Get nearest schedule for a child
     */
    public function nearestSchedule($anakId)
    {
        $today = Carbon::now(); // Gunakan waktu sekarang untuk perbandingan yang lebih akurat
        
        try {
            // Get child data to calculate age
            $anak = Anak::findOrFail($anakId);
            $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
            $usiaBulan = Carbon::now()->diffInMonths($tanggalLahir);
            $usiaHari = Carbon::now()->diffInDays($tanggalLahir);
            
            // Log informasi anak untuk debugging
            \Log::info("Mencari jadwal terdekat untuk anak:", [
                'id' => $anakId,
                'nama' => $anak->nama_anak,
                'tanggal_lahir' => $anak->tanggal_lahir,
                'usia_bulan' => $usiaBulan,
                'usia_hari' => $usiaHari
            ]);
            
            // Get nearest pemeriksaan rutin (hanya yang belum dilaksanakan)
            $pemeriksaan = JadwalPemeriksaan::select(
                    'id', 
                    \DB::raw('NULL as anak_id'),
                    'judul as nama', 
                    \DB::raw("'pemeriksaan rutin' as jenis"), 
                    'tanggal', 
                    'waktu', 
                    'created_at',
                    \DB::raw('NULL as min_umur_hari'),
                    \DB::raw('NULL as max_umur_hari'),
                    \DB::raw('NULL as min_umur_bulan'),
                    \DB::raw('NULL as max_umur_bulan'),
                    \DB::raw('NULL as keterangan'),
                    \DB::raw('CASE 
                        WHEN is_implemented = 1 THEN "Selesai"
                        ELSE "Belum Dilaksanakan"
                    END as status'),
                    'is_implemented'
                )
                ->where('tanggal', '>=', $today->format('Y-m-d'))
                ->where('is_implemented', 0) // Hanya ambil yang belum dilaksanakan
                ->orderBy('tanggal', 'asc')
                ->orderBy('waktu', 'asc')
                ->first();
                
            // Get nearest imunisasi yang sesuai usia dan belum diambil
            $imunisasi = JadwalImunisasi::select(
                    'jadwal_imunisasi.id', 
                    \DB::raw('NULL as anak_id'),
                    'jenis_imunisasi.nama as judul', 
                    \DB::raw("'imunisasi' as jenis"), 
                    'jadwal_imunisasi.tanggal', 
                    'jadwal_imunisasi.waktu', 
                    'jadwal_imunisasi.created_at',
                    'jenis_imunisasi.min_umur_hari',
                    'jenis_imunisasi.max_umur_hari',
                    \DB::raw('NULL as min_umur_bulan'),
                    \DB::raw('NULL as max_umur_bulan'),
                    'jenis_imunisasi.keterangan',
                    \DB::raw('CASE 
                        WHEN jadwal_imunisasi.is_implemented = 1 THEN "Selesai"
                        ELSE "Belum Dilaksanakan"
                    END as status'),
                    'jadwal_imunisasi.is_implemented'
                )
                ->join('jenis_imunisasi', 'jadwal_imunisasi.jenis_imunisasi_id', '=', 'jenis_imunisasi.id')
                ->where('jadwal_imunisasi.tanggal', '>=', $today->format('Y-m-d'))
                ->where('jadwal_imunisasi.is_implemented', 0) // Hanya ambil yang belum dilaksanakan
                ->where(function($query) use ($usiaHari) {
                    $query->where('jenis_imunisasi.min_umur_hari', '<=', $usiaHari)
                          ->where('jenis_imunisasi.max_umur_hari', '>=', $usiaHari);
                })
                ->whereNotExists(function($query) use ($anakId) {
                    $query->select(\DB::raw(1))
                          ->from('imunisasi')
                          ->whereRaw('imunisasi.jenis_id = jadwal_imunisasi.jenis_imunisasi_id')
                          ->where('imunisasi.anak_id', $anakId)
                          ->whereIn('imunisasi.status', ['Selesai Sesuai', 'Selesai Tidak Sesuai']);
                })
                ->orderBy('jadwal_imunisasi.tanggal', 'asc')
                ->orderBy('jadwal_imunisasi.waktu', 'asc')
                ->first();
                
            // Get nearest vitamin yang sesuai usia dan belum diambil
            $vitamin = JadwalVitamin::select(
                    'jadwal_vitamin.id', 
                    \DB::raw('NULL as anak_id'),
                    'jenis_vitamin.nama as judul', 
                    \DB::raw("'vitamin' as jenis"), 
                    'jadwal_vitamin.tanggal', 
                    'jadwal_vitamin.waktu', 
                    'jadwal_vitamin.created_at',
                    \DB::raw('NULL as min_umur_hari'),
                    \DB::raw('NULL as max_umur_hari'),
                    'jenis_vitamin.min_umur_bulan',
                    'jenis_vitamin.max_umur_bulan',
                    'jenis_vitamin.keterangan',
                    \DB::raw('CASE 
                        WHEN jadwal_vitamin.is_implemented = 1 THEN "Selesai"
                        ELSE "Belum Dilaksanakan"
                    END as status'),
                    'jadwal_vitamin.is_implemented'
                )
                ->join('jenis_vitamin', 'jadwal_vitamin.jenis_vitamin_id', '=', 'jenis_vitamin.id')
                ->where('jadwal_vitamin.tanggal', '>=', $today->format('Y-m-d'))
                ->where('jadwal_vitamin.is_implemented', 0) // Hanya ambil yang belum dilaksanakan
                ->where(function($query) use ($usiaBulan) {
                    $query->where('jenis_vitamin.min_umur_bulan', '<=', $usiaBulan)
                          ->where('jenis_vitamin.max_umur_bulan', '>=', $usiaBulan);
                })
                ->whereNotExists(function($query) use ($anakId) {
                    $query->select(\DB::raw(1))
                          ->from('vitamin')
                          ->whereRaw('vitamin.jenis_id = jadwal_vitamin.jenis_vitamin_id')
                          ->where('vitamin.anak_id', $anakId)
                          ->where('vitamin.status', 'Selesai');
                })
                ->orderBy('jadwal_vitamin.tanggal', 'asc')
                ->orderBy('jadwal_vitamin.waktu', 'asc')
                ->first();
            
            // Debug log untuk melihat jadwal yang ditemukan
            \Log::info("Jadwal yang ditemukan:", [
                'pemeriksaan' => $pemeriksaan ? [
                    'id' => $pemeriksaan->id,
                    'nama' => $pemeriksaan->nama,
                    'tanggal' => $pemeriksaan->tanggal,
                    'waktu' => $pemeriksaan->waktu,
                    'status' => $pemeriksaan->status
                ] : null,
                'imunisasi' => $imunisasi ? [
                    'id' => $imunisasi->id,
                    'nama' => $imunisasi->judul,
                    'tanggal' => $imunisasi->tanggal,
                    'waktu' => $imunisasi->waktu,
                    'status' => $imunisasi->status,
                    'min_umur_hari' => $imunisasi->min_umur_hari,
                    'max_umur_hari' => $imunisasi->max_umur_hari
                ] : null,
                'vitamin' => $vitamin ? [
                    'id' => $vitamin->id,
                    'nama' => $vitamin->judul,
                    'tanggal' => $vitamin->tanggal,
                    'waktu' => $vitamin->waktu,
                    'status' => $vitamin->status,
                    'min_umur_bulan' => $vitamin->min_umur_bulan,
                    'max_umur_bulan' => $vitamin->max_umur_bulan
                ] : null
            ]);
            
            // Combine and find the nearest one
            $schedules = collect([$pemeriksaan, $imunisasi, $vitamin])
                ->filter()
                ->map(function($schedule) use ($today) {
                    // Convert tanggal and waktu to Carbon instance
                    $scheduleDate = Carbon::parse($schedule->tanggal);
                    if ($schedule->waktu) {
                        $time = Carbon::parse($schedule->waktu);
                        $scheduleDate->setTime($time->hour, $time->minute, $time->second);
                    }
                    
                    // Hitung selisih waktu dengan sekarang
                    $diffInSeconds = $scheduleDate->diffInSeconds($today);
                    
                    return [
                        'schedule' => $schedule,
                        'datetime' => $scheduleDate,
                        'diff_in_seconds' => $diffInSeconds
                    ];
                })
                ->sortBy('diff_in_seconds'); // Urutkan berdasarkan selisih waktu terdekat
            
            $nearestSchedule = $schedules->first();
            
            if (!$nearestSchedule) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tidak ada jadwal terdekat',
                    'data' => null
                ]);
            }
            
            // Convert to array and ensure all fields match the Flutter model
            $nearestSchedule = $nearestSchedule['schedule']->toArray();
            
            // Ensure all required fields are present
            $nearestSchedule = array_merge([
                'id' => 0,
                'anak_id' => null,
                'nama' => '',
                'jenis' => '',
                'tanggal' => now()->toIso8601String(),
                'waktu' => null,
                'status' => null,
                'keterangan' => null,
                'min_umur_hari' => null,
                'max_umur_hari' => null,
                'min_umur_bulan' => null,
                'max_umur_bulan' => null,
                'is_implemented' => null,
                'created_at' => now()->toIso8601String(),
                'updated_at' => now()->toIso8601String(),
            ], $nearestSchedule);
            
            // Log jadwal terdekat yang dipilih
            \Log::info("Jadwal terdekat yang dipilih:", [
                'jenis' => $nearestSchedule['jenis'],
                'nama' => $nearestSchedule['nama'],
                'tanggal' => $nearestSchedule['tanggal'],
                'waktu' => $nearestSchedule['waktu'],
                'status' => $nearestSchedule['status'],
                'selisih_waktu' => Carbon::parse($nearestSchedule['tanggal'] . ' ' . $nearestSchedule['waktu'])
                    ->diffForHumans($today)
            ]);
            
            return response()->json([
                'status' => 'success',
                'data' => $nearestSchedule,
                'child_info' => [
                    'id' => $anakId,
                    'nama' => $anak->nama_anak,
                    'tanggal_lahir' => $anak->tanggal_lahir,
                    'age_months' => $usiaBulan,
                    'age_days' => $usiaHari
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error("Error getting nearest schedule: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get nearest schedule: ' . $e->getMessage()
            ], 500);
        }
    }
}
