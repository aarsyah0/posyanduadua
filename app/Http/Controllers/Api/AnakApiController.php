<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnakApiController extends Controller
{
    /**
     * Get all children for a specific parent
     */
    public function getByPenggunaId($pengguna_id)
    {
        $pengguna = Pengguna::find($pengguna_id);
        
        if (!$pengguna) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pengguna tidak ditemukan',
            ], 404);
        }
        
        $anak = Anak::where('pengguna_id', $pengguna_id)->get();
        
        // Calculate age in months for each child
        $anak->each(function ($item) {
            $item->usia_bulan = $this->hitungUsiaBulan($item->tanggal_lahir);
        });
            
        return response()->json([
            'status' => 'success',
            'anak' => $anak,
        ]);
    }
    
    /**
     * Get a specific child record
     */
    public function show($id)
    {
        $anak = Anak::find($id);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }
        
        // Calculate age in months
        $anak->usia_bulan = $this->hitungUsiaBulan($anak->tanggal_lahir);
        
        return response()->json([
            'status' => 'success',
            'anak' => $anak,
        ]);
    }
    
    /**
     * Create a new child record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pengguna_id' => 'required|exists:pengguna,id',
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Calculate age based on birth date
        $birthDate = new \DateTime($request->tanggal_lahir);
        $today = new \DateTime('today');
        $interval = $birthDate->diff($today);
        $usia = $interval->y . ' tahun ' . $interval->m . ' bulan';
        
        // Add age to request data
        $data = $request->all();
        $data['usia'] = $usia;
        
        $anak = Anak::create($data);
        
        // Calculate age in months
        $anak->usia_bulan = $this->hitungUsiaBulan($anak->tanggal_lahir);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data anak berhasil ditambahkan',
            'anak' => $anak,
        ], 201);
    }
    
    /**
     * Update a child record
     */
    public function update(Request $request, $id)
    {
        $anak = Anak::find($id);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Calculate age based on birth date
        $birthDate = new \DateTime($request->tanggal_lahir);
        $today = new \DateTime('today');
        $interval = $birthDate->diff($today);
        $usia = $interval->y . ' tahun ' . $interval->m . ' bulan';
        
        // Add age to request data
        $data = $request->all();
        $data['usia'] = $usia;
        
        $anak->update($data);
        
        // Calculate age in months
        $anak->usia_bulan = $this->hitungUsiaBulan($anak->tanggal_lahir);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data anak berhasil diperbarui',
            'anak' => $anak,
        ]);
    }
    
    /**
     * Delete a child record
     */
    public function destroy($id)
    {
        $anak = Anak::find($id);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }
        
        $anak->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data anak berhasil dihapus',
        ]);
    }
    
    // Helper method to calculate age in months
    private function hitungUsiaBulan($tanggal_lahir)
    {
        if (!$tanggal_lahir) return 0;
        
        $birthDate = new \DateTime($tanggal_lahir);
        $today = new \DateTime('today');
        $interval = $birthDate->diff($today);
        return ($interval->y * 12) + $interval->m;
    }
} 