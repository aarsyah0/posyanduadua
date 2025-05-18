<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataOrangTuaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        
        $query = Pengguna::where('role', 'parent');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }
        
        $pengguna = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Load anak relation for each pengguna
        $pengguna->each(function ($item) {
            $item->load('anak');
        });
        
        return view('data_orangtua', compact('pengguna', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $action = 'create';
        return view('data_orangtua', compact('action'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric|digits:16|unique:pengguna,nik',
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:pengguna,email',
            'password' => 'required|string|min:8',
            'no_telp' => 'nullable|numeric|digits_between:1,15',
            'alamat' => 'required|string',
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pengguna = Pengguna::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'parent',
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        Anak::create([
            'pengguna_id' => $pengguna->id,
            'nama_anak' => $request->nama_anak,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia' => $request->usia,
        ]);

        return redirect()->route('data_orangtua.index')
            ->with('success', 'Data orang tua berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nik)
    {
        $pengguna = Pengguna::with('anak')->where('role', 'parent')->findOrFail($nik);
        
        if (request()->ajax()) {
            return response()->json([
                'pengguna' => $pengguna,
                'anak' => $pengguna->anak
            ]);
        }
        
        $action = 'show';
        return view('data_orangtua', compact('pengguna', 'action'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $nik)
    {
        $pengguna = Pengguna::with('anak')->where('role', 'parent')->findOrFail($nik);
        $action = 'edit';
        return view('data_orangtua', compact('pengguna', 'action'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nik)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:pengguna,email,' . $nik . ',nik',
            'no_telp' => 'nullable|numeric|digits_between:1,15',
            'alamat' => 'required|string',
            'password' => 'nullable|string|min:8',
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pengguna = Pengguna::findOrFail($nik);
        $pengguna->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
        ]);

        if ($request->password) {
            $pengguna->update([
                'password' => bcrypt($request->password),
            ]);
        }

        // Check if anak relation exists, if not create it
        if ($pengguna->anak->isEmpty()) {
            Anak::create([
                'pengguna_id' => $pengguna->id,
                'nama_anak' => $request->nama_anak,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'usia' => $request->usia,
            ]);
        } else {
            // Update the first child
            $pengguna->anak->first()->update([
                'nama_anak' => $request->nama_anak,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'usia' => $request->usia,
            ]);
        }

        return redirect()->route('data_orangtua.index')
            ->with('success', 'Data orang tua berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nik)
    {
        $pengguna = Pengguna::findOrFail($nik);
        $pengguna->delete();

        return redirect()->route('data_orangtua.index')
            ->with('success', 'Data orang tua berhasil dihapus!');
    }
}
