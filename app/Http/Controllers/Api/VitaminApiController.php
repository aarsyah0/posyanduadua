<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vitamin;
use App\Models\JadwalVitamin;
use App\Models\Anak;
use App\Models\JenisVitamin;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VitaminApiController extends Controller
{
    /**
     * Get all vitamin records with filtering options
     */
    public function index(Request $request)
    {
        $query = Vitamin::with(['anak', 'jenisVitamin']);

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
            $vitamin = $query->orderBy('tanggal', 'desc')->paginate($request->limit);
        } else {
            $vitamin = $query->orderBy('tanggal', 'desc')->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $vitamin
        ]);
    }

    /**
     * Get a vitamin record by ID
     */
    public function show($id)
    {
        $vitamin = Vitamin::with(['anak', 'jenisVitamin'])->find($id);

        if (!$vitamin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data vitamin tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $vitamin
        ]);
    }

    /**
     * Update vitamin status and date
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'sometimes|date',
            'status' => 'sometimes|in:' . implode(',', Vitamin::$statusList),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $vitamin = Vitamin::find($id);

        if (!$vitamin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data vitamin tidak ditemukan'
            ], 404);
        }

        // Keep track of old status for comparison
        $oldStatus = $vitamin->status;

        // Only update tanggal and status fields
        $updateData = [];
        if ($request->has('tanggal')) {
            $updateData['tanggal'] = $request->tanggal;
        }
        if ($request->has('status')) {
            $updateData['status'] = $request->status;
        }

        $vitamin->update($updateData);

        // If status changed and jadwal_vitamin_id is set, update jadwal status
        if ($request->has('status') && $oldStatus !== $request->status && $vitamin->jadwal_vitamin_id) {
            $this->updateJadwalStatus($vitamin->jadwal_vitamin_id);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data vitamin berhasil diperbarui',
            'data' => $vitamin->fresh()->load(['anak', 'jenisVitamin'])
        ]);
    }

    /**
     * Get vitamin data for a specific child
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

        $vitamin = Vitamin::with('jenisVitamin')
            ->where('anak_id', $anakId)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $vitamin
        ]);
    }

    /**
     * Get scheduled vitamin and check implementation status
     */
    public function getJadwalWithStatus(Request $request)
    {
        // Get all scheduled vitamin
        $query = JadwalVitamin::with('jenisVitamin')
            ->orderBy('tanggal', 'desc');
            
        // Filter by date range if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $jadwalVitamin = $query->get();
        
        // Prepare response with implementation status
        $result = [];
        foreach ($jadwalVitamin as $jadwal) {
            // For each jadwal, check if there are matching vitamin records
            $implementedCount = Vitamin::where('jenis_id', $jadwal->jenis_vitamin_id)
                ->where('tanggal', $jadwal->tanggal)
                ->count();
                
            // Add status information
            $item = [
                'jadwal' => $jadwal,
                'jenis_vitamin' => $jadwal->jenisVitamin,
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
     * Get jadwal vitamin for a specific child based on their age
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
        
        // Get upcoming scheduled vitamin
        $jadwalVitamin = JadwalVitamin::with('jenisVitamin')
            ->whereHas('jenisVitamin', function($query) use ($umurHari) {
                // Filter jadwal where child's age is within min and max age range
                $query->where('min_umur_hari', '<=', $umurHari)
                      ->where('max_umur_hari', '>=', $umurHari);
            })
            ->where('tanggal', '>=', Carbon::today()->format('Y-m-d'))
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Check if each jadwal is already implemented for this child
        $result = [];
        foreach ($jadwalVitamin as $jadwal) {
            // Check if this vitamin is already scheduled/implemented for the child
            $existingVitamin = Vitamin::where('anak_id', $anakId)
                ->where('jenis_id', $jadwal->jenis_vitamin_id)
                ->first();
            
            $result[] = [
                'jadwal' => $jadwal,
                'jenis_vitamin' => $jadwal->jenisVitamin,
                'child_age_days' => $umurHari,
                'is_eligible' => true,
                'is_implemented' => $existingVitamin ? true : false,
                'vitamin' => $existingVitamin
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
            'jadwal_id' => 'required|exists:jadwal_vitamin,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $jadwalId = $request->jadwal_id;
        $jadwal = JadwalVitamin::with('jenisVitamin')->findOrFail($jadwalId);
        
        // Make sure jadwal implementation status is up-to-date
        $this->updateJadwalStatus($jadwalId);
        $jadwal->refresh();
        
        // Count implementation even if jadwal_vitamin_id is not set
        $implementationCount = Vitamin::where('jenis_id', $jadwal->jenis_vitamin_id)
                                    ->where('tanggal', $jadwal->tanggal)
                                    ->count();
        
        // Count completed implementations
        $completedCount = Vitamin::where('jenis_id', $jadwal->jenis_vitamin_id)
                               ->where('tanggal', $jadwal->tanggal)
                               ->where('status', Vitamin::STATUS_SELESAI)
                               ->count();
        
        $response = [
            'success' => true,
            'data' => [
                [
                    'id' => $jadwal->id,
                    'jenis_vitamin' => $jadwal->jenisVitamin,
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
        $jadwal = JadwalVitamin::findOrFail($id);
        
        // Update jadwal implementation status based on completed vitamin records
        $this->updateJadwalStatus($jadwal->id);
        
        return response()->json([
            'success' => true,
            'message' => 'Jadwal vitamin berhasil diperiksa status implementasinya.'
        ]);
    }

    /**
     * Get eligible children for a schedule
     */
    public function getEligibleChildren($jadwalId)
    {
        $jadwal = JadwalVitamin::with('jenisVitamin')->findOrFail($jadwalId);
        $jenisVitamin = $jadwal->jenisVitamin;
        
        // Get all children
        $allChildren = Anak::all();
        $eligibleChildren = [];
        
        foreach ($allChildren as $anak) {
            // Calculate age in months
            $birthDate = Carbon::parse($anak->tanggal_lahir);
            $ageInMonths = $birthDate->diffInMonths(Carbon::now());
            
            // Check if already registered
            $isRegistered = Vitamin::where('anak_id', $anak->id)
                                  ->where('jadwal_vitamin_id', $jadwalId)
                                  ->exists();
            
            $isImplemented = Vitamin::where('anak_id', $anak->id)
                                  ->where('jadwal_vitamin_id', $jadwalId)
                                  ->where('status', 'Sudah')
                                  ->exists();
            
            // Check age eligibility with the correct field names
            if ($ageInMonths >= $jenisVitamin->min_umur_bulan && $ageInMonths <= $jenisVitamin->max_umur_bulan) {
                $eligibleChildren[] = [
                    'anak' => $anak,
                    'is_registered' => $isRegistered,
                    'is_implemented' => $isImplemented,
                    'child_age_months' => $ageInMonths
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $eligibleChildren
        ]);
    }

    /**
     * Create vitamin records from a jadwal
     */
    public function createFromJadwal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jadwal_vitamin_id' => 'required|exists:jadwal_vitamin,id',
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
        $jadwal = JadwalVitamin::findOrFail($request->jadwal_vitamin_id);
        
        // Get anak and verify age eligibility
        $anak = Anak::findOrFail($request->anak_id);
        $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
        $umurBulan = $tanggalLahir->diffInMonths(Carbon::parse($jadwal->tanggal));
        
        // Get jenis vitamin
        $jenisVitamin = JenisVitamin::findOrFail($jadwal->jenis_vitamin_id);
        
        // Check if child's age is within the eligible range (using months for vitamin)
        if ($umurBulan < $jenisVitamin->min_umur_bulan || $umurBulan > $jenisVitamin->max_umur_bulan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anak tidak memenuhi syarat umur untuk vitamin ini',
                'data' => [
                    'umur_anak_bulan' => $umurBulan,
                    'min_umur_bulan' => $jenisVitamin->min_umur_bulan,
                    'max_umur_bulan' => $jenisVitamin->max_umur_bulan
                ]
            ], 422);
        }

        // Check if vitamin already exists for this child and jadwal
        $existingVitamin = Vitamin::where('anak_id', $request->anak_id)
            ->where('jenis_id', $jadwal->jenis_vitamin_id)
            ->first();

        if ($existingVitamin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data vitamin untuk anak dan jadwal ini sudah ada',
                'data' => $existingVitamin
            ], 422);
        }

        // Create new vitamin record with jadwal_vitamin_id
        $vitamin = Vitamin::create([
            'anak_id' => $request->anak_id,
            'jenis_id' => $jadwal->jenis_vitamin_id,
            'jadwal_vitamin_id' => $jadwal->id,
            'tanggal' => $jadwal->tanggal,
            'status' => Vitamin::STATUS_BELUM
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data vitamin berhasil dibuat dari jadwal',
            'data' => $vitamin->load(['anak', 'jenisVitamin'])
        ], 201);
    }

    /**
     * Mark a jadwal as complete and register all eligible children
     */
    public function completeJadwal($id)
    {
        try {
            $jadwal = JadwalVitamin::with('jenisVitamin')->findOrFail($id);
            
            // Get all children
            $allChildren = Anak::all();
            $eligibleChildren = [];
            $debug_info = [];
            
            foreach ($allChildren as $anak) {
                // Calculate age in months
                $birthDate = Carbon::parse($anak->tanggal_lahir);
                $ageInMonths = $birthDate->diffInMonths(Carbon::now());
                
                // Log info for debugging
                $debug_info[] = [
                    'anak_id' => $anak->id,
                    'nama' => $anak->nama_anak,
                    'tanggal_lahir' => $anak->tanggal_lahir,
                    'umur_bulan' => $ageInMonths,
                    'min_umur_bulan' => $jadwal->jenisVitamin->min_umur_bulan,
                    'max_umur_bulan' => $jadwal->jenisVitamin->max_umur_bulan,
                    'eligible' => ($ageInMonths >= $jadwal->jenisVitamin->min_umur_bulan && $ageInMonths <= $jadwal->jenisVitamin->max_umur_bulan)
                ];
                
                // Check age eligibility
                if ($ageInMonths >= $jadwal->jenisVitamin->min_umur_bulan && $ageInMonths <= $jadwal->jenisVitamin->max_umur_bulan) {
                    $eligibleChildren[] = $anak;
                }
            }
            
            // Register each eligible child for this vitamin if not already registered
            $registered = 0;
            foreach ($eligibleChildren as $anak) {
                // Check if already registered
                $existing = Vitamin::where('anak_id', $anak->id)
                                     ->where('jenis_id', $jadwal->jenis_vitamin_id)
                                     ->first();
                
                if (!$existing) {
                    // Create new vitamin record dengan status awal Belum
                    Vitamin::create([
                        'anak_id' => $anak->id,
                        'jenis_id' => $jadwal->jenis_vitamin_id,
                        'jadwal_vitamin_id' => $jadwal->id,
                        'tanggal' => $jadwal->tanggal,
                        'status' => 'Belum'
                    ]);
                    $registered++;
                }
            }
            
            // Status jadwal independent dari status vitamin
            // Tombol "Selesai" di jadwal.blade.php akan mengubah status jadwal secara langsung
            
            // Jika tidak ada anak yang eligible, tampilkan pesan yang jelas
            $message = $registered > 0 
                ? "Jadwal vitamin berhasil diselesaikan dan $registered anak ditambahkan ke daftar vitamin."
                : "Jadwal vitamin berhasil diselesaikan tetapi tidak ada anak yang memenuhi syarat umur (min: {$jadwal->jenisVitamin->min_umur_bulan} bulan, max: {$jadwal->jenisVitamin->max_umur_bulan} bulan).";
            
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
     * Update implementation status of jadwal - now independent of vitamin status
     */
    private function updateJadwalStatus($jadwalId)
    {
        // Status jadwal sekarang independent dari status vitamin
        // Akan diubah langsung menggunakan endpoint khusus
        return JadwalVitamin::findOrFail($jadwalId);
    }
}
