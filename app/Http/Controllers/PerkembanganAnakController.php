<?php

namespace App\Http\Controllers;

use App\Models\PerkembanganAnak;
use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PerkembanganAnakController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman
        
        // Ambil semua anak yang memiliki setidaknya satu data perkembangan
        // Muat relasi perkembangan anak terbaru
        $query = Anak::has('perkembanganAnak')->with(['perkembanganAnak' => function($query) {
            $query->latest('tanggal'); // Ambil yang terbaru berdasarkan tanggal
        }]);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_anak', 'like', "%{$search}%")
                  ->orWhereHas('perkembanganAnak', function($sq) use ($search) {
                      $sq->where('tanggal', 'like', "%{$search}%");
                  });
            });
        }
        
        // Dapatkan hasil paginasi dari query Anak
        $anakPaginator = $query->paginate($perPage);

        // Sekarang, proses koleksi Anak di halaman saat ini untuk mendapatkan data perkembangan terbaru
        $perkembanganCollection = collect();
        foreach ($anakPaginator as $anak) {
            if ($anak->perkembanganAnak && $anak->perkembanganAnak->count() > 0) {
                $latest = $anak->perkembanganAnak->first(); // Ambil data perkembangan terbaru
                if ($latest) {
                    $latest->setRelation('anak', $anak); // Tambahkan relasi anak 
                    $perkembanganCollection->push($latest);
                }
            }
        }
        
        // Gunakan paginator yang ada tetapi dengan koleksi baru
        $perkembangan = new \Illuminate\Pagination\LengthAwarePaginator(
            $perkembanganCollection,
            $anakPaginator->total(),
            $anakPaginator->perPage(),
            $anakPaginator->currentPage(),
            ['path' => \Illuminate\Support\Facades\Request::url()]
        );
        
        // Set query string dari request asli ke paginator
        $perkembangan->appends($request->query());
        
        return view('perkembangan_anak', compact('perkembangan', 'search'));
    }

    public function create()
    {
        $action = 'create';
        $dataAnak = Anak::all();
        return view('perkembangan_anak', compact('action', 'dataAnak'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'berat_badan' => 'required|numeric|between:0,999.99',
            'tinggi_badan' => 'required|numeric|between:0,999.99',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $perkembangan = PerkembanganAnak::create($request->all());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data perkembangan anak berhasil ditambahkan!',
                    'data' => $perkembangan
                ]);
            }

            return redirect()->route('perkembangan.index')
                ->with('success', 'Data perkembangan anak berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Error storing perkembangan: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $perkembangan = PerkembanganAnak::with('anak')->findOrFail($id);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'perkembangan' => $perkembangan,
                    'anak' => $perkembangan->anak
                ]);
            }
            
            $action = 'show';
            return view('perkembangan_anak', compact('perkembangan', 'action'));
        } catch (\Exception $e) {
            \Log::error('Error in show perkembangan: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('perkembangan.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $perkembangan = PerkembanganAnak::with('anak')->findOrFail($id);
            $dataAnak = \App\Models\Anak::all();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'perkembangan' => $perkembangan,
                    'anak' => $perkembangan->anak,
                    'dataAnak' => $dataAnak
                ]);
            }
            
            $action = 'edit';
            return view('perkembangan_anak', compact('perkembangan', 'action', 'dataAnak'));
        } catch (\Exception $e) {
            \Log::error('Error in edit perkembangan: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('perkembangan.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'berat_badan' => 'required|numeric|between:0,999.99',
            'tinggi_badan' => 'required|numeric|between:0,999.99',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get the old record
            $oldRecord = PerkembanganAnak::findOrFail($id);
            
            // Create new record with updated data
            $newRecord = new PerkembanganAnak();
            $newRecord->anak_id = $request->anak_id;
            $newRecord->tanggal = $request->tanggal;
            $newRecord->berat_badan = $request->berat_badan;
            $newRecord->tinggi_badan = $request->tinggi_badan;
            $newRecord->updated_from_id = $id; // Reference to the old record
            $newRecord->save();

            // Mark old record as updated
            $oldRecord->update([
                'is_updated' => true,
                'updated_by_id' => $newRecord->id
            ]);
            
            // Update related stunting records if any
            if ($oldRecord) {
                $terkaitStunting = \App\Models\Stunting::where('perkembangan_id', $oldRecord->id)->get();
                foreach ($terkaitStunting as $stunting) {
                    $stunting->perkembangan_id = $newRecord->id;
                    $stunting->save();
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data perkembangan anak berhasil diperbarui!',
                    'data' => [
                        'old_record' => $oldRecord,
                        'new_record' => $newRecord
                    ]
                ]);
            }

            return redirect()->route('perkembangan.index')
                ->with('success', 'Data perkembangan anak berhasil diperbarui!');
                
        } catch (\Exception $e) {
            \Log::error('Error updating perkembangan: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $perkembangan = PerkembanganAnak::findOrFail($id);
            
            // Cek apakah ada data stunting yang terkait dengan perkembangan ini
            $terkaitStunting = \App\Models\Stunting::where('perkembangan_id', $perkembangan->id)->count();
            
            if ($terkaitStunting > 0) {
                return redirect()->route('perkembangan.index')
                    ->with('error', 'Data perkembangan anak tidak dapat dihapus karena masih terkait dengan data stunting! Hapus data stunting terkait terlebih dahulu.');
            }
            
            $perkembangan->delete();

            return redirect()->route('perkembangan.index')
                ->with('success', 'Data perkembangan anak berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('perkembangan.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function riwayat($anak_id)
    {
        try {
            // Cari data anak
            $anak = \App\Models\Anak::findOrFail($anak_id);
            
            // Ambil semua data perkembangan untuk anak ini
            $perkembangan = PerkembanganAnak::where('anak_id', $anak_id)
                ->orderBy('tanggal', 'desc')
                ->get();
            
            if ($perkembangan->isEmpty()) {
                return redirect()->route('perkembangan.index')
                    ->with('error', 'Tidak ada data riwayat perkembangan untuk anak ini');
            }
            
            $action = 'riwayat';
            return view('perkembangan_anak', compact('perkembangan', 'anak', 'action'));
        } catch (\Exception $e) {
            \Log::error('Error in riwayat perkembangan: ' . $e->getMessage());
            
            return redirect()->route('perkembangan.index')
                ->with('error', 'Terjadi kesalahan saat mengambil data riwayat: ' . $e->getMessage());
        }
    }
}
