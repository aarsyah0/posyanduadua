@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <!-- Add CSRF meta tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Perkembangan Anak</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Perkembangan Anak</span>
            </nav>
        </div>
    </div>
    
    @if(isset($action) && $action == 'create')
        <!-- Form Create -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Tambah Data Perkembangan Anak</h3>
                <a href="{{ route('perkembangan.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('perkembangan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <select name="anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('anak_id') border-red-500 @enderror">
                            <option value="">-Pilih Anak-</option>
                            @foreach($dataAnak as $anak)
                                <option value="{{ $anak->id }}" {{ old('anak_id') == $anak->id ? 'selected' : '' }}>{{ $anak->nama_anak }}</option>
                            @endforeach
                        </select>
                        @error('anak_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal*</label>
                        <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal') }}">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Berat Badan (kg)*</label>
                        <input type="number" name="berat_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('berat_badan') border-red-500 @enderror" placeholder="Berat Badan" value="{{ old('berat_badan') }}">
                        @error('berat_badan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tinggi Badan (cm)*</label>
                        <input type="number" name="tinggi_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tinggi_badan') border-red-500 @enderror" placeholder="Tinggi Badan" value="{{ old('tinggi_badan') }}">
                        @error('tinggi_badan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Simpan Data</button>
                </div>
            </form>
        </div>
    @elseif(isset($action) && $action == 'edit')
        <!-- Form Edit -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Edit Data Perkembangan Anak</h3>
                <a href="{{ route('perkembangan.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('perkembangan.update', $perkembangan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <select name="anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('anak_id') border-red-500 @enderror">
                            <option value="">-Pilih Anak-</option>
                            @foreach($dataAnak as $anak)
                                <option value="{{ $anak->id }}" {{ old('anak_id', $perkembangan->anak_id) == $anak->id ? 'selected' : '' }}>{{ $anak->nama_anak }}</option>
                            @endforeach
                        </select>
                        @error('anak_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal*</label>
                        <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal', $perkembangan->tanggal->format('Y-m-d')) }}">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Berat Badan (kg)*</label>
                        <input type="number" name="berat_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('berat_badan') border-red-500 @enderror" placeholder="Berat Badan" value="{{ old('berat_badan', $perkembangan->berat_badan) }}">
                        @error('berat_badan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tinggi Badan (cm)*</label>
                        <input type="number" name="tinggi_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tinggi_badan') border-red-500 @enderror" placeholder="Tinggi Badan" value="{{ old('tinggi_badan', $perkembangan->tinggi_badan) }}">
                        @error('tinggi_badan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui Data</button>
                </div>
            </form>
        </div>
    @elseif(isset($action) && $action == 'show')
        <!-- Detail View -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Detail Data Perkembangan Anak</h3>
                <a href="{{ route('perkembangan.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm mb-4">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Anak</h3>
                        <p class="text-lg text-black">{{ $perkembangan->anak->nama_anak ?? 'Tidak ada data' }}</p>
                    </div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tanggal</h3>
                        <p class="text-lg text-black">
                            @if(isset($perkembangan->tanggal) && $perkembangan->tanggal instanceof \DateTime)
                                {{ $perkembangan->tanggal->format('d F Y') }}
                            @else
                                Tidak ada data
                            @endif
                        </p>
                    </div>
                </div>
                <div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm mb-4">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Berat Badan</h3>
                        <p class="text-lg text-black">{{ $perkembangan->berat_badan }} kg</p>
                    </div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tinggi Badan</h3>
                        <p class="text-lg text-black">{{ $perkembangan->tinggi_badan }} cm</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Terdaftar Pada</h3>
                <p class="text-lg text-black">
                    @if(isset($perkembangan->created_at) && $perkembangan->created_at instanceof \DateTime)
                        {{ $perkembangan->created_at->format('d F Y, H:i') }}
                    @else
                        Tidak ada data
                    @endif
                </p>
            </div>
            
            <div class="mt-8 flex justify-between">
                <a href="{{ route('perkembangan.edit', $perkembangan->id) }}" class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Edit Data</a>
                
                <form action="{{ route('perkembangan.destroy', $perkembangan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-gradient-to-r from-red-500 to-red-400 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Hapus Data</button>
                </form>
            </div>
        </div>
    @elseif(isset($action) && $action == 'riwayat')
        <!-- Riwayat View -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Riwayat Perkembangan: {{ $anak->nama_anak }}</h3>
                <a href="{{ route('perkembangan.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            
            <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-[#06B3BF]">Data Anak</h3>
                        <p class="text-sm text-gray-600">Nama: {{ $anak->nama_anak }}</p>
                        <p class="text-sm text-gray-600">Tanggal Lahir: {{ $anak->tanggal_lahir->format('d F Y') }}</p>
                        <p class="text-sm text-gray-600">Jenis Kelamin: {{ $anak->jenis_kelamin }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Timeline Riwayat -->
            <div class="relative">
                <div class="absolute left-4 md:left-6 top-0 h-full w-0.5 bg-[#06B3BF]/50"></div>
                
                @foreach($perkembangan as $p)
                    <div class="mb-6 ml-8 md:ml-12 relative">
                        <div class="absolute -left-8 md:-left-12 top-4 w-5 h-5 rounded-full bg-[#06B3BF] border-4 border-white"></div>
                        
                        <div class="bg-white/90 p-5 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-3">
                                <h4 class="text-lg font-semibold text-[#06B3BF]">
                                    @if(isset($p->tanggal) && $p->tanggal instanceof \DateTime)
                                        {{ $p->tanggal->format('d F Y') }}
                                    @else
                                        Tanggal tidak valid
                                    @endif
                                </h4>
                                <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">
                                    @if(isset($p->created_at) && $p->created_at instanceof \DateTime)
                                        {{ $p->created_at->diffForHumans() }}
                                    @endif
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Berat Badan</p>
                                        <p class="font-semibold text-lg">{{ $p->berat_badan ?? '-' }} kg</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <div class="w-12 h-12 flex items-center justify-center bg-green-100 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Tinggi Badan</p>
                                        <p class="font-semibold text-lg">{{ $p->tinggi_badan ?? '-' }} cm</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 flex justify-end space-x-2">
                                <a href="javascript:void(0)" onclick="fetchDetail({{ $p->id }})" class="text-blue-500 hover:underline text-sm">Detail</a>
                                <a href="javascript:void(0)" onclick="fetchEdit({{ $p->id }})" class="text-yellow-500 hover:underline text-sm">Edit</a>
                                <a href="{{ route('perkembangan.riwayat', $p->anak_id) }}" class="text-purple-500 hover:underline text-sm">Riwayat</a>
                                <form action="{{ url('/perkembangan/'.$p->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <!-- Index View (List) -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="bg-white/60 p-6 rounded-xl border border-white/40">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">
                    <div class="flex gap-2">
                        <a href="javascript:void(0)" onclick="showTambahForm()" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Tambah</a>
                        <a href="#" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Cetak</a>
                    </div>
                    <form action="{{ route('perkembangan.index') }}" method="GET" class="flex-grow md:max-w-xs">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('perkembangan.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(isset($search) && $search)
                    <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded-lg">
                        Menampilkan hasil pencarian untuk: <strong>{{ $search }}</strong> ({{ $perkembangan->total() }} hasil)
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Row Page Selector -->
                <div class="flex justify-end mb-4">
                    <form action="{{ route('perkembangan.index') }}" method="GET" class="flex items-center">
                        <input type="hidden" name="search" value="{{ $search ?? '' }}">
                        <label for="perPage" class="mr-2 text-sm">Tampilkan:</label>
                        <select name="perPage" id="perPage" class="border rounded p-1 text-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="ml-2 text-sm">data per halaman</span>
                    </form>
                </div>

                <!-- Wrapper untuk Scroll Horizontal -->
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] border-collapse border border-gray-300 text-left rounded-xl overflow-hidden">
                        <thead class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white">
                            <tr>
                                <th class="border border-gray-300 p-3 w-16">No</th>
                                <th class="border border-gray-300 p-3">Nama Anak</th>
                                <th class="border border-gray-300 p-3">Tanggal</th>
                                <th class="border border-gray-300 p-3">Berat Badan</th>
                                <th class="border border-gray-300 p-3">Tinggi Badan</th>
                                <th class="border border-gray-300 p-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($perkembangan as $index => $p)
                                {{-- Debugging: Tampilkan ID perkembangan --}}
                                @php
                                    Log::info('Processing perkembangan ID: ' . ($p->id ?? 'NULL'));
                                @endphp
                                <tr class="hover:bg-[#63BE9A]/10 transition">
                                    <td class="border border-gray-300 p-3">{{ $loop->index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->nama_anak ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">
                                        @if(isset($p->tanggal) && $p->tanggal instanceof \DateTime)
                                            {{ $p->tanggal->format('d-m-Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-3">{{ $p->berat_badan ?? '-' }} kg</td>
                                    <td class="border border-gray-300 p-3">{{ $p->tinggi_badan ?? '-' }} cm</td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            @if($p->id)
                                                <a href="javascript:void(0)" onclick="fetchDetail({{ $p->id }})" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</a>
                                                <a href="javascript:void(0)" onclick="fetchEdit({{ $p->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Edit</a>
                                                <a href="{{ route('perkembangan.riwayat', $p->anak_id) }}" class="bg-purple-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Riwayat</a>
                                                <form action="{{ url('/perkembangan/'.$p->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Hapus</button>
                                                </form>
                                            @else
                                                <span class="text-red-500">ID tidak valid</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border border-gray-300 p-3 text-center bg-white/60">
                                        @if(isset($search) && $search)
                                            Tidak ada data perkembangan anak yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data perkembangan anak
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($perkembangan) > 0 && count($perkembangan) < 4)
                                @for($i = 0; $i < 4 - count($perkembangan); $i++)
                                    <tr>
                                        <td class="border border-gray-300 p-3 h-12 bg-white/60" colspan="6"></td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <div class="text-sm text-gray-600">
                        @if($perkembangan->count() > 0)
                            Menampilkan {{ $perkembangan->firstItem() ?? 0 }} sampai {{ $perkembangan->lastItem() ?? 0 }} dari {{ $perkembangan->total() }} data
                        @else
                            Tidak ada data yang ditampilkan
                        @endif
                    </div>
                    <div>
                        {{ $perkembangan->appends(['search' => $search ?? '', 'perPage' => request('perPage') ?? 10])->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal untuk Detail -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Detail Perkembangan Anak</h3>
                    <button onclick="closeModal('detailModal')" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto max-h-[70vh]">
                    <div class="space-y-4 mb-6">
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Anak</h3>
                            <p id="detail_nama_anak" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tanggal</h3>
                            <p id="detail_tanggal" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Berat Badan</h3>
                            <p id="detail_berat_badan" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tinggi Badan</h3>
                            <p id="detail_tinggi_badan" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Terdaftar Pada</h3>
                            <p id="detail_terdaftar" class="text-lg text-black">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal untuk Edit -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Edit Data Perkembangan Anak</h3>
                    <button onclick="closeModal('editModal')" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                            <select name="anak_id" id="edit_anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                                <option value="">-Pilih Anak-</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Tanggal*</label>
                            <input type="date" name="tanggal" id="edit_tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Berat Badan (kg)*</label>
                            <input type="number" name="berat_badan" id="edit_berat_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Berat Badan">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Tinggi Badan (cm)*</label>
                            <input type="number" name="tinggi_badan" id="edit_tinggi_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Tinggi Badan">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Modal untuk Tambah Data -->
        <div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Tambah Data Perkembangan Anak</h3>
                    <button onclick="closeModal('tambahModal')" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="tambahForm" action="{{ route('perkembangan.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                            <select name="anak_id" id="tambah_anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                                <option value="">-Pilih Anak-</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Tanggal*</label>
                            <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Berat Badan (kg)*</label>
                            <input type="number" name="berat_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Berat Badan">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-black">Tinggi Badan (cm)*</label>
                            <input type="number" name="tinggi_badan" step="0.01" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Tinggi Badan">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

@if(!isset($action))
<script>
// Fungsi untuk membuka modal
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
    document.body.style.overflow = '';
}

// Fungsi untuk fetch data detail
function fetchDetail(id) {
    // Tampilkan loading state
    document.getElementById('detail_nama_anak').textContent = 'Loading...';
    document.getElementById('detail_tanggal').textContent = 'Loading...';
    document.getElementById('detail_berat_badan').textContent = 'Loading...';
    document.getElementById('detail_tinggi_badan').textContent = 'Loading...';
    document.getElementById('detail_terdaftar').textContent = 'Loading...';
    
    // Buka modal
    openModal('detailModal');
    
    // Ambil data
    fetch(`/perkembangan/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Format tanggal
            const tanggal = new Date(data.perkembangan.tanggal);
            const formattedTanggal = tanggal.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            
            const createdAt = new Date(data.perkembangan.created_at);
            const formattedCreatedAt = createdAt.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Isi data
            document.getElementById('detail_nama_anak').textContent = data.anak ? data.anak.nama_anak : '-';
            document.getElementById('detail_tanggal').textContent = formattedTanggal;
            document.getElementById('detail_berat_badan').textContent = `${data.perkembangan.berat_badan} kg`;
            document.getElementById('detail_tinggi_badan').textContent = `${data.perkembangan.tinggi_badan} cm`;
            document.getElementById('detail_terdaftar').textContent = formattedCreatedAt;
        } else {
            alert('Gagal mengambil data detail');
            closeModal('detailModal');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data');
        closeModal('detailModal');
    });
}

// Fungsi untuk fetch data edit
function fetchEdit(id) {
    // Buka modal
    openModal('editModal');
    
    // Ambil data
    fetch(`/perkembangan/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Set form action
        document.getElementById('editForm').action = `/perkembangan/${id}`;
        
        // Clear existing options except the first one
        const anakSelect = document.getElementById('edit_anak_id');
        anakSelect.innerHTML = '<option value="">-Pilih Anak-</option>';
        
        // Populate anak options
        if (data.dataAnak && data.dataAnak.length > 0) {
            data.dataAnak.forEach(anak => {
                const option = document.createElement('option');
                option.value = anak.id;
                option.textContent = anak.nama_anak;
                if (anak.id === data.perkembangan.anak_id) {
                    option.selected = true;
                }
                anakSelect.appendChild(option);
            });
        }
        
        // Set other form values
        if (typeof data.perkembangan.tanggal === 'string') {
            document.getElementById('edit_tanggal').value = data.perkembangan.tanggal.split('T')[0];
        }
        document.getElementById('edit_berat_badan').value = data.perkembangan.berat_badan;
        document.getElementById('edit_tinggi_badan').value = data.perkembangan.tinggi_badan;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data');
        closeModal('editModal');
    });
}

// Fungsi untuk menampilkan form tambah
function showTambahForm() {
    // Buka modal
    openModal('tambahModal');
    
    // Ambil data anak untuk dropdown
    fetch('/api/anak', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Clear existing options except the first one
        const anakSelect = document.getElementById('tambah_anak_id');
        anakSelect.innerHTML = '<option value="">-Pilih Anak-</option>';
        
        // Populate anak options
        if (data && data.length > 0) {
            data.forEach(anak => {
                const option = document.createElement('option');
                option.value = anak.id;
                option.textContent = anak.nama_anak;
                anakSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data anak');
    });
}

// Event listener untuk tombol
document.addEventListener('DOMContentLoaded', function() {
    // Ganti link tambah dengan tombol yang membuka modal
    const tambahBtn = document.querySelector('a[href="{{ route("perkembangan.create") }}"]');
    if (tambahBtn) {
        tambahBtn.href = 'javascript:void(0)';
        tambahBtn.addEventListener('click', function(e) {
            e.preventDefault();
            showTambahForm();
        });
    }
});

// Close modal ketika klik di luar modal
window.addEventListener('click', function(event) {
    const detailModal = document.getElementById('detailModal');
    const editModal = document.getElementById('editModal');
    const tambahModal = document.getElementById('tambahModal');
    
    if (event.target === detailModal) closeModal('detailModal');
    if (event.target === editModal) closeModal('editModal');
    if (event.target === tambahModal) closeModal('tambahModal');
});

// Close modal dengan escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal('detailModal');
        closeModal('editModal');
        closeModal('tambahModal');
    }
});

// Submit form handler
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Data berhasil diperbarui');
            closeModal('editModal');
            window.location.reload();
        } else {
            alert('Gagal memperbarui data: ' + (result.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui data');
    });
});

document.getElementById('tambahForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Data berhasil disimpan');
            closeModal('tambahModal');
            window.location.reload();
        } else {
            alert('Gagal menyimpan data: ' + (result.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
});
</script>
@endif

@endsection
