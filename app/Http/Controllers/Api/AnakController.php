<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AnakController extends Controller
{
    /**
     * Mendapatkan daftar anak
     * 
     * - Untuk mobile app: mendapatkan anak milik user terautentikasi
     * - Untuk admin: bisa mendapatkan semua anak atau filter berdasarkan pengguna_id
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Log untuk debugging
        \Log::info("=== Get anak data ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        
        // Default query - untuk daftar semua anak jika admin, atau milik sendiri jika parent
        $query = Anak::with('pengguna');
        
        if ($user->role === 'parent') {
            // Jika user adalah parent, hanya tampilkan anak miliknya
            $query->where('pengguna_id', $user->id);
            \Log::info("Filtering: only showing children for parent ID {$user->id}");
        } else if ($request->has('pengguna_id') && $user->role === 'admin') {
            // Admin bisa filter berdasarkan pengguna_id
            $query->where('pengguna_id', $request->pengguna_id);
            \Log::info("Admin filtering: showing children for parent ID {$request->pengguna_id}");
        }
        
        $anakList = $query->get();
        \Log::info("Found {$anakList->count()} children records");
        
        // Pastikan data pengguna (orangtua) tersedia untuk setiap anak
        $anakWithParents = $anakList->map(function ($anak) {
            $data = $anak->toArray();
            // Jika tidak ada data pengguna tapi ada pengguna_id, coba load manual
            if ((!isset($data['pengguna']) || $data['pengguna'] === null) && $anak->pengguna_id) {
                $pengguna = \App\Models\Pengguna::find($anak->pengguna_id);
                if ($pengguna) {
                    $data['pengguna'] = $pengguna->toArray();
                    \Log::info("Manually loaded parent data for child ID {$anak->id}, parent ID {$anak->pengguna_id}");
                }
            }
            return $data;
        });
        
        return response()->json([
            'success' => true,
            'data' => $anakWithParents
        ]);
    }

    /**
     * Menyimpan data anak baru
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Log untuk debugging
        \Log::info("=== Store anak data ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Request data:", $request->all());
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'pengguna_id' => 'sometimes|exists:pengguna,id',
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            \Log::error("Validation failed: " . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Tentukan pengguna_id
            $pengguna_id = null;
            
            if ($user->role === 'parent') {
                // Jika parent, gunakan ID sendiri
                $pengguna_id = $user->id;
                \Log::info("Using authenticated parent ID: {$pengguna_id}");
            } else if ($user->role === 'admin' && $request->has('pengguna_id')) {
                // Jika admin, gunakan pengguna_id yang diberikan
                $pengguna_id = $request->pengguna_id;
                \Log::info("Admin assigning to parent ID: {$pengguna_id}");
            } else if ($user->role === 'admin' && !$request->has('pengguna_id')) {
                // Jika admin tidak menentukan pengguna_id, biarkan null (anak tanpa orangtua)
                \Log::info("Admin creating child without parent");
            }
            
            $anak = new Anak();
            $anak->pengguna_id = $pengguna_id;
            $anak->nama_anak = $request->nama_anak;
            $anak->tempat_lahir = $request->tempat_lahir;
            $anak->tanggal_lahir = $request->tanggal_lahir;
            $anak->jenis_kelamin = $request->jenis_kelamin;
            $anak->usia = $request->usia;
            $anak->save();
            
            \Log::info("Child data saved successfully. ID: {$anak->id}");
            
            // Load data pengguna (orangtua) jika tersedia
            $anak->load('pengguna');
            
            // Jika tidak ada data pengguna tapi ada pengguna_id, coba load manual
            $responseData = $anak->toArray();
            if ((!isset($responseData['pengguna']) || $responseData['pengguna'] === null) && $anak->pengguna_id) {
                $pengguna = \App\Models\Pengguna::find($anak->pengguna_id);
                if ($pengguna) {
                    $responseData['pengguna'] = $pengguna->toArray();
                    \Log::info("Manually loaded parent data for created child");
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data anak berhasil disimpan',
                'data' => $responseData
            ], 201);
        } catch (\Exception $e) {
            \Log::error("Error saving child data: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan detail anak
     */
    public function show($id)
    {
        $user = auth()->user();
        $anak = Anak::with('pengguna')->find($id);
        
        if (!$anak) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan'
            ], 404);
        }
        
        // Verifikasi akses
        if ($user->role !== 'admin' && $anak->pengguna_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data ini'
            ], 403);
        }
        
        // Pastikan data pengguna (orangtua) tersedia
        $responseData = $anak->toArray();
        if ((!isset($responseData['pengguna']) || $responseData['pengguna'] === null) && $anak->pengguna_id) {
            $pengguna = \App\Models\Pengguna::find($anak->pengguna_id);
            if ($pengguna) {
                $responseData['pengguna'] = $pengguna->toArray();
                \Log::info("Manually loaded parent data for child ID {$anak->id}");
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $responseData
        ]);
    }

    /**
     * Mengupdate data anak
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $anak = Anak::find($id);
        
        // Log untuk debugging
        \Log::info("=== Update anak data ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Child ID: {$id}");
        \Log::info("Request data:", $request->all());
        
        if (!$anak) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan'
            ], 404);
        }
        
        // Verifikasi akses
        if ($user->role !== 'admin' && $anak->pengguna_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah data ini'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'nama_anak' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Admin bisa mengubah pengguna_id
            if ($user->role === 'admin' && $request->has('pengguna_id')) {
                $anak->pengguna_id = $request->pengguna_id;
            }
            
            $anak->nama_anak = $request->nama_anak;
            $anak->tempat_lahir = $request->tempat_lahir;
            $anak->tanggal_lahir = $request->tanggal_lahir;
            $anak->jenis_kelamin = $request->jenis_kelamin;
            $anak->usia = $request->usia;
            $anak->save();
            
            \Log::info("Child data updated successfully");
            
            // Load data pengguna (orangtua) setelah update
            $anak->load('pengguna');
            
            // Pastikan data pengguna (orangtua) tersedia
            $responseData = $anak->toArray();
            if ((!isset($responseData['pengguna']) || $responseData['pengguna'] === null) && $anak->pengguna_id) {
                $pengguna = \App\Models\Pengguna::find($anak->pengguna_id);
                if ($pengguna) {
                    $responseData['pengguna'] = $pengguna->toArray();
                    \Log::info("Manually loaded parent data for updated child");
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data anak berhasil diperbarui',
                'data' => $responseData
            ]);
        } catch (\Exception $e) {
            \Log::error("Error updating child data: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghapus data anak
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $anak = Anak::find($id);
        
        if (!$anak) {
            return response()->json([
                'success' => false,
                'message' => 'Data anak tidak ditemukan'
            ], 404);
        }
        
        // Verifikasi akses
        if ($user->role !== 'admin' && $anak->pengguna_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus data ini'
            ], 403);
        }

        try {
            $anak->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data anak berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            \Log::error("Error deleting child data: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mencari anak berdasarkan NIK orang tua
     */
    public function findByPenggunaNik($nik)
    {
        $user = auth()->user();
        
        // Verifikasi akses - hanya admin atau pemilik NIK yang boleh mengakses
        if ($user->role !== 'admin' && $user->nik !== $nik) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data ini'
            ], 403);
        }
        
        try {
            $pengguna = Pengguna::where('nik', $nik)->first();
            
            if (!$pengguna) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna dengan NIK tersebut tidak ditemukan'
                ], 404);
            }
            
            $anakList = Anak::where('pengguna_id', $pengguna->id)->get();
            
            return response()->json([
                'success' => true,
                'data' => $anakList
            ]);
        } catch (\Exception $e) {
            \Log::error("Error finding children by parent NIK: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menghubungkan anak dengan orang tua
     */
    public function linkToParent(Request $request)
    {
        $user = auth()->user();
        
        // Log untuk debugging
        \Log::info("=== Link anak to parent ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Request data:", $request->all());
        
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'nik' => 'required|exists:pengguna,nik',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {
            $pengguna = Pengguna::where('nik', $request->nik)->first();
            $anak = Anak::find($request->anak_id);
            
            // Verifikasi akses
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya admin yang dapat menautkan data anak'
                ], 403);
            }
            
            // Verifikasi pengguna adalah parent
            if ($pengguna->role !== 'parent') {
                return response()->json([
                    'success' => false,
                    'message' => 'NIK yang diberikan bukan milik orang tua'
                ], 400);
            }
            
            $anak->pengguna_id = $pengguna->id;
            $anak->save();
            
            \Log::info("Child linked to parent successfully");
            
            return response()->json([
                'success' => true,
                'message' => 'Data anak berhasil dikaitkan dengan orang tua',
                'data' => [
                    'anak' => $anak,
                    'pengguna' => [
                        'id' => $pengguna->id,
                        'nik' => $pengguna->nik,
                        'nama' => $pengguna->nama
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error("Error linking child to parent: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaitkan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Mendapatkan anak berdasarkan ID pengguna
     * Endpoint khusus untuk mobile
     */
    public function getAnakByPenggunaId($pengguna_id)
    {
        $user = auth()->user();
        
        // Log untuk debugging
        \Log::info("=== Get anak by pengguna ID ===");
        \Log::info("User: " . ($user ? "ID: {$user->id}, NIK: {$user->nik}, Role: {$user->role}" : "Not authenticated"));
        \Log::info("Requested pengguna_id: {$pengguna_id}");
        
        // Verifikasi akses
        if ($user->role !== 'admin' && $user->id != $pengguna_id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke data ini'
            ], 403);
        }
        
        try {
            $anakList = Anak::where('pengguna_id', $pengguna_id)->get();
            \Log::info("Found {$anakList->count()} children");
            
            return response()->json([
                'success' => true,
                'data' => $anakList
            ]);
        } catch (\Exception $e) {
            \Log::error("Error finding children by pengguna ID: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 