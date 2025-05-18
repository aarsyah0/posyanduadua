<?php

namespace App\Http\Controllers;

use App\Models\Imunisasi;
use App\Models\Anak;
use App\Models\JenisImunisasi;
use App\Models\JadwalImunisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ImunisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman
        
        $query = Imunisasi::with(['anak', 'jenisImunisasi']);
        
        if ($search) {
            $query->whereHas('anak', function($q) use ($search) {
                $q->where('nama_anak', 'like', "%{$search}%");
            })
            ->orWhereHas('jenisImunisasi', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->orWhere('status', 'like', "%{$search}%");
        }
        
        $imunisasi = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Dapatkan semua jadwal imunisasi yang tersedia
        $availableJadwal = $this->getAvailableJadwalImunisasi();
        
        return view('imunisasi', compact('imunisasi', 'search', 'availableJadwal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = 'create';
        $dataAnak = Anak::all();
        $jenisImunisasi = JenisImunisasi::all();
        
        // Dapatkan semua jadwal imunisasi yang tersedia
        $availableJadwal = $this->getAvailableJadwalImunisasi();
        
        return view('imunisasi', compact('action', 'dataAnak', 'jenisImunisasi', 'availableJadwal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'jenis_id' => 'required|exists:jenis_imunisasi,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:' . implode(',', Imunisasi::$statusList),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi apakah umur anak sesuai dengan jenis imunisasi
        $anak = Anak::findOrFail($request->anak_id);
        $jenisImunisasi = JenisImunisasi::findOrFail($request->jenis_id);
        
        $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
        $umurHari = $tanggalLahir->diffInDays(Carbon::parse($request->tanggal));
        
        if ($umurHari < $jenisImunisasi->min_umur_hari || $umurHari > $jenisImunisasi->max_umur_hari) {
            return redirect()->back()
                ->with('error', 'Umur anak (' . $umurHari . ' hari) tidak sesuai dengan rentang umur yang disarankan untuk imunisasi ' . $jenisImunisasi->nama . ' (' . $jenisImunisasi->min_umur_hari . '-' . $jenisImunisasi->max_umur_hari . ' hari)')
                ->withInput();
        }

        Imunisasi::create($request->all());

        return redirect()->route('imunisasi.index')
            ->with('success', 'Data imunisasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $imunisasi = Imunisasi::with(['anak', 'jenisImunisasi'])->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'imunisasi' => $imunisasi,
                'anak' => $imunisasi->anak,
                'jenisImunisasi' => $imunisasi->jenisImunisasi
            ]);
        }
        
        $action = 'show';
        return view('imunisasi', compact('imunisasi', 'action'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $imunisasi = Imunisasi::with(['anak', 'jenisImunisasi'])->findOrFail($id);
        
        if (request()->ajax()) {
            // Get all children and immunization types for dropdowns
            $dataAnak = Anak::all();
            $jenisImunisasi = JenisImunisasi::all();
            
            return response()->json([
                'imunisasi' => $imunisasi,
                'anak' => $imunisasi->anak,
                'jenisImunisasi' => $imunisasi->jenisImunisasi,
                'dataAnak' => $dataAnak,
                'jenisImunisasiList' => $jenisImunisasi
            ]);
        }
        
        $dataAnak = Anak::all();
        $jenisImunisasi = JenisImunisasi::all();
        $action = 'edit';
        return view('imunisasi', compact('imunisasi', 'action', 'dataAnak', 'jenisImunisasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // If this is a JSON request (likely from AJAX or API)
        if ($request->expectsJson() || $request->ajax()) {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:' . implode(',', Imunisasi::$statusList),
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $imunisasi = Imunisasi::findOrFail($id);
            $imunisasi->status = $request->status;
            $imunisasi->save();

            return response()->json([
                'success' => true,
                'message' => 'Status imunisasi berhasil diperbarui!',
                'data' => $imunisasi
            ]);
        }

        // Regular form submission
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'jenis_id' => 'required|exists:jenis_imunisasi,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:' . implode(',', Imunisasi::$statusList),
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imunisasi = Imunisasi::findOrFail($id);
        $imunisasi->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data imunisasi berhasil diperbarui!'
            ]);
        }

        return redirect()->route('imunisasi.index')
            ->with('success', 'Data imunisasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imunisasi = Imunisasi::findOrFail($id);
        $imunisasi->delete();

        return redirect()->route('imunisasi.index')
            ->with('success', 'Data imunisasi berhasil dihapus!');
    }

    /**
     * Mendapatkan jadwal imunisasi yang tersedia untuk anak-anak berdasarkan umur
     */
    private function getAvailableJadwalImunisasi()
    {
        // Dapatkan semua jadwal imunisasi yang akan datang
        $upcomingJadwal = JadwalImunisasi::with('jenisImunisasi')
            ->where('tanggal', '>=', Carbon::today())
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Dapatkan semua anak
        $dataAnak = Anak::all();
        
        $availableJadwal = [];
        
        // Untuk setiap jadwal, cek anak-anak yang memenuhi syarat umur
        foreach ($upcomingJadwal as $jadwal) {
            $jenisImunisasi = $jadwal->jenisImunisasi;
            if (!$jenisImunisasi) continue;
            
            $eligibleChildren = [];
            
            // Cek setiap anak
            foreach ($dataAnak as $anak) {
                $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
                $umurHari = $tanggalLahir->diffInDays(Carbon::now());
                
                // Periksa apakah anak berada dalam rentang umur yang sesuai
                if ($umurHari >= $jenisImunisasi->min_umur_hari && $umurHari <= $jenisImunisasi->max_umur_hari) {
                    // Cek apakah anak sudah terdaftar untuk imunisasi ini
                    $existingImunisasi = Imunisasi::where('anak_id', $anak->id)
                        ->where('jenis_id', $jadwal->jenis_imunisasi_id)
                        ->first();
                    
                    $eligibleChildren[] = [
                        'anak' => $anak,
                        'umur_hari' => $umurHari,
                        'already_registered' => $existingImunisasi ? true : false,
                        'existing_imunisasi' => $existingImunisasi
                    ];
                }
            }
            
            // Jika ada anak yang memenuhi syarat, tambahkan jadwal ke daftar
            if (count($eligibleChildren) > 0) {
                $availableJadwal[] = [
                    'jadwal' => $jadwal,
                    'jenis_imunisasi' => $jenisImunisasi,
                    'eligible_children' => $eligibleChildren
                ];
            }
        }
        
        return $availableJadwal;
    }
    
    /**
     * Mendaftarkan anak untuk imunisasi dari jadwal yang sudah ada
     */
    public function registerFromJadwal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'jadwal_imunisasi_id' => 'required|exists:jadwal_imunisasi,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Dapatkan jadwal imunisasi
        $jadwal = JadwalImunisasi::with('jenisImunisasi')->findOrFail($request->jadwal_imunisasi_id);
        
        // Verifikasi anak
        $anak = Anak::findOrFail($request->anak_id);
        
        // Verifikasi umur anak
        $tanggalLahir = Carbon::parse($anak->tanggal_lahir);
        $umurHari = $tanggalLahir->diffInDays(Carbon::now());
        
        if ($umurHari < $jadwal->jenisImunisasi->min_umur_hari || $umurHari > $jadwal->jenisImunisasi->max_umur_hari) {
            return redirect()->back()
                ->with('error', 'Umur anak (' . $umurHari . ' hari) tidak sesuai dengan rentang umur yang disarankan untuk imunisasi ' . $jadwal->jenisImunisasi->nama . ' (' . $jadwal->jenisImunisasi->min_umur_hari . '-' . $jadwal->jenisImunisasi->max_umur_hari . ' hari)')
                ->withInput();
        }
        
        // Cek apakah anak sudah terdaftar untuk imunisasi ini
        $existingImunisasi = Imunisasi::where('anak_id', $anak->id)
            ->where('jenis_id', $jadwal->jenis_imunisasi_id)
            ->first();
            
        if ($existingImunisasi) {
            return redirect()->back()
                ->with('error', 'Anak sudah terdaftar untuk imunisasi ' . $jadwal->jenisImunisasi->nama)
                ->withInput();
        }
        
        // Buat data imunisasi baru
        Imunisasi::create([
            'anak_id' => $anak->id,
            'jenis_id' => $jadwal->jenis_imunisasi_id,
            'tanggal' => $jadwal->tanggal,
            'status' => Imunisasi::STATUS_BELUM
        ]);
        
        return redirect()->route('imunisasi.index')
            ->with('success', 'Anak berhasil didaftarkan untuk imunisasi!');
    }
}
