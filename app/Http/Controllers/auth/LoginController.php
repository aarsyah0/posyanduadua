<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('nik', 'password');
        
        // Coba login dengan NIK
        if (Auth::attempt(['nik' => $credentials['nik'], 'password' => $credentials['password']])) {
            return redirect()->intended('dashboard');
        }

        // Jika gagal, coba login dengan email
        $user = Pengguna::where('email', $credentials['nik'])->first();
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'nik' => 'NIK/Email atau password salah.',
        ])->withInput($request->only('nik'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
