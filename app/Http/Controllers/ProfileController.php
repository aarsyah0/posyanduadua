<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        // Get current logged-in admin user
        $user = Auth::user();
        
        // Find the admin profile from the pengguna table
        $profile = Profile::where('id', $user->id)
                        ->where('role', 'admin')
                        ->first();
        
        // If no admin profile exists, redirect to dashboard
        if (!$profile) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil admin tidak ditemukan');
        }
        
        return view('profile', compact('profile'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        // Get current logged-in admin user
        $user = Auth::user();
        
        // Validate only password fields
        $request->validate([
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        
        // Find the admin profile
        $profile = Profile::where('id', $user->id)
                        ->where('role', 'admin')
                        ->first();
        
        // If no admin profile exists, redirect to dashboard
        if (!$profile) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil admin tidak ditemukan');
        }
        
        // Only update password if provided - other fields remain unchanged
        if ($request->filled('password')) {
            $profile->password = Hash::make($request->password);
            $profile->save();
            return redirect()->route('profile.index')
                ->with('success', 'Password berhasil diperbarui');
        }
        
        return redirect()->route('profile.index')
            ->with('info', 'Tidak ada perubahan yang dilakukan');
    }
}
