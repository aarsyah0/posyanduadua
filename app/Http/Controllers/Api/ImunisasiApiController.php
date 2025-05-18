<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Imunisasi;
use App\Models\JadwalImunisasi;
use App\Models\Anak;
use App\Models\JenisImunisasi;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ImunisasiApiController extends Controller
{
    /**
     * Get all imunisasi records with filtering options
     */
    public function index(Request $request)
    {
        $query = Imunisasi::with(['anak', 'jenisImunisasi']);

        // Filter by anak_id if provided
        if ($request->has('anak_id')) {
            $query->where('anak_id', $request->anak_id);
        }

        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Get paginated results or all if limit=0
        if ($request->has('limit') && $request->limit > 0) {
            $imunisasi = $query->orderBy('tanggal', 'desc')->paginate($request->limit);
        } else {
            $imunisasi = $query->orderBy('tanggal', 'desc')->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $imunisasi
        ]);
    }

    /**
     * Get an imunisasi record by ID
     */
    public function show($id)
    {
        $imunisasi = Imunisasi::with(['anak', 'jenisImunisasi'])->find($id);

        if (!$imunisasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data imunisasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $imunisasi
        ]);
    }

    /**
     * Update imunisasi status and date
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'sometimes|date',
            'status' => 'sometimes|in:' . implode(',', Imunisasi::$statusList),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $imunisasi = Imunisasi::find($id);

        if (!$imunisasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data imunisasi tidak ditemukan'
            ], 404);
        }

        // Keep track of old status for comparison
        $oldStatus = $imunisasi->status;

        // Only update tanggal and status fields
        $updateData = [];
        if ($request->has('tanggal')) {
            $updateData['tanggal'] = $request->tanggal;
        }
        if ($request->has('status')) {
            $updateData['status'] = $request->status;
        }

        $imunisasi->update($updateData);

        // If status changed and jadwal_imunisasi_id is set, update jadwal status
        if ($request->has('status') && $oldStatus !== $request->status && $imunisasi->jadwal_imunisasi_id) {
            $this->updateJadwalStatus($imunisasi->jadwal_imunisasi_id);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data imunisasi berhasil diperbarui',
            'data' => $imunisasi->fresh()->load(['anak', 'jenisImunisasi'])
        ]);
    }

    /**
     * Get imunisasi data for a specific child
     */
    public function getByAnakId($anakId)
    {
        $anak = Anak::find($anakId);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan'
            ], 404);
        }

        $imunisasi = Imunisasi::with('jenisImunisasi')
            ->where('anak_id', $anakId)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $imunisasi
        ]);
    }

    /**
     * Get scheduled imunisasi and check implementation status
     */
    public function getJadwalWithStatus(Request $request)
    {
        // Get all scheduled imunisasi
        $query = JadwalImunisasi::with('jenisImunisasi')
            ->orderBy('tanggal', 'desc');
            
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $jadwalImunisasi = $query->get();
        
        // Prepare response with implementation status
        $result = [];
        foreach ($jadwalImunisasi as $jadwal) {
            // For each jadwal, check if there are matching imunisasi records
            $implementedCount = Imunisasi::where('jenis_id', $jadwal->jenis_imunisasi_id)
                ->where('tanggal', $jadwal->tanggal)
                ->count();
                
            // Add status information
            $item = [
                'jadwal' => $jadwal,
                'jenis_imunisasi' => $jadwal->jenisImunisasi,
                'implementation_count' => $implementedCount,
                'is_implemented' => $implementedCount > 0
            ];
            
            $result[] = $item;
        }

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    /**
     * Get jadwal imunisasi for a specific child based on their age
     */
    public function getJadwalForAnak($anakId)
    {
        $anak = Anak::find($anakId);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan'
            ], 404);
        }
        
        // Calculate child's age in days
        $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
        $umurHari = $tanggalLahir->diffInDays(Carbon::now());
        
        // Get upcoming scheduled imunisasi
        $jadwalImunisasi = JadwalImunisasi::with('jenisImunisasi')
            ->whereHas('jenisImunisasi', function($query) use ($umurHari) {
                // Filter jadwal where child's age is within min and max age range
                $query->where('min_umur_hari', '<=', $umurHari)
                      ->where('max_umur_hari', '>=', $umurHari);
            })
            ->where('tanggal', '>=', Carbon::today()->format('Y-m-d'))
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Check if each jadwal is already implemented for this child
        $result = [];
        foreach ($jadwalImunisasi as $jadwal) {
            // Check if this imunisasi is already scheduled/implemented for the child
            $existingImunisasi = Imunisasi::where('anak_id', $anakId)
                ->where('jenis_id', $jadwal->jenis_imunisasi_id)
                ->first();
            
            $result[] = [
                'jadwal' => $jadwal,
                'jenis_imunisasi' => $jadwal->jenisImunisasi,
                'child_age_days' => $umurHari,
                'is_eligible' => true,
                'is_implemented' => $existingImunisasi ? true : false,
                'imunisasi' => $existingImunisasi
            ];
        }

        return response()->json([
            'status' => 'success',
            'anak' => [
                'id' => $anak->id,
                'nama' => $anak->nama_anak,
                'tanggal_lahir' => $anak->tanggal_lahir,
                'umur_hari' => $umurHari
            ],
            'data' => $result
        ]);
    }

    /**
     * Check implementation status of a schedule
     */
    public function checkImplementationStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_id' => 'required|exists:jadwal_imunisasi,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwalId = $request->jadwal_id;
        $jadwal = JadwalImunisasi::with('jenisImunisasi')->findOrFail($jadwalId);
        
        // Make sure jadwal implementation status is up-to-date
        $this->updateJadwalStatus($jadwalId);
        $jadwal->refresh();
        
        // Count implementation even if jadwal_imunisasi_id is not set
        $implementationCount = Imunisasi::where('jenis_id', $jadwal->jenis_imunisasi_id)
                                      ->where('tanggal', $jadwal->tanggal)
                                      ->count();
        
        // Count completed implementations
        $completedCount = Imunisasi::where('jenis_id', $jadwal->jenis_imunisasi_id)
                                 ->where('tanggal', $jadwal->tanggal)
                                 ->whereIn('status', [Imunisasi::STATUS_SELESAI_SESUAI, Imunisasi::STATUS_SELESAI_TIDAK_SESUAI])
                                 ->count();
        
        $response = [
            'success' => true,
            'data' => [
                [
                    'id' => $jadwal->id,
                    'jenis_imunisasi' => $jadwal->jenisImunisasi,
                    'is_implemented' => $jadwal->is_implemented,
                    'implementation_count' => $implementationCount,
                    'completed_count' => $completedCount
                ]
            ]
        ];
        
        return response()->json($response);
    }

    /**
     * Confirm implementation of a schedule
     */
    public function confirmImplementation($id)
    {
        $jadwal = JadwalImunisasi::findOrFail($id);
        
        // Update jadwal implementation status based on completed imunisasi records
        $this->updateJadwalStatus($jadwal->id);
        
        return response()->json([
            'success' => true,
            'message' => 'Jadwal imunisasi berhasil diperiksa status implementasinya.'
        ]);
    }

    /**
     * Get eligible children for a schedule
     */
    public function getEligibleChildren($jadwalId)
    {
        $jadwal = JadwalImunisasi::with('jenisImunisasi')->findOrFail($jadwalId);
        $jenisImunisasi = $jadwal->jenisImunisasi;
        
        // Get all children
        $allChildren = Anak::all();
        $eligibleChildren = [];
        
        foreach ($allChildren as $anak) {
            // Calculate age in days
            $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
            $umurHari = $tanggalLahir->diffInDays(Carbon::now());
            
            // Check if already registered
            $isRegistered = Imunisasi::where('anak_id', $anak->id)
                                     ->where('jadwal_imunisasi_id', $jadwalId)
                                     ->exists();
            
            $isImplemented = Imunisasi::where('anak_id', $anak->id)
                                     ->where('jadwal_imunisasi_id', $jadwalId)
                                     ->where('status', 'Sudah')
                                     ->exists();
            
            // Check age eligibility - use min_umur_hari and max_umur_hari instead of min_usia_hari and max_usia_hari
            if ($umurHari >= $jenisImunisasi->min_umur_hari && $umurHari <= $jenisImunisasi->max_umur_hari) {
                $eligibleChildren[] = [
                    'anak' => $anak,
                    'is_registered' => $isRegistered,
                    'is_implemented' => $isImplemented,
                    'child_age_days' => $umurHari
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $eligibleChildren
        ]);
    }

    /**
     * Create imunisasi records from a jadwal
     */
    public function createFromJadwal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_imunisasi_id' => 'required|exists:jadwal_imunisasi,id',
            'anak_id' => 'required|exists:anak,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the jadwal
        $jadwal = JadwalImunisasi::findOrFail($request->jadwal_imunisasi_id);
        
        // Get anak and verify age eligibility
        $anak = Anak::findOrFail($request->anak_id);
        $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
        $umurHari = $tanggalLahir->diffInDays(Carbon::now());
        
        // Get jenis imunisasi
        $jenisImunisasi = JenisImunisasi::findOrFail($jadwal->jenis_imunisasi_id);
        
        // Check if child's age is within the eligible range
        if ($umurHari < $jenisImunisasi->min_umur_hari || $umurHari > $jenisImunisasi->max_umur_hari) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anak tidak memenuhi syarat umur untuk imunisasi ini',
                'data' => [
                    'umur_anak_hari' => $umurHari,
                    'min_umur_hari' => $jenisImunisasi->min_umur_hari,
                    'max_umur_hari' => $jenisImunisasi->max_umur_hari
                ]
            ], 422);
        }

        // Check if imunisasi already exists for this child and jadwal
        $existingImunisasi = Imunisasi::where('anak_id', $request->anak_id)
            ->where('jenis_id', $jadwal->jenis_imunisasi_id)
            ->first();

        if ($existingImunisasi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data imunisasi untuk anak dan jadwal ini sudah ada',
                'data' => $existingImunisasi
            ], 422);
        }

        // Create new imunisasi record with jadwal_imunisasi_id
        $imunisasi = Imunisasi::create([
            'anak_id' => $request->anak_id,
            'jenis_id' => $jadwal->jenis_imunisasi_id,
            'jadwal_imunisasi_id' => $jadwal->id,
            'tanggal' => $jadwal->tanggal,
            'status' => Imunisasi::STATUS_BELUM
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data imunisasi berhasil dibuat dari jadwal',
            'data' => $imunisasi->load(['anak', 'jenisImunisasi'])
        ], 201);
    }

    /**
     * Mark a jadwal as complete and register all eligible children
     */
    public function completeJadwal($id)
    {
        try {
            $jadwal = JadwalImunisasi::with('jenisImunisasi')->findOrFail($id);
            
            // Get all children
            $allChildren = Anak::all();
            $eligibleChildren = [];
            $debug_info = [];
            
            foreach ($allChildren as $anak) {
                // Calculate age in days
                $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
                $umurHari = $tanggalLahir->diffInDays(Carbon::now());
                
                // Log info for debugging
                $debug_info[] = [
                    'anak_id' => $anak->id,
                    'nama' => $anak->nama_anak,
                    'tanggal_lahir' => $anak->tanggal_lahir,
                    'umur_hari' => $umurHari,
                    'min_umur_hari' => $jadwal->jenisImunisasi->min_umur_hari,
                    'max_umur_hari' => $jadwal->jenisImunisasi->max_umur_hari,
                    'eligible' => ($umurHari >= $jadwal->jenisImunisasi->min_umur_hari && $umurHari <= $jadwal->jenisImunisasi->max_umur_hari)
                ];
                
                // Check age eligibility
                if ($umurHari >= $jadwal->jenisImunisasi->min_umur_hari && $umurHari <= $jadwal->jenisImunisasi->max_umur_hari) {
                    $eligibleChildren[] = $anak;
                }
            }
            
            // Register each eligible child for this imunisasi if not already registered
            $registered = 0;
            foreach ($eligibleChildren as $anak) {
                // Check if already registered
                $existing = Imunisasi::where('anak_id', $anak->id)
                                     ->where('jenis_id', $jadwal->jenis_imunisasi_id)
                                     ->first();
                
                if (!$existing) {
                    // Create new imunisasi record, status awal Belum
                    Imunisasi::create([
                        'anak_id' => $anak->id,
                        'jenis_id' => $jadwal->jenis_imunisasi_id,
                        'jadwal_imunisasi_id' => $jadwal->id,
                        'tanggal' => $jadwal->tanggal,
                        'status' => Imunisasi::STATUS_BELUM
                    ]);
                    $registered++;
                }
            }
            
            // Status jadwal independent dari status imunisasi
            // Tombol "Selesai" di jadwal.blade.php akan mengubah status jadwal secara langsung
            
            // Jika tidak ada anak yang eligible, tampilkan pesan yang jelas
            $message = $registered > 0 
                ? "Jadwal imunisasi berhasil diselesaikan dan $registered anak ditambahkan ke daftar imunisasi."
                : "Jadwal imunisasi berhasil diselesaikan tetapi tidak ada anak yang memenuhi syarat umur (min: {$jadwal->jenisImunisasi->min_umur_hari} hari, max: {$jadwal->jenisImunisasi->max_umur_hari} hari).";
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'jadwal' => $jadwal->fresh(),
                    'eligible_children_count' => count($eligibleChildren),
                    'registered_children' => $registered,
                    'debug_info' => $debug_info
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai jadwal sebagai selesai: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Update implementation status of jadwal - now independent of imunisasi status
     */
    private function updateJadwalStatus($jadwalId)
    {
        // Status jadwal sekarang independent dari status imunisasi
        // Akan diubah langsung menggunakan endpoint khusus
        return JadwalImunisasi::findOrFail($jadwalId);
    }
}
