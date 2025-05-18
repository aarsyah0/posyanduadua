@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black drop-shadow-md">Profile Admin</h2>
            <nav class="text-gray-700 text-sm bg-white px-4 py-2 rounded-xl shadow-md border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span class="font-medium">Profil</span>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="max-w-5xl mx-auto mb-6 p-4 bg-white border-l-4 border-green-500 rounded-xl shadow-lg flex items-center">
            <div class="bg-green-100 p-2 rounded-full mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('info'))
        <div class="max-w-5xl mx-auto mb-6 p-4 bg-white border-l-4 border-blue-500 rounded-xl shadow-lg flex items-center">
            <div class="bg-blue-100 p-2 rounded-full mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-blue-700 font-medium">{{ session('info') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-5xl mx-auto mb-6 p-4 bg-white border-l-4 border-red-500 rounded-xl shadow-lg flex items-center">
            <div class="bg-red-100 p-2 rounded-full mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Main Profile Card -->
    <div class="w-full max-w-5xl bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-6 md:p-8 mx-auto border border-white/40">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Left column - Admin Profile Card -->
                <div class="w-full md:w-1/3">
                    <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl border border-gray-100 shadow-lg">
                        <div class="flex flex-col items-center">
                            <!-- Profile Image Container -->
                            <div class="group relative w-40 h-40 rounded-full overflow-hidden border-4 border-[#63BE9A]/30 shadow-lg mb-6 flex items-center justify-center bg-gradient-to-br from-[#63BE9A]/20 to-[#06B3BF]/20 hover:from-[#63BE9A]/30 hover:to-[#06B3BF]/30 transition duration-300">
                                <span class="text-7xl group-hover:scale-110 transition duration-300">👤</span>
                            </div>
                            
                            <!-- Name & Email -->
                            <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $profile->nama }}</h3>
                            <p class="text-gray-500 mb-3">{{ $profile->email }}</p>
                            
                            <!-- Admin Badge -->
                            <div class="px-4 py-2 bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white rounded-full text-sm font-bold shadow-md">
                                Administrator
                            </div>
                            
                            <!-- Last Login Info -->
                            <div class="mt-6 pt-4 border-t border-gray-200 w-full text-center">
                                <p class="text-sm text-gray-500">Terakhir login</p>
                                <p class="font-medium text-gray-700">{{ now()->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Change Section -->
                    <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl border border-gray-100 shadow-lg mt-6">
                        <h3 class="text-lg font-bold mb-4 text-[#06B3BF] flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Ubah Password
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Password Field -->
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <input type="password" name="password" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 shadow-sm @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Confirm Password Field -->
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full p-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 shadow-sm">
                            </div>
                            
                            <button type="submit" class="w-full mt-2 py-2 px-4 bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white rounded-xl font-medium shadow-md hover:shadow-lg hover:opacity-90 transition-all duration-300 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Password
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Right column - Profile Details Form -->
                <div class="w-full md:w-2/3">
                    <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl border border-gray-100 shadow-lg">
                        <h3 class="text-xl font-bold mb-6 text-[#06B3BF] flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Informasi Profil
                        </h3>
                        
                        <div class="space-y-5">
                            <!-- NIK Field -->
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#06B3BF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    NIK
                                </label>
                                <div class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl shadow-sm text-gray-700 font-medium">
                                    {{ $profile->nik }}
                                    <input type="hidden" name="nik" value="{{ $profile->nik }}">
                                </div>
                            </div>

                            <!-- Nama Field -->
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#06B3BF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Nama Lengkap
                                </label>
                                <div class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl shadow-sm text-gray-700 font-medium">
                                    {{ $profile->nama }}
                                    <input type="hidden" name="nama" value="{{ $profile->nama }}">
                                </div>
                            </div>
                            
                            <!-- Email Field -->
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#06B3BF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Email
                                </label>
                                <div class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl shadow-sm text-gray-700 font-medium">
                                    {{ $profile->email }}
                                    <input type="hidden" name="email" value="{{ $profile->email }}">
                                </div>
                            </div>
                            
                            <!-- Phone Field -->
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#06B3BF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Nomor Telepon
                                </label>
                                <div class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl shadow-sm text-gray-700 font-medium">
                                    {{ $profile->no_telp ?? '-' }}
                                    <input type="hidden" name="no_telp" value="{{ $profile->no_telp }}">
                                </div>
                            </div>
                            
                            <!-- Address Field -->
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#06B3BF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Alamat
                                </label>
                                <div class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl shadow-sm text-gray-700 font-medium min-h-[72px]">
                                    {{ $profile->alamat ?? '-' }}
                                    <input type="hidden" name="alamat" value="{{ $profile->alamat }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center px-6 py-3 bg-gray-600 text-white rounded-xl font-bold shadow-md hover:bg-gray-700 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Add any profile-specific JavaScript here
    document.addEventListener('DOMContentLoaded', function() {
        // Example: Adding subtle animations to form fields on focus
        const formInputs = document.querySelectorAll('input, textarea');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-[1.01]', 'transform', 'transition-all', 'duration-300');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-[1.01]', 'transform');
            });
        });
    });
</script>
@endpush

@endsection
