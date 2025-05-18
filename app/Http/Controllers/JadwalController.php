<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPemeriksaan;
use App\Models\JadwalImunisasi;
use App\Models\JadwalVitamin;
use App\Models\JenisImunisasi;
use App\Models\JenisVitamin;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // Get perPage parameter, default to 10
        $perPage = $request->input('perPage', 10);
        $jenis = $request->input('jenis', 'semua');
        $search = $request->input('search', '');
        
        // Prepare queries for each jadwal type
        $queryPemeriksaan = JadwalPemeriksaan::orderBy('tanggal', 'desc')->orderBy('waktu', 'desc')->orderBy('created_at', 'desc');
        $queryImunisasi = JadwalImunisasi::with('jenisImunisasi')->orderBy('tanggal', 'desc')->orderBy('waktu', 'desc')->orderBy('created_at', 'desc');
        $queryVitamin = JadwalVitamin::with('jenisVitamin')->orderBy('tanggal', 'desc')->orderBy('waktu', 'desc')->orderBy('created_at', 'desc');
        
        // Apply search filter if provided
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $queryPemeriksaan->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('tanggal', 'like', "%{$search}%")
                  ->orWhere('waktu', 'like', "%{$search}%");
            });
            
            // For Imunisasi, search through relationship
            $queryImunisasi->whereHas('jenisImunisasi', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhere('tanggal', 'like', "%{$search}%");
            
            // For Vitamin, search through relationship
            $queryVitamin->whereHas('jenisVitamin', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })->orWhere('tanggal', 'like', "%{$search}%");
        }
        
        // Get data based on the selected type
        $jadwalPemeriksaan = ($jenis == 'semua' || $jenis == 'pemeriksaan') ? $queryPemeriksaan->paginate($perPage) : collect([]);
        $jadwalImunisasi = ($jenis == 'semua' || $jenis == 'imunisasi') ? $queryImunisasi->paginate($perPage) : collect([]);
        $jadwalVitamin = ($jenis == 'semua' || $jenis == 'vitamin') ? $queryVitamin->paginate($perPage) : collect([]);
        
        // Create a collection of all jadwals for the combined view
        $allJadwal = new Collection();
        
        // Add pemeriksaan items with type information
        if ($jenis == 'semua' || $jenis == 'pemeriksaan') {
            foreach ($queryPemeriksaan->get() as $jp) {
                $allJadwal->push((object)[
                    'id' => $jp->id,
                    'nama' => $jp->judul,
                    'jenis' => 'pemeriksaan rutin',
                    'tanggal' => $jp->tanggal,
                    'waktu' => $jp->waktu,
                    'is_implemented' => $jp->is_implemented,
                    'min_umur_hari' => null,
                    'max_umur_hari' => null,
                    'min_umur_bulan' => null,
                    'max_umur_bulan' => null,
                    'keterangan' => null
                ]);
            }
        }
        
        // Add imunisasi items with type information
        if ($jenis == 'semua' || $jenis == 'imunisasi') {
            foreach ($queryImunisasi->get() as $ji) {
                $allJadwal->push((object)[
                    'id' => $ji->id,
                    'nama' => $ji->jenisImunisasi->nama ?? 'Imunisasi',
                    'jenis' => 'imunisasi',
                    'tanggal' => $ji->tanggal,
                    'waktu' => $ji->waktu,
                    'is_implemented' => $ji->is_implemented,
                    'min_umur_hari' => $ji->jenisImunisasi->min_umur_hari ?? null,
                    'max_umur_hari' => $ji->jenisImunisasi->max_umur_hari ?? null,
                    'min_umur_bulan' => null,
                    'max_umur_bulan' => null,
                    'keterangan' => $ji->jenisImunisasi->keterangan ?? null
                ]);
            }
        }
        
        // Add vitamin items with type information
        if ($jenis == 'semua' || $jenis == 'vitamin') {
            foreach ($queryVitamin->get() as $jv) {
                $allJadwal->push((object)[
                    'id' => $jv->id,
                    'nama' => $jv->jenisVitamin->nama ?? 'Vitamin',
                    'jenis' => 'vitamin',
                    'tanggal' => $jv->tanggal,
                    'waktu' => $jv->waktu,
                    'is_implemented' => $jv->is_implemented,
                    'min_umur_hari' => null,
                    'max_umur_hari' => null,
                    'min_umur_bulan' => $jv->jenisVitamin->min_umur_bulan ?? null,
                    'max_umur_bulan' => $jv->jenisVitamin->max_umur_bulan ?? null,
                    'keterangan' => $jv->jenisVitamin->keterangan ?? null
                ]);
            }
        }
        
        // Sort the combined collection - urut dari yang terbaru
        $allJadwal = $allJadwal->sortByDesc('tanggal')->sortByDesc('waktu')->sortByDesc('created_at');
        
        // Reset index dan tambahkan nomor urut
        $allJadwal = $allJadwal->values();
        
        // Tambahkan nomor urut ke setiap item
        $allJadwal = $allJadwal->map(function($item, $index) {
            $item->nomor_urut = $index + 1;
            return $item;
        });
        
        // Paginate the combined collection
        $page = request()->input('page', 1);
        $jadwal = new LengthAwarePaginator(
            $allJadwal->forPage($page, $perPage),
            $allJadwal->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        // Get data for dropdowns
        $jenisImunisasi = JenisImunisasi::all();
        $jenisVitamin = JenisVitamin::all();
        
        return view('jadwal', compact(
            'jadwal',
            'jadwalPemeriksaan', 
            'jadwalImunisasi', 
            'jadwalVitamin', 
            'jenisImunisasi', 
            'jenisVitamin',
            'jenis',
            'search'
        ));
    }

    // Create Jadwal Pemeriksaan
    public function storePemeriksaan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
        ]);

        try {
            JadwalPemeriksaan::create([
                'judul' => 'Posyandu',
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
            ]);

            return redirect()->route('jadwal')->with('success', 'Jadwal pemeriksaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('jadwal')->with('error', 'Gagal menambahkan jadwal pemeriksaan: ' . $e->getMessage());
        }
    }
    
    // Create Jadwal Imunisasi
    public function storeImunisasi(Request $request)
    {
        $request->validate([
            'jenis_imunisasi_id' => 'required|exists:jenis_imunisasi,id',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
        ]);

        try {
            JadwalImunisasi::create([
                'jenis_imunisasi_id' => $request->jenis_imunisasi_id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'is_implemented' => false,
            ]);

            return redirect()->route('jadwal')->with('success', 'Jadwal imunisasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('jadwal')->with('error', 'Gagal menambahkan jadwal imunisasi: ' . $e->getMessage());
        }
    }
    
    // Create Jadwal Vitamin
    public function storeVitamin(Request $request)
    {
        $request->validate([
            'jenis_vitamin_id' => 'required|exists:jenis_vitamin,id',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
        ]);

        try {
            JadwalVitamin::create([
                'jenis_vitamin_id' => $request->jenis_vitamin_id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'is_implemented' => false,
            ]);

            return redirect()->route('jadwal')->with('success', 'Jadwal vitamin berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->route('jadwal')->with('error', 'Gagal menambahkan jadwal vitamin: ' . $e->getMessage());
        }
    }

    // Delete Jadwal Pemeriksaan
    public function destroyPemeriksaan($id)
    {
        try {
            $jadwal = JadwalPemeriksaan::findOrFail($id);
            $jadwal->delete();

            return redirect()->route('jadwal')->with('success', 'Jadwal pemeriksaan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('jadwal')->with('error', 'Gagal menghapus jadwal pemeriksaan: ' . $e->getMessage());
        }
    }
    
    // Delete Jadwal Imunisasi
    public function destroyImunisasi($id)
    {
        try {
            $jadwal = JadwalImunisasi::findOrFail($id);
            $jadwal->delete();

            return redirect()->route('jadwal')->with('success', 'Jadwal imunisasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('jadwal')->with('error', 'Gagal menghapus jadwal imunisasi: ' . $e->getMessage());
        }
    }
    
    // Delete Jadwal Vitamin
    public function destroyVitamin($id)
    {
        try {
            $jadwal = JadwalVitamin::findOrFail($id);
            $jadwal->delete();

            return redirect()->route('jadwal')->with('success', 'Jadwal vitamin berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('jadwal')->with('error', 'Gagal menghapus jadwal vitamin: ' . $e->getMessage());
        }
    }

    // Edit Jadwal Pemeriksaan
    public function editPemeriksaan($id)
    {
        $jadwal = JadwalPemeriksaan::findOrFail($id);
        return response()->json([
            'jadwal' => $jadwal
        ]);
    }
    
    // Edit Jadwal Imunisasi
    public function editImunisasi($id)
    {
        $jadwal = JadwalImunisasi::with('jenisImunisasi')->findOrFail($id);
        $jenisImunisasi = JenisImunisasi::all();
        return response()->json([
            'jadwal' => $jadwal,
            'jenisImunisasi' => $jenisImunisasi
        ]);
    }
    
    // Edit Jadwal Vitamin
    public function editVitamin($id)
    {
        $jadwal = JadwalVitamin::with('jenisVitamin')->findOrFail($id);
        $jenisVitamin = JenisVitamin::all();
        return response()->json([
            'jadwal' => $jadwal,
            'jenisVitamin' => $jenisVitamin
        ]);
    }

    // Update Jadwal Pemeriksaan
    public function updatePemeriksaan(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'tanggal' => 'required|date',
                'waktu' => 'required|date_format:H:i',
            ]);
            
            if ($validator->fails()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $jadwal = JadwalPemeriksaan::findOrFail($id);
            $jadwal->update([
                'judul' => 'Posyandu',
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal pemeriksaan berhasil diperbarui.'
                ]);
            }

            return redirect()->route('jadwal')->with('success', 'Jadwal pemeriksaan berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui jadwal pemeriksaan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('jadwal')->with('error', 'Gagal memperbarui jadwal pemeriksaan: ' . $e->getMessage());
        }
    }
    
    // Update Jadwal Imunisasi
    public function updateImunisasi(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'jenis_imunisasi_id' => 'required|exists:jenis_imunisasi,id',
                'tanggal' => 'required|date',
                'waktu' => 'required|date_format:H:i',
            ]);
            
            if ($validator->fails()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $jadwal = JadwalImunisasi::findOrFail($id);
            $jadwal->update([
                'jenis_imunisasi_id' => $request->jenis_imunisasi_id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'is_implemented' => $jadwal->is_implemented ?? false,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal imunisasi berhasil diperbarui.'
                ]);
            }

            return redirect()->route('jadwal')->with('success', 'Jadwal imunisasi berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui jadwal imunisasi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('jadwal')->with('error', 'Gagal memperbarui jadwal imunisasi: ' . $e->getMessage());
        }
    }
    
    // Update Jadwal Vitamin
    public function updateVitamin(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'jenis_vitamin_id' => 'required|exists:jenis_vitamin,id',
                'tanggal' => 'required|date',
                'waktu' => 'required|date_format:H:i',
            ]);
            
            if ($validator->fails()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $jadwal = JadwalVitamin::findOrFail($id);
            $jadwal->update([
                'jenis_vitamin_id' => $request->jenis_vitamin_id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'is_implemented' => $jadwal->is_implemented ?? false,
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal vitamin berhasil diperbarui.'
                ]);
            }

            return redirect()->route('jadwal')->with('success', 'Jadwal vitamin berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui jadwal vitamin: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('jadwal')->with('error', 'Gagal memperbarui jadwal vitamin: ' . $e->getMessage());
        }
    }
}
