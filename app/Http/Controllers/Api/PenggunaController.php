<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function findByNik($nik)
    {
        $pengguna = Pengguna::where('nik', $nik)->first();
        
        if (!$pengguna) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $pengguna
        ]);
    }
} 