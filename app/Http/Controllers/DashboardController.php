<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $dashboardService;
    
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    
    public function index()
    {
        try {
            // Get dashboard statistics using the service
            $stats = $this->dashboardService->getDashboardStats();
        } catch (Exception $e) {
            // Log the error
            Log::error('Dashboard stats error: ' . $e->getMessage());
            
            // Provide default values if there's an error
            $stats = [
                'total_stunting' => 0,
                'total_pengguna' => 0,
                'total_anak' => 0,
                'total_petugas' => 0
            ];
        }
        
        return view('dashboard', compact('stats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|numeric|digits:16|unique:pengguna,nik',
            'nama_ibu' => 'required|string|max:100',
            'nama_anak' => 'required|string|max:100',
            'no_telp' => 'nullable|numeric|digits_between:1,15',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'usia' => 'required|string|max:50',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        Pengguna::create($data);

        return redirect()->back()->with('success', 'Data pengguna berhasil ditambahkan!');
    }
}
