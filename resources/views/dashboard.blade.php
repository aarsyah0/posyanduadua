@extends('layouts.app')

@section('content')

{{-- Background Gradient --}}
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF]">

    {{-- Navbar --}}
    <div class="bg-white p-4 shadow-lg border-b">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <button class="text-black text-2xl" id="sidebarToggle">
                    ☰
                </button>
                <h1 class="text-2xl font-bold text-black">Dashboard</h1>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative" id="profileDropdown">
                    <button class="flex items-center space-x-2 text-black hover:text-gray-800 transition-colors duration-300" id="profileButton">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-gray-200">
                            <span class="text-xl">👤</span>
                        </div>
                        <span class="font-medium">Admin</span>
                    </button>
                    <style>
                        /* Override untuk menu dropdown */
                        #profileMenu {
                            background-color: white !important;
                            backdrop-filter: none !important;
                            -webkit-backdrop-filter: none !important;
                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
                        }
                        
                        #profileMenu a, 
                        #profileMenu button {
                            font-weight: bold !important;
                            color: black !important;
                        }
                        
                        /* Modal styling */
                        .modal {
                            display: none;
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background-color: rgba(0,0,0,0.5);
                            z-index: 1000;
                            justify-content: center;
                            align-items: center;
                        }
                        
                        .modal-content {
                            background-color: white;
                            border-radius: 12px;
                            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                            padding: 20px;
                            width: 90%;
                            max-width: 400px;
                            border: 1px solid #eee;
                        }
                    </style>
                    <div id="profileMenu" class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg py-2 hidden border border-gray-300 z-50">
                        <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-black hover:bg-gray-100 cursor-pointer font-bold text-base">Profil</a>
                        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="button" onclick="showLogoutConfirmation()" class="w-full text-left px-4 py-2 text-black hover:bg-gray-100 font-semibold text-base">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        {{-- Welcome Section --}}
        <div class="mb-8">
            <div class="bg-white/40 backdrop-blur-sm px-6 py-4 rounded-xl shadow-lg border border-white/30">
                <h2 class="text-xl font-semibold text-black">Selamat Datang Admin</h2>
                <p class="text-black">Selamat datang di dashboard Posyandu Mahoni 54</p>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('stunting.index') }}" class="bg-white/40 backdrop-blur-sm p-6 rounded-xl shadow-lg border border-white/30 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-black font-medium">Total Kasus Stunting</p>
                        <h3 class="text-2xl font-bold text-black">{{ $stats['total_stunting'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#FFA4D3]/40 flex items-center justify-center border border-white/30">
                        <span class="text-2xl">📊</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('pengguna.index') }}" class="bg-white/40 backdrop-blur-sm p-6 rounded-xl shadow-lg border border-white/30 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-black font-medium">Total Pengguna</p>
                        <h3 class="text-2xl font-bold text-black">{{ $stats['total_pengguna'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#63BE9A]/40 flex items-center justify-center border border-white/30">
                        <span class="text-2xl">👥</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('anak.index') }}" class="bg-white/40 backdrop-blur-sm p-6 rounded-xl shadow-lg border border-white/30 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-black font-medium">Total Data Anak</p>
                        <h3 class="text-2xl font-bold text-black">{{ $stats['total_anak'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#06B3BF]/40 flex items-center justify-center border border-white/30">
                        <span class="text-2xl">👶</span>
                    </div>
                </div>
            </a>

            <a href="{{ route('petugas.index') }}" class="bg-white/40 backdrop-blur-sm p-6 rounded-xl shadow-lg border border-white/30 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-black font-medium">Total Petugas</p>
                        <h3 class="text-2xl font-bold text-black">{{ $stats['total_petugas'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#FFA4D3]/40 flex items-center justify-center border border-white/30">
                        <span class="text-2xl">👨‍⚕️</span>
                    </div>
                </div>
            </a>
        </div>

        {{-- Menu Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @php
                $menuItems = [
                    ['image' => 'perkembangan.png', 'title' => 'Perkembangan Anak', 'route' => 'perkembangan.index'],
                    ['image' => 'imunisasi.png', 'title' => 'Imunisasi', 'route' => 'imunisasi.index'],
                    ['image' => 'ibu.png', 'title' => 'Data Orang Tua', 'route' => 'data_orangtua.index'],
                    ['image' => 'bayi.png', 'title' => 'Data Anak', 'route' => 'anak.index'],
                    ['image' => 'vitamin.png', 'title' => 'Vitamin', 'route' => 'vitamin.index'],
                    ['image' => 'stunting.png', 'title' => 'Stunting', 'route' => 'stunting.index'],
                ];
            @endphp

            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}" 
                   class="group bg-white/40 backdrop-blur-sm rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-white/30">
                    <div class="bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-4 rounded-xl inline-block mb-4 group-hover:scale-110 transition-transform duration-300 border border-white/30">
                        <img src="{{ asset('images/' . $item['image']) }}" class="w-12 h-12 object-contain">
                    </div>
                    <p class="font-semibold text-black group-hover:text-black transition-colors duration-300">{{ $item['title'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <div class="mb-4 text-center">
            <h3 class="text-lg font-bold mb-2">Konfirmasi Logout</h3>
            <p>Apakah Anda yakin ingin keluar dari sistem?</p>
        </div>
        <div class="flex justify-center space-x-4">
            <button id="cancelLogout" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition font-medium">
                Tidak
            </button>
            <button id="confirmLogout" class="px-4 py-2 bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white rounded-lg hover:opacity-90 transition font-medium">
                Ya, Logout
            </button>
        </div>
    </div>
</div>

<script>
    // Sidebar toggle functionality
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('hidden');
    });

    // Profile dropdown functionality - versi diperbaiki
    document.addEventListener('DOMContentLoaded', function() {
        const profileButton = document.getElementById('profileButton');
        const profileMenu = document.getElementById('profileMenu');
        
        // Toggle dropdown on click
        profileButton.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
            
            // Pastikan menu dropdown memiliki style yang benar
            if (!profileMenu.classList.contains('hidden')) {
                // Terapkan style ketika dropdown terbuka
                profileMenu.style.backgroundColor = 'white';
                profileMenu.style.backdropFilter = 'none';
                profileMenu.style.WebkitBackdropFilter = 'none';
                profileMenu.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
                
                // Enforce styles on menu items
                const menuItems = profileMenu.querySelectorAll('a, button');
                menuItems.forEach(item => {
                    item.style.color = 'black';
                    item.style.fontWeight = 'bold';
                });
            }
        });
        
        // Close dropdown when clicking elsewhere
        document.addEventListener('click', function(e) {
            if (!profileButton.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });
        
        // Ensure links in dropdown work properly
        const profileLinks = profileMenu.querySelectorAll('a, button');
        profileLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Pastikan link bisa di-klik dengan normal
                e.stopPropagation();
            });
        });
        
        // Logout modal functionality
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');
        const confirmLogout = document.getElementById('confirmLogout');
        
        // Close modal when clicking "No"
        cancelLogout.addEventListener('click', function() {
            logoutModal.style.display = 'none';
        });
        
        // Submit the form when clicking "Yes"
        confirmLogout.addEventListener('click', function() {
            document.getElementById('logoutForm').submit();
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.style.display = 'none';
            }
        });
    });
    
    // Show the logout confirmation modal
    function showLogoutConfirmation() {
        const modal = document.getElementById('logoutModal');
        modal.style.display = 'flex';
    }
</script>

@endsection
