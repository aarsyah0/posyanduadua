<?php

namespace App\Http\Controllers;

use App\Models\JenisImunisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisImunisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman
        
        $query = JenisImunisasi::query();
        
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
        }
        
        $jenisImunisasi = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return view('jenis_imunisasi', compact('jenisImunisasi', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = 'create';
        $jenisOptions = JenisImunisasi::$jenisImunisasi; // Mengambil enum dari model
        return view('jenis_imunisasi', compact('action', 'jenisOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|in:' . implode(',', JenisImunisasi::$jenisImunisasi),
            'min_umur_hari' => 'required|integer|min:0',
            'max_umur_hari' => 'required|integer|gte:min_umur_hari',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        JenisImunisasi::create($request->all());

        return redirect()->route('jenis_imunisasi.index')
            ->with('success', 'Data jenis imunisasi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jenisImunisasi = JenisImunisasi::findOrFail($id);
        $action = 'show';
        return view('jenis_imunisasi', compact('jenisImunisasi', 'action'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenisImunisasi = JenisImunisasi::findOrFail($id);
        $jenisOptions = JenisImunisasi::$jenisImunisasi; // Mengambil enum dari model
        $action = 'edit';
        return view('jenis_imunisasi', compact('jenisImunisasi', 'action', 'jenisOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|in:' . implode(',', JenisImunisasi::$jenisImunisasi),
            'min_umur_hari' => 'required|integer|min:0',
            'max_umur_hari' => 'required|integer|gte:min_umur_hari',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $jenisImunisasi = JenisImunisasi::findOrFail($id);
        $jenisImunisasi->update($request->all());

        return redirect()->route('jenis_imunisasi.index')
            ->with('success', 'Data jenis imunisasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenisImunisasi = JenisImunisasi::findOrFail($id);
        $jenisImunisasi->delete();

        return redirect()->route('jenis_imunisasi.index')
            ->with('success', 'Data jenis imunisasi berhasil dihapus!');
    }
}
