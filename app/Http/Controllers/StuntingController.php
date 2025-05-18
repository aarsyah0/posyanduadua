<?php

namespace App\Http\Controllers;

use App\Models\Stunting;
use App\Models\Anak;
use App\Models\PerkembanganAnak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StuntingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman
        
        $query = Stunting::with(['anak', 'perkembangan']);
        
        if ($search) {
            $query->whereHas('anak', function($q) use ($search) {
                    $q->where('nama_anak', 'like', "%{$search}%");
                })
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('catatan', 'like', "%{$search}%");
        }
        
        $stunting = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Pastikan data perkembangan terbaru terkait selalu digunakan
        foreach ($stunting as $item) {
            if ($item->perkembangan) {
                // Refresh data relasi
                $item->perkembangan = PerkembanganAnak::find($item->perkembangan_id);
            }
        }
        
        return view('stunting', compact('stunting', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = 'create';
        $dataAnak = Anak::all();
        $dataPerkembangan = PerkembanganAnak::with('anak')->orderBy('tanggal', 'desc')->get();
        return view('stunting', compact('action', 'dataAnak', 'dataPerkembangan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'usia' => 'required|string|max:10',
            'catatan' => 'nullable|string',
            'status' => 'required|in:Stunting,Tidak Stunting',
            'perkembangan_id' => 'required|exists:perkembangan_anak,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil data perkembangan untuk mendapatkan tinggi_badan dan berat_badan
        $perkembangan = PerkembanganAnak::findOrFail($request->perkembangan_id);
        
        // Buat data stunting baru dengan nilai dari form dan data dari perkembangan
        $stunting = new Stunting();
        $stunting->anak_id = $request->anak_id;
        $stunting->tanggal = $request->tanggal;
        $stunting->usia = $request->usia;
        $stunting->catatan = $request->catatan;
        $stunting->status = $request->status;
        $stunting->perkembangan_id = $request->perkembangan_id;
        // Ambil tinggi_badan dan berat_badan langsung dari perkembangan terkait
        $stunting->tinggi_badan = $perkembangan->tinggi_badan;
        $stunting->berat_badan = $perkembangan->berat_badan;
        
        $stunting->save();

        return redirect()->route('stunting.index')
            ->with('success', 'Data stunting berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $stunting = Stunting::with(['anak', 'perkembangan'])->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'stunting' => $stunting,
                'anak' => $stunting->anak,
                'perkembangan' => $stunting->perkembangan
            ]);
        }
        
        $action = 'show';
        return view('stunting', compact('stunting', 'action'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stunting = Stunting::with(['anak', 'perkembangan'])->findOrFail($id);
        
        if (request()->ajax()) {
            // Get all children and perkembangan for dropdown
            $dataAnak = Anak::all();
            $dataPerkembangan = PerkembanganAnak::with('anak')->orderBy('tanggal', 'desc')->get();
            
            return response()->json([
                'stunting' => $stunting,
                'anak' => $stunting->anak,
                'perkembangan' => $stunting->perkembangan,
                'dataAnak' => $dataAnak,
                'dataPerkembangan' => $dataPerkembangan
            ]);
        }
        
        $action = 'edit';
        $dataAnak = Anak::all();
        $dataPerkembangan = PerkembanganAnak::with('anak')->orderBy('tanggal', 'desc')->get();
        return view('stunting', compact('stunting', 'action', 'dataAnak', 'dataPerkembangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'usia' => 'required|string|max:10',
            'catatan' => 'nullable|string',
            'status' => 'required|in:Stunting,Tidak Stunting',
            'perkembangan_id' => 'required|exists:perkembangan_anak,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $stunting = Stunting::findOrFail($id);
        
        // Ambil data perkembangan untuk mendapatkan tinggi_badan dan berat_badan
        $perkembangan = PerkembanganAnak::findOrFail($request->perkembangan_id);
        
        // Update data stunting dengan nilai dari form dan data dari perkembangan
        $stunting->anak_id = $request->anak_id;
        $stunting->tanggal = $request->tanggal;
        $stunting->usia = $request->usia;
        $stunting->catatan = $request->catatan;
        $stunting->status = $request->status;
        $stunting->perkembangan_id = $request->perkembangan_id;
        // Ambil tinggi_badan dan berat_badan langsung dari perkembangan terkait
        $stunting->tinggi_badan = $perkembangan->tinggi_badan;
        $stunting->berat_badan = $perkembangan->berat_badan;
        
        $stunting->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data stunting berhasil diperbarui!'
            ]);
        }

        return redirect()->route('stunting.index')
            ->with('success', 'Data stunting berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stunting = Stunting::findOrFail($id);
        $stunting->delete();

        return redirect()->route('stunting.index')
            ->with('success', 'Data stunting berhasil dihapus!');
    }
}
