<div class="bg-gradient-to-b from-[#FFA4D3] via-[#63BE9A] to-[#06B3BF] w-64 min-h-screen p-6 shadow-xl">
    {{-- Logo Section --}}
    <div class="flex items-center justify-center mb-8">
        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-2xl shadow-lg">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Posyandu" class="w-20 h-20 object-contain">
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="space-y-3">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('dashboard') ? 'bg-[#FFA4D3]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#FFA4D3]/20 hover:text-white' }}">
            <span class="text-xl mr-3">📊</span>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <a href="{{ route('jadwal') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('jadwal') ? 'bg-[#63BE9A]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#63BE9A]/20 hover:text-white' }}">
            <span class="text-xl mr-3">📅</span>
            <span class="font-medium">Jadwal</span>
        </a>

        <a href="{{ route('petugas.index') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('petugas') ? 'bg-[#06B3BF]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#06B3BF]/20 hover:text-white' }}">
            <span class="text-xl mr-3">👮</span>
            <span class="font-medium">Petugas</span>
        </a>

        <a href="{{ route('artikel.index') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ Request::is('artikel') ? 'bg-[#FFA4D3]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#FFA4D3]/20 hover:text-white' }}">
            <span class="text-xl mr-3">📰</span>
            <span class="font-medium">Artikel</span>
        </a>

        <a href="{{ route('pengguna.index') }}" 
           class="flex items-center p-3 rounded-xl transition-all duration-300
                  {{ request()->routeIs('pengguna.*') ? 'bg-[#63BE9A]/30 backdrop-blur-sm text-white shadow-lg' : 'text-white/80 hover:bg-[#63BE9A]/20 hover:text-white' }}">
            <span class="text-xl mr-3">👥</span>
            <span class="font-medium">Data Pengguna</span>
        </a>
    </nav>
</div>
