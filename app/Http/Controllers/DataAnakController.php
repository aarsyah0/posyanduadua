<?php

namespace App\Http\Controllers;

use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataAnakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10); // Default 10 data per halaman
        
        // Log untuk debugging
        \Log::info('=========== DATA ANAK DEBUG ===========');
        \Log::info('Mencoba mengambil data anak');
        
        // Cek jumlah total data tanpa relasi
        $totalDataTanpaRelasi = Anak::count();
        \Log::info('Total data anak tanpa relasi: ' . $totalDataTanpaRelasi);
        
        // Log semua data anak untuk debugging
        $allAnakData = Anak::get();
        \Log::info('Data anak yang ada di database: ' . count($allAnakData));
        
        foreach ($allAnakData as $index => $anakItem) {
            \Log::info("Data anak #{$index}: ID={$anakItem->id}, Nama={$anakItem->nama_anak}, PenggunaID={$anakItem->pengguna_id}");
        }
        
        // Ubah query untuk menampilkan semua data anak
        // Gunakan eager loading untuk memuat data pengguna
        $query = Anak::with('pengguna');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_anak', 'like', "%{$search}%")
                  ->orWhere('tempat_lahir', 'like', "%{$search}%")
                  ->orWhereHas('pengguna', function($sq) use ($search) {
                      $sq->where('nik', 'like', "%{$search}%")
                         ->orWhere('nama', 'like', "%{$search}%");
                  });
            });
        }
        
        $anak = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        // Log jumlah data setelah filter
        \Log::info('Total data anak setelah filter: ' . $anak->total());
        
        // Cek relasi pengguna
        $adaRelasiPengguna = Anak::has('pengguna')->count();
        \Log::info('Total data anak dengan relasi pengguna: ' . $adaRelasiPengguna);
        
        // Cek apakah pengguna yang terkait adalah role 'parent'
        $penggunaParent = \App\Models\Pengguna::where('role', 'parent')->count();
        \Log::info('Total pengguna dengan role parent: ' . $penggunaParent);
        
        // Tampilkan pengguna IDs
        $penggunaIds = \App\Models\Pengguna::where('role', 'parent')->pluck('id')->toArray();
        \Log::info('ID Pengguna dengan role parent: ' . implode(', ', $penggunaIds));
        
        // Pastikan setiap anak memiliki data pengguna jika ada pengguna_id
        foreach ($anak as $anakItem) {
            if ($anakItem->pengguna_id && !$anakItem->pengguna) {
                // Jika pengguna_id ada tapi relasi pengguna kosong, coba muat manual
                $anakItem->pengguna = \App\Models\Pengguna::find($anakItem->pengguna_id);
                \Log::info("Memuat manual data pengguna untuk anak ID={$anakItem->id}, PenggunaID={$anakItem->pengguna_id}");
            }
        }
        
        \Log::info('=========== END DEBUG ===========');
        
        return view('data_anak', compact('anak', 'search'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $anak = Anak::with('pengguna')->findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'anak' => $anak,
                'pengguna' => $anak->pengguna
            ]);
        }
        
        $action = 'show';
        return view('data_anak', compact('anak', 'action'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $anak = Anak::with('pengguna')->findOrFail($id);
        $orangtua = \App\Models\Pengguna::where('role', 'parent')->get();
        $action = 'edit';
        return view('data_anak', compact('anak', 'action', 'orangtua'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'pengguna_id' => 'required|exists:pengguna,id',
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'usia' => 'required|string|max:10',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $anak = Anak::findOrFail($id);
        $anak->update($request->all());

        return redirect()->route('anak.index')
            ->with('success', 'Data anak berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $anak = Anak::findOrFail($id);
        $anak->delete();

        return redirect()->route('anak.index')
            ->with('success', 'Data anak berhasil dihapus!');
    }
}
