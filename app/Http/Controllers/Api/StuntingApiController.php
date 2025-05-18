<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stunting;
use App\Models\Anak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StuntingApiController extends Controller
{
    /**
     * Get all stunting records for a specific child
     */
    public function getByAnakId($anak_id)
    {
        $anak = Anak::find($anak_id);
        
        if (!$anak) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan',
            ], 404);
        }
        
        $stunting = Stunting::with('perkembangan')
            ->where('anak_id', $anak_id)
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return response()->json([
            'status' => 'success',
            'stunting' => $stunting,
        ]);
    }
    
    /**
     * Get a specific stunting record
     */
    public function show($id)
    {
        $stunting = Stunting::with('perkembangan')->find($id);
        
        if (!$stunting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data stunting tidak ditemukan',
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'stunting' => $stunting,
        ]);
    }
    
    /**
     * Create a new stunting record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'anak_id' => 'required|exists:anak,id',
            'tanggal' => 'required|date',
            'usia' => 'required|string',
            'catatan' => 'nullable|string',
            'status' => 'required|in:Stunting,Tidak Stunting',
            'perkembangan_id' => 'required|exists:perkembangan_anak,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $stunting = Stunting::create($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data stunting berhasil ditambahkan',
            'stunting' => $stunting,
        ], 201);
    }
    
    /**
     * Update a stunting record
     */
    public function update(Request $request, $id)
    {
        $stunting = Stunting::find($id);
        
        if (!$stunting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data stunting tidak ditemukan',
            ], 404);
        }
        
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'usia' => 'required|string',
            'catatan' => 'nullable|string',
            'status' => 'required|in:Stunting,Tidak Stunting',
            'perkembangan_id' => 'required|exists:perkembangan_anak,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $stunting->update($request->all());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data stunting berhasil diperbarui',
            'stunting' => $stunting,
        ]);
    }
    
    /**
     * Delete a stunting record
     */
    public function destroy($id)
    {
        $stunting = Stunting::find($id);
        
        if (!$stunting) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data stunting tidak ditemukan',
            ], 404);
        }
        
        $stunting->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data stunting berhasil dihapus',
        ]);
    }
} 