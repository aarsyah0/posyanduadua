<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengguna = Pengguna::where('nik', $request->nik)->first();

        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NIK atau password salah',
            ], 401);
        }

        // Check if this is a mobile request (default) or explicitly stated
        $isMobile = $request->has('platform') ? $request->platform === 'mobile' : true;
        
        // Check role permissions
        if ($isMobile && $pengguna->role !== 'parent') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun ini tidak memiliki akses mobile. Silakan gunakan aplikasi web.',
            ], 403);
        }
        
        if (!$isMobile && $pengguna->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun ini tidak memiliki akses admin. Silakan gunakan aplikasi mobile.',
            ], 403);
        }

        // Load anak data for the user if parent
        if ($pengguna->role === 'parent') {
            $pengguna->load('anak');
        }
        
        // Generate token using Sanctum with role in the token name
        $tokenName = $pengguna->role . '_auth_token';
        $token = $pengguna->createToken($tokenName)->plainTextToken;

        // Prepare response data based on role
        if ($pengguna->role === 'parent') {
            // Process anak data to match mobile app expectations
            $anakData = [];
            if ($pengguna->anak && $pengguna->anak->count() > 0) {
                foreach ($pengguna->anak as $anak) {
                    $anakData[] = [
                        'id' => $anak->id,
                        'nama' => $anak->nama_anak,
                        'usia_bulan' => $this->hitungUsiaBulan($anak->tanggal_lahir),
                        'jenis_kelamin' => $anak->jenis_kelamin,
                    ];
                }
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'token' => $token,
                'pengguna' => [
                    'id' => $pengguna->id,
                    'nik' => $pengguna->nik,
                    'nama_ibu' => $pengguna->nama,
                    'alamat' => $pengguna->alamat,
                    'usia' => null,
                    'role' => $pengguna->role,
                    'anak' => $anakData,
                ],
            ]);
        } else {
            // Admin response
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'token' => $token,
                'pengguna' => [
                    'id' => $pengguna->id,
                    'nik' => $pengguna->nik,
                    'nama' => $pengguna->nama,
                    'email' => $pengguna->email,
                    'role' => $pengguna->role,
                ],
            ]);
        }
    }

    public function logout(Request $request)
    {
        // Revoke token
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil',
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
    
    public function getUser(Request $request, $nik)
    {
        $pengguna = Pengguna::where('nik', $nik)->first();
        
        if (!$pengguna) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan',
            ], 404);
        }
        
        // Check if user is parent before loading anak data
        if ($pengguna->role === 'parent') {
            // Load anak data for the user
            $pengguna->load('anak');
            
            // Process anak data to match mobile app expectations
            $anakData = [];
            if ($pengguna->anak && $pengguna->anak->count() > 0) {
                foreach ($pengguna->anak as $anak) {
                    $anakData[] = [
                        'id' => $anak->id,
                        'nama' => $anak->nama_anak,
                        'usia_bulan' => $this->hitungUsiaBulan($anak->tanggal_lahir),
                        'jenis_kelamin' => $anak->jenis_kelamin,
                    ];
                }
            }
            
            return response()->json([
                'status' => 'success',
                'pengguna' => [
                    'id' => $pengguna->id,
                    'nik' => $pengguna->nik,
                    'nama_ibu' => $pengguna->nama,
                    'alamat' => $pengguna->alamat,
                    'usia' => null,
                    'role' => $pengguna->role,
                    'anak' => $anakData,
                ],
            ]);
        } else {
            // Admin response
            return response()->json([
                'status' => 'success',
                'pengguna' => [
                    'id' => $pengguna->id,
                    'nik' => $pengguna->nik,
                    'nama' => $pengguna->nama,
                    'email' => $pengguna->email,
                    'role' => $pengguna->role,
                ],
            ]);
        }
    }
    
    /**
     * Get currently authenticated user information
     */
    public function user(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }
        
        // Check if user is parent before loading anak data
        if ($user->role === 'parent') {
            // Load anak data for the user
            $user->load('anak');
            
            // Process anak data to match mobile app expectations
            $anakData = [];
            if ($user->anak && $user->anak->count() > 0) {
                foreach ($user->anak as $anak) {
                    $anakData[] = [
                        'id' => $anak->id,
                        'nama' => $anak->nama_anak,
                        'usia_bulan' => $this->hitungUsiaBulan($anak->tanggal_lahir),
                        'jenis_kelamin' => $anak->jenis_kelamin,
                    ];
                }
            }
            
            return response()->json([
                'status' => 'success',
                'pengguna' => [
                    'id' => $user->id,
                    'nik' => $user->nik,
                    'nama_ibu' => $user->nama,
                    'alamat' => $user->alamat,
                    'no_telp' => $user->no_telp,
                    'email' => $user->email,
                    'role' => $user->role,
                    'anak' => $anakData,
                ],
            ]);
        } else {
            // Admin response
            return response()->json([
                'status' => 'success',
                'pengguna' => [
                    'id' => $user->id,
                    'nik' => $user->nik,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'no_telp' => $user->no_telp,
                    'alamat' => $user->alamat,
                    'role' => $user->role,
                ],
            ]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|unique:pengguna,nik',
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
            'password' => 'required|string|min:6',
            'email' => 'nullable|email|unique:pengguna,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengguna = Pengguna::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'role' => 'parent',
        ]);

        // Generate token for immediate login
        $tokenName = 'parent_auth_token';
        $token = $pengguna->createToken($tokenName)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Pendaftaran berhasil',
            'token' => $token,
            'pengguna' => [
                'id' => $pengguna->id,
                'nik' => $pengguna->nik,
                'nama_ibu' => $pengguna->nama,
                'alamat' => $pengguna->alamat,
                'no_telp' => $pengguna->no_telp,
                'email' => $pengguna->email,
                'role' => $pengguna->role,
            ],
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            // Enable query logging
            DB::enableQueryLog();
            
            // Debug untuk melihat ID yang diterima
            \Log::info('Attempting to update user with ID: ' . $id);
            
            // Cari user dengan ID dan log query-nya
            $user = \App\Models\Pengguna::where('id', $id)->first();
            \Log::info('SQL Query: ' . json_encode(DB::getQueryLog()));
            
            if (!$user) {
                \Log::error('User not found with ID: ' . $id . ' in table pengguna');
                // Debug: cek isi tabel
                $allUsers = DB::table('pengguna')->get();
                \Log::info('All users in database: ' . json_encode($allUsers));
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found with ID: ' . $id
                ], 404);
            }

            \Log::info('Found user: ' . json_encode($user));

            // Validasi input
            $request->validate([
                'nama_ibu' => 'required|string|max:255',
                'email' => 'required|email|unique:pengguna,email,' . $id,
                'no_telp' => 'required|string|max:15',
                'alamat' => 'required|string',
                'nik' => 'required|string|size:16|unique:pengguna,nik,' . $id,
            ]);

            // Update data user
            $updated = $user->update([
                'nama' => $request->nama_ibu, // Menggunakan 'nama' karena di model Pengguna fieldnya adalah 'nama'
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
            ]);

            \Log::info('Update result: ' . ($updated ? 'success' : 'failed'));
            \Log::info('Update query: ' . json_encode(DB::getQueryLog()));

            if (!$updated) {
                throw new \Exception('Failed to update user data');
            }

            // Get fresh data after update
            $user->refresh();

            // Load anak data if user is parent
            if ($user->role === 'parent') {
                $user->load('anak');
                
                // Process anak data to match mobile app expectations
                $anakData = [];
                if ($user->anak && $user->anak->count() > 0) {
                    foreach ($user->anak as $anak) {
                        $anakData[] = [
                            'id' => $anak->id,
                            'nama' => $anak->nama_anak,
                            'usia_bulan' => $this->hitungUsiaBulan($anak->tanggal_lahir),
                            'jenis_kelamin' => $anak->jenis_kelamin,
                        ];
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile updated successfully',
                    'pengguna' => [
                        'id' => $user->id,
                        'nik' => $user->nik,
                        'nama_ibu' => $user->nama,
                        'alamat' => $user->alamat,
                        'no_telp' => $user->no_telp,
                        'email' => $user->email,
                        'role' => $user->role,
                        'anak' => $anakData,
                    ]
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'pengguna' => [
                    'id' => $user->id,
                    'nik' => $user->nik,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'no_telp' => $user->no_telp,
                    'alamat' => $user->alamat,
                    'role' => $user->role,
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating user: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
