<?php

namespace App\Http\Controllers;

use App\Models\Vitamin;
use App\Models\JenisVitamin;
use App\Models\Anak;
use App\Models\JadwalVitamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VitaminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman
        
        $query = Vitamin::with(['anak', 'jenisVitamin']);
        
        if ($search) {
            $query->whereHas('anak', function($q) use ($search) {
                $q->where('nama_anak', 'like', "%{$search}%");
            })
            ->orWhereHas('jenisVitamin', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->orWhere('status', 'like', "%{$search}%");
        }
        
        $vitamin = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Dapatkan semua jadwal vitamin yang tersedia
        $availableJadwal = $this->getAvailableJadwalVitamin();
        
        return view('vitamin', compact('vitamin', 'search', 'availableJadwal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = 'create';
        $dataAnak = Anak::all();
        $jenisVitamin = JenisVitamin::all();
        
        // Dapatkan semua jadwal vitamin yang tersedia
        $availableJadwal = $this->getAvailableJadwalVitamin();
        
        return view('vitamin', compact('action', 'dataAnak', 'jenisVitamin', 'availableJadwal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'jenis_id' => 'required|exists:jenis_vitamin,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Belum,Selesai',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi apakah umur anak sesuai dengan jenis vitamin
        $anak = Anak::findOrFail($request->anak_id);
        $jenisVitamin = JenisVitamin::findOrFail($request->jenis_id);
        
        $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
        $umurBulan = $tanggalLahir->diffInMonths(Carbon::parse($request->tanggal));
        
        if ($umurBulan < $jenisVitamin->min_umur_bulan || $umurBulan > $jenisVitamin->max_umur_bulan) {
            return redirect()->back()
                ->with('error', 'Umur anak (' . $umurBulan . ' bulan) tidak sesuai dengan rentang umur yang disarankan untuk vitamin ' . $jenisVitamin->nama . ' (' . $jenisVitamin->min_umur_bulan . '-' . $jenisVitamin->max_umur_bulan . ' bulan)')
                ->withInput();
        }

        Vitamin::create($request->all());

        return redirect()->route('vitamin.index')
            ->with('success', 'Data vitamin berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vitamin = Vitamin::with(['anak', 'jenisVitamin'])->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'vitamin' => $vitamin,
                'anak' => $vitamin->anak,
                'jenisVitamin' => $vitamin->jenisVitamin
            ]);
        }
        
        $action = 'show';
        return view('vitamin', compact('vitamin', 'action'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vitamin = Vitamin::with(['anak', 'jenisVitamin'])->findOrFail($id);
        
        if (request()->ajax()) {
            // Get all children and vitamin types for dropdowns
            $dataAnak = Anak::all();
            $jenisVitamin = JenisVitamin::all();
            
            return response()->json([
                'vitamin' => $vitamin,
                'anak' => $vitamin->anak,
                'jenisVitamin' => $vitamin->jenisVitamin,
                'dataAnak' => $dataAnak,
                'jenisVitaminList' => $jenisVitamin
            ]);
        }
        
        $dataAnak = Anak::all();
        $jenisVitamin = JenisVitamin::all();
        $action = 'edit';
        return view('vitamin', compact('vitamin', 'action', 'dataAnak', 'jenisVitamin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // If this is a JSON request (likely from AJAX or API)
        if ($request->expectsJson() || $request->ajax()) {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:' . implode(',', Vitamin::$statusList),
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $vitamin = Vitamin::findOrFail($id);
            $vitamin->status = $request->status;
            $vitamin->save();

            return response()->json([
                'success' => true,
                'message' => 'Status vitamin berhasil diperbarui!',
                'data' => $vitamin
            ]);
        }

        // Regular form submission
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'jenis_id' => 'required|exists:jenis_vitamin,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:' . implode(',', Vitamin::$statusList),
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vitamin = Vitamin::findOrFail($id);
        $vitamin->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data vitamin berhasil diperbarui!'
            ]);
        }

        return redirect()->route('vitamin.index')
            ->with('success', 'Data vitamin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vitamin = Vitamin::findOrFail($id);
        $vitamin->delete();

        return redirect()->route('vitamin.index')
            ->with('success', 'Data vitamin berhasil dihapus!');
    }
    
    /**
     * Mendapatkan jadwal vitamin yang tersedia untuk anak-anak berdasarkan umur
     */
    private function getAvailableJadwalVitamin()
    {
        // Dapatkan semua jadwal vitamin yang akan datang
        $upcomingJadwal = JadwalVitamin::with('jenisVitamin')
            ->where('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Dapatkan semua anak
        $dataAnak = Anak::all();
        
        $availableJadwal = [];
        
        // Untuk setiap jadwal, cek anak-anak yang memenuhi syarat umur
        foreach ($upcomingJadwal as $jadwal) {
            $jenisVitamin = $jadwal->jenisVitamin;
            if (!$jenisVitamin) continue;
            
            $eligibleChildren = [];
            
            // Cek setiap anak
            foreach ($dataAnak as $anak) {
                $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
                $umurBulan = $tanggalLahir->diffInMonths(Carbon::parse($jadwal->tanggal));
                
                // Periksa apakah anak berada dalam rentang umur yang sesuai
                if ($umurBulan >= $jenisVitamin->min_umur_bulan && $umurBulan <= $jenisVitamin->max_umur_bulan) {
                    // Cek apakah anak sudah terdaftar untuk vitamin ini
                    $existingVitamin = Vitamin::where('anak_id', $anak->id)
                        ->where('jenis_id', $jadwal->jenis_vitamin_id)
                        ->first();
                    
                    $eligibleChildren[] = [
                        'anak' => $anak,
                        'umur_bulan' => $umurBulan,
                        'already_registered' => $existingVitamin ? true : false,
                        'existing_vitamin' => $existingVitamin
                    ];
                }
            }
            
            // Jika ada anak yang memenuhi syarat, tambahkan jadwal ke daftar
            if (count($eligibleChildren) > 0) {
                $availableJadwal[] = [
                    'jadwal' => $jadwal,
                    'jenis_vitamin' => $jenisVitamin,
                    'eligible_children' => $eligibleChildren
                ];
            }
        }
        
        return $availableJadwal;
    }
    
    /**
     * Mendaftarkan anak untuk vitamin dari jadwal yang sudah ada
     */
    public function registerFromJadwal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'jadwal_vitamin_id' => 'required|exists:jadwal_vitamin,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Dapatkan jadwal vitamin
        $jadwal = JadwalVitamin::with('jenisVitamin')->findOrFail($request->jadwal_vitamin_id);
        
        // Verifikasi anak
        $anak = Anak::findOrFail($request->anak_id);
        
        // Verifikasi umur anak
        $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
        $umurBulan = $tanggalLahir->diffInMonths(Carbon::parse($jadwal->tanggal));
        
        if ($umurBulan < $jadwal->jenisVitamin->min_umur_bulan || $umurBulan > $jadwal->jenisVitamin->max_umur_bulan) {
            return redirect()->back()
                ->with('error', 'Umur anak (' . $umurBulan . ' bulan) tidak sesuai dengan rentang umur yang disarankan untuk vitamin ' . $jadwal->jenisVitamin->nama . ' (' . $jadwal->jenisVitamin->min_umur_bulan . '-' . $jadwal->jenisVitamin->max_umur_bulan . ' bulan)')
                ->withInput();
        }
        
        // Cek apakah anak sudah terdaftar untuk vitamin ini
        $existingVitamin = Vitamin::where('anak_id', $anak->id)
            ->where('jenis_id', $jadwal->jenis_vitamin_id)
            ->first();
            
        if ($existingVitamin) {
            return redirect()->back()
                ->with('error', 'Anak sudah terdaftar untuk vitamin ' . $jadwal->jenisVitamin->nama)
                ->withInput();
        }
        
        // Buat data vitamin baru
        Vitamin::create([
            'anak_id' => $anak->id,
            'jenis_id' => $jadwal->jenis_vitamin_id,
            'tanggal' => $jadwal->tanggal,
            'status' => Vitamin::STATUS_BELUM
        ]);
        
        return redirect()->route('vitamin.index')
            ->with('success', 'Anak berhasil didaftarkan untuk vitamin!');
    }
}
