<?php

namespace App\Http\Controllers;

use App\Models\JenisVitamin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisVitaminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman
        
        $query = JenisVitamin::query();
        
        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
        }
        
        $jenisVitamin = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return view('jenis_vitamin', compact('jenisVitamin', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = 'create';
        $jenisOptions = JenisVitamin::$jenisVitamin; // Mengambil enum dari model
        return view('jenis_vitamin', compact('action', 'jenisOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|in:' . implode(',', JenisVitamin::$jenisVitamin),
            'min_umur_bulan' => 'required|integer|min:0',
            'max_umur_bulan' => 'required|integer|gte:min_umur_bulan',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        JenisVitamin::create($request->all());

        return redirect()->route('jenis_vitamin.index')
            ->with('success', 'Data jenis vitamin berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jenisVitamin = JenisVitamin::findOrFail($id);
        $action = 'show';
        return view('jenis_vitamin', compact('jenisVitamin', 'action'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jenisVitamin = JenisVitamin::findOrFail($id);
        $jenisOptions = JenisVitamin::$jenisVitamin; // Mengambil enum dari model
        $action = 'edit';
        return view('jenis_vitamin', compact('jenisVitamin', 'action', 'jenisOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|in:' . implode(',', JenisVitamin::$jenisVitamin),
            'min_umur_bulan' => 'required|integer|min:0',
            'max_umur_bulan' => 'required|integer|gte:min_umur_bulan',
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $jenisVitamin = JenisVitamin::findOrFail($id);
        $jenisVitamin->update($request->all());

        return redirect()->route('jenis_vitamin.index')
            ->with('success', 'Data jenis vitamin berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jenisVitamin = JenisVitamin::findOrFail($id);
        $jenisVitamin->delete();

        return redirect()->route('jenis_vitamin.index')
            ->with('success', 'Data jenis vitamin berhasil dihapus!');
    }
} 