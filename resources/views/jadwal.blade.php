@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-4 md:p-6 lg:p-8">
    <div class="max-w-6xl mx-auto relative mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="text-2xl md:text-3xl font-bold text-white drop-shadow-lg">Jadwal Posyandu</h2>
            <nav class="text-gray-700 text-sm bg-white/80 backdrop-blur-sm px-4 py-2.5 rounded-xl shadow-lg border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:text-[#63BE9A] transition-colors">Dashboard</a> / <span class="text-gray-600">Jadwal Posyandu</span>
            </nav>
        </div>
    </div>

    <div class="w-full max-w-6xl bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl p-4 md:p-6 lg:p-8 mx-auto border border-white/40">
        <!-- Tab Navigation -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-2">
                <button onclick="showTab('semua')" id="tab-semua" class="px-4 py-2.5 font-medium rounded-lg transition-all duration-300 {{ $jenis == 'semua' ? 'bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white shadow-lg scale-105' : 'bg-white/60 hover:bg-white/80 hover:shadow-md' }}">Semua Jadwal</button>
                <button onclick="showTab('pemeriksaan')" id="tab-pemeriksaan" class="px-4 py-2.5 font-medium rounded-lg transition-all duration-300 {{ $jenis == 'pemeriksaan' ? 'bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white shadow-lg scale-105' : 'bg-white/60 hover:bg-white/80 hover:shadow-md' }}">Pemeriksaan Rutin</button>
                <button onclick="showTab('imunisasi')" id="tab-imunisasi" class="px-4 py-2.5 font-medium rounded-lg transition-all duration-300 {{ $jenis == 'imunisasi' ? 'bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white shadow-lg scale-105' : 'bg-white/60 hover:bg-white/80 hover:shadow-md' }}">Imunisasi</button>
                <button onclick="showTab('vitamin')" id="tab-vitamin" class="px-4 py-2.5 font-medium rounded-lg transition-all duration-300 {{ $jenis == 'vitamin' ? 'bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white shadow-lg scale-105' : 'bg-white/60 hover:bg-white/80 hover:shadow-md' }}">Vitamin</button>
            </div>
        </div>

        <div class="bg-white/90 p-4 md:p-6 rounded-xl border border-white/40 shadow-lg">
            <!-- Action Bar -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div class="flex gap-3">
                    <button onclick="openModal()" class="bg-gradient-to-r from-green-500 to-[#63BE9A] text-white px-5 py-2.5 rounded-xl shadow-lg border border-white/40 hover:opacity-90 transition-all duration-300 hover:scale-105 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Jadwal
                    </button>
                </div>
                <form action="{{ route('jadwal') }}" method="GET" class="flex-grow md:max-w-xs">
                    <div class="flex gap-2">
                        <input type="hidden" name="jenis" id="current-tab-input" value="{{ $jenis }}">
                        <div class="relative flex-grow">
                            <input type="text" name="search" placeholder="Cari jadwal..." class="w-full p-3 pl-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 shadow-sm" value="{{ request('search') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-3 rounded-xl shadow-lg hover:opacity-90 transition-all duration-300 hover:scale-105 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('jadwal', ['jenis' => $jenis]) }}" class="bg-gray-500 text-white px-4 py-3 rounded-xl shadow-lg hover:opacity-90 transition-all duration-300 hover:scale-105 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Search Results Info -->
            @if(request('search'))
                <div class="mb-4 p-3 bg-blue-50 text-blue-700 rounded-lg border border-blue-100 shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span>Menampilkan hasil pencarian untuk: <strong>{{ request('search') }}</strong>
                    @if($jenis == 'pemeriksaan')
                        ({{ $jadwalPemeriksaan->total() }} hasil)
                    @elseif($jenis == 'imunisasi')
                        ({{ $jadwalImunisasi->total() }} hasil)
                    @elseif($jenis == 'vitamin')
                        ({{ $jadwalVitamin->total() }} hasil)
                    @else
                        ({{ $jadwalPemeriksaan->total() + $jadwalImunisasi->total() + $jadwalVitamin->total() }} hasil)
                        @endif</span>
                    </div>
                </div>
            @endif

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-100 shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    {{ session('success') }}
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg border border-red-100 shadow-sm">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Per Page Selector -->
            <div class="flex justify-end mb-4">
                <form action="{{ route('jadwal') }}" method="GET" class="flex items-center gap-2 bg-white/60 p-2 rounded-lg shadow-sm">
                    <input type="hidden" name="jenis" value="{{ $jenis }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <label for="perPage" class="text-sm text-gray-600">Tampilkan:</label>
                    <select name="perPage" id="perPage" class="border rounded-lg p-1.5 text-sm focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" onchange="this.form.submit()">
                        <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-600">data per halaman</span>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white">
                            <th class="p-3 font-semibold border-b border-white/20">No</th>
                            <th class="p-3 font-semibold border-b border-white/20">Judul</th>
                            <th class="p-3 font-semibold border-b border-white/20">Jenis</th>
                            <th class="p-3 font-semibold border-b border-white/20">Tanggal</th>
                            <th class="p-3 font-semibold border-b border-white/20">Waktu</th>
                            <th class="p-3 font-semibold border-b border-white/20">Rentang Usia</th>
                            <th class="p-3 font-semibold border-b border-white/20">Keterangan</th>
                            <th class="p-3 font-semibold border-b border-white/20">Status</th>
                            <th class="p-3 font-semibold border-b border-white/20 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($jadwal as $index => $j)
                            <tr class="hover:bg-[#63BE9A]/5 transition-colors duration-200">
                                <td class="p-3 text-gray-600">{{ $j->nomor_urut }}</td>
                                <td class="p-3 font-medium text-gray-800">{{ $j->nama }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $j->jenis == 'pemeriksaan rutin' ? 'bg-blue-100 text-blue-800' : 
                                           ($j->jenis == 'imunisasi' ? 'bg-purple-100 text-purple-800' : 
                                           'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($j->jenis) }}
                                    </span>
                                </td>
                                <td class="p-3 text-gray-600">{{ $j->tanggal }}</td>
                                <td class="p-3 text-gray-600">{{ $j->waktu }}</td>
                                <td class="p-3 text-gray-600">
                                    @if($j->jenis == 'imunisasi')
                                        {{ floor($j->min_umur_hari/30) }} - {{ floor($j->max_umur_hari/30) }} bulan
                                    @elseif($j->jenis == 'vitamin')
                                        {{ $j->min_umur_bulan ?? '-' }} - {{ $j->max_umur_bulan ?? '-' }} bulan
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-3 text-gray-600">{{ $j->keterangan ?? '-' }}</td>
                                <td class="p-3">
                                    @if($j->jenis == 'imunisasi' || $j->jenis == 'vitamin' || $j->jenis == 'pemeriksaan rutin')
                                        @if($j->is_implemented)
                                            <div class="flex flex-col md:flex-row md:items-center gap-2">
                                                <span class="bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-medium">Sudah dilaksanakan</span>
                                                <button onclick="toggleStatus('{{ $j->id }}', '{{ $j->jenis }}', '{{ $j->nama }}', false)" 
                                                    class="bg-yellow-500 text-white px-2.5 py-1 rounded-lg text-xs shadow-sm hover:opacity-90 transition-all duration-300 hover:scale-105">
                                                    Ubah ke Belum
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex flex-col md:flex-row md:items-center gap-2">
                                                <span class="bg-yellow-100 text-yellow-800 px-2.5 py-1 rounded-full text-xs font-medium">Belum dilaksanakan</span>
                                                <button onclick="toggleStatus('{{ $j->id }}', '{{ $j->jenis }}', '{{ $j->nama }}', true)" 
                                                    class="bg-green-500 text-white px-2.5 py-1 rounded-lg text-xs shadow-sm hover:opacity-90 transition-all duration-300 hover:scale-105">
                                                    Ubah ke Selesai
                                                </button>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openEditModal('{{ $j->id }}', '{{ $j->nama }}', '{{ $j->jenis }}', '{{ $j->tanggal }}', '{{ $j->waktu }}')" 
                                            class="bg-yellow-500 text-white px-3 py-1.5 rounded-lg text-xs shadow-sm hover:opacity-90 transition-all duration-300 hover:scale-105 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Edit
                                        </button>
                                        @if($j->jenis == 'pemeriksaan rutin')
                                            <form action="{{ route('jadwal.pemeriksaan.destroy', $j->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs shadow-sm hover:opacity-90 transition-all duration-300 hover:scale-105 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @elseif($j->jenis == 'imunisasi')
                                            <form action="{{ route('jadwal.imunisasi.destroy', $j->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs shadow-sm hover:opacity-90 transition-all duration-300 hover:scale-105 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @elseif($j->jenis == 'vitamin')
                                            <form action="{{ route('jadwal.vitamin.destroy', $j->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs shadow-sm hover:opacity-90 transition-all duration-300 hover:scale-105 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center bg-white/60 text-gray-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    @if(request('search'))
                                        Tidak ada jadwal yang sesuai dengan pencarian
                                    @else
                                        Tidak ada jadwal
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div class="mt-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div class="text-sm text-gray-600 bg-white/60 px-4 py-2 rounded-lg shadow-sm">
                    Menampilkan {{ $jadwal->firstItem() ?? 0 }} sampai {{ $jadwal->lastItem() ?? 0 }} dari {{ $jadwal->total() }} data
                </div>
                <div class="flex justify-center md:justify-end">
                    {{ $jadwal->appends(['search' => request('search'), 'perPage' => request('perPage') ?? 10])->links() }}
                </div>
                </div>
            </div>
        </div>

<!-- Modal Tambah Jadwal -->
<div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Tambah Jadwal Baru</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="text-center py-8">
            <p class="mb-4">Silahkan pilih jenis jadwal yang ingin Anda tambahkan:</p>
            <div class="flex flex-wrap justify-center gap-4">
                <button onclick="openModalPemeriksaan(); closeModal();" class="bg-gradient-to-r from-green-500 to-[#63BE9A] text-white px-5 py-2 rounded-xl shadow border border-white/40 hover:opacity-90 transition">Jadwal Pemeriksaan</button>
                <button onclick="openModalImunisasi(); closeModal();" class="bg-gradient-to-r from-green-500 to-[#63BE9A] text-white px-5 py-2 rounded-xl shadow border border-white/40 hover:opacity-90 transition">Jadwal Imunisasi</button>
                <button onclick="openModalVitamin(); closeModal();" class="bg-gradient-to-r from-green-500 to-[#63BE9A] text-white px-5 py-2 rounded-xl shadow border border-white/40 hover:opacity-90 transition">Jadwal Vitamin</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pemeriksaan -->
<div id="modalPemeriksaan" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Tambah Jadwal Pemeriksaan</h3>
            <button onclick="closeModalPemeriksaan()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('jadwal.pemeriksaan.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Judul*</label>
                    <input type="text" name="judul" value="Posyandu" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Judul Pemeriksaan">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Tanggal*</label>
                    <input type="date" name="tanggal" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="dd/mm/yyyy">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Waktu*</label>
                    <input type="time" name="waktu" step="60" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="--:--">
                    <small class="text-gray-500">Format: HH:MM (24 jam)</small>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-2">
                <button type="button" onclick="closeModalPemeriksaan()" class="bg-gray-300 text-black px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Batal</button>
                <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Pemeriksaan -->
<div id="modalEditPemeriksaan" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Edit Jadwal Pemeriksaan</h3>
            <button onclick="closeEditModalPemeriksaan()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="formEditPemeriksaan" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Judul*</label>
                    <input type="text" name="judul" id="editPemeriksaanJudul" value="Posyandu" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Judul Pemeriksaan">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Tanggal*</label>
                    <input type="date" name="tanggal" id="editPemeriksaanTanggal" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Waktu*</label>
                    <input type="time" name="waktu" id="editPemeriksaanWaktu" step="60" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                    <small class="text-gray-500">Format: HH:MM (24 jam)</small>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModalPemeriksaan()" class="bg-gray-300 text-black px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Batal</button>
                <button type="submit" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Imunisasi -->
<div id="modalImunisasi" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Tambah Jadwal Imunisasi</h3>
            <button onclick="closeModalImunisasi()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('jadwal.imunisasi.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Jenis Imunisasi*</label>
                    <select name="jenis_imunisasi_id" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                        <option value="">Pilih Jenis Imunisasi</option>
                        @foreach($jenisImunisasi as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Tanggal*</label>
                    <input type="date" name="tanggal" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="dd/mm/yyyy">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Waktu*</label>
                    <input type="time" name="waktu" step="60" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="--:--">
                    <small class="text-gray-500">Format: HH:MM (24 jam)</small>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-2">
                <button type="button" onclick="closeModalImunisasi()" class="bg-gray-300 text-black px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Batal</button>
                <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Vitamin -->
<div id="modalVitamin" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Tambah Jadwal Vitamin</h3>
            <button onclick="closeModalVitamin()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('jadwal.vitamin.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Jenis Vitamin*</label>
                    <select name="jenis_vitamin_id" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                        <option value="">Pilih Jenis Vitamin</option>
                        @foreach($jenisVitamin as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Tanggal*</label>
                    <input type="date" name="tanggal" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="dd/mm/yyyy">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Waktu*</label>
                    <input type="time" name="waktu" step="60" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="--:--">
                    <small class="text-gray-500">Format: HH:MM (24 jam)</small>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-2">
                <button type="button" onclick="closeModalVitamin()" class="bg-gray-300 text-black px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Batal</button>
                <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Imunisasi -->
<div id="modalEditImunisasi" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Edit Jadwal Imunisasi</h3>
            <button onclick="closeEditModalImunisasi()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="formEditImunisasi" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Jenis Imunisasi*</label>
                    <select name="jenis_imunisasi_id" id="editImunisasiJenis" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                        <option value="">Pilih Jenis Imunisasi</option>
                        @foreach($jenisImunisasi as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Tanggal*</label>
                    <input type="date" name="tanggal" id="editImunisasiTanggal" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Waktu*</label>
                    <input type="time" name="waktu" id="editImunisasiWaktu" step="60" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                    <small class="text-gray-500">Format: HH:MM (24 jam)</small>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModalImunisasi()" class="bg-gray-300 text-black px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Batal</button>
                <button type="submit" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Vitamin -->
<div id="modalEditVitamin" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Edit Jadwal Vitamin</h3>
            <button onclick="closeEditModalVitamin()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="formEditVitamin" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Jenis Vitamin*</label>
                    <select name="jenis_vitamin_id" id="editVitaminJenis" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                        <option value="">Pilih Jenis Vitamin</option>
                        @foreach($jenisVitamin as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Tanggal*</label>
                    <input type="date" name="tanggal" id="editVitaminTanggal" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Waktu*</label>
                    <input type="time" name="waktu" id="editVitaminWaktu" step="60" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300">
                    <small class="text-gray-500">Format: HH:MM (24 jam)</small>
                </div>
            </div>
            <div class="mt-8 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModalVitamin()" class="bg-gray-300 text-black px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Batal</button>
                <button type="submit" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Tab functionality
    function showTab(tabName) {
        // Update the hidden input field for current tab
        document.getElementById('current-tab-input').value = tabName;
        
        // Redirect to the page with the selected tab
        window.location.href = "{{ route('jadwal') }}?jenis=" + tabName;
    }
    
    // DOM Ready Event
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission handling for Pemeriksaan edit form
        const formEditPemeriksaan = document.getElementById('formEditPemeriksaan');
        if (formEditPemeriksaan) {
            formEditPemeriksaan.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                const action = this.action;
                
                fetch(action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`Error ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    closeEditModalPemeriksaan();
                    // Menambahkan session flash message ke localStorage
                    localStorage.setItem('flashMessage', 'Jadwal pemeriksaan berhasil diperbarui');
                    localStorage.setItem('flashType', 'success');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error updating pemeriksaan:', error);
                    alert('Gagal memperbarui jadwal pemeriksaan: ' + error.message);
                });
            });
        }
        
        // Form submission handling for Imunisasi edit form
        const formEditImunisasi = document.getElementById('formEditImunisasi');
        if (formEditImunisasi) {
            formEditImunisasi.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                const action = this.action;
                
                fetch(action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`Error ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    closeEditModalImunisasi();
                    // Menambahkan session flash message ke localStorage
                    localStorage.setItem('flashMessage', 'Jadwal imunisasi berhasil diperbarui');
                    localStorage.setItem('flashType', 'success');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error updating imunisasi:', error);
                    alert('Gagal memperbarui jadwal imunisasi: ' + error.message);
                });
            });
        }
        
        // Form submission handling for Vitamin edit form
        const formEditVitamin = document.getElementById('formEditVitamin');
        if (formEditVitamin) {
            formEditVitamin.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                const action = this.action;
                
                fetch(action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(`Error ${response.status}: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    closeEditModalVitamin();
                    // Menambahkan session flash message ke localStorage
                    localStorage.setItem('flashMessage', 'Jadwal vitamin berhasil diperbarui');
                    localStorage.setItem('flashType', 'success');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error updating vitamin:', error);
                    alert('Gagal memperbarui jadwal vitamin: ' + error.message);
                });
            });
        }
        
        // Check for flash messages in localStorage
        const flashMessage = localStorage.getItem('flashMessage');
        const flashType = localStorage.getItem('flashType');
        
        if (flashMessage) {
            // Create flash message element
            const flashDiv = document.createElement('div');
            flashDiv.className = flashType === 'success' 
                ? 'mb-4 p-4 bg-green-100 text-green-700 rounded-lg'
                : 'mb-4 p-4 bg-red-100 text-red-700 rounded-lg';
            flashDiv.textContent = flashMessage;
            
            // Find container to insert the flash message
            const container = document.querySelector('.bg-white\\/60.p-6');
            if (container) {
                // Insert after the search section
                const searchSection = container.querySelector('.flex.flex-col.md\\:flex-row.md\\:items-center.md\\:justify-between');
                if (searchSection) {
                    searchSection.insertAdjacentElement('afterend', flashDiv);
                } else {
                    container.insertAdjacentElement('afterbegin', flashDiv);
                }
            }
            
            // Clear the flash message
            localStorage.removeItem('flashMessage');
            localStorage.removeItem('flashType');
        }
    });
    
    // The "Selesai" button functionality - replaced the previous implementation and participant modals
    // When clicked, it will check existing imunisasi/vitamin records for this schedule
    // and update the implementation status accordingly
    
    function markAsComplete(id, type, title) {
        if (!confirm(`Apakah Anda yakin ingin menandai jadwal "${title}" sebagai selesai?`)) {
            return;
        }
        
        const url = type === 'imunisasi' 
            ? `{{ url('/api/test-update-imunisasi-status') }}/${id}`
            : type === 'vitamin'
                ? `{{ url('/api/test-update-vitamin-status') }}/${id}`
                : `{{ url('/api/test-update-pemeriksaan-status') }}/${id}`;
        
        // Tampilkan indikator loading
        const loadingMessage = 'Sedang memproses... Mohon tunggu.';
        alert(loadingMessage);
            
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                'is_implemented': true
            }),
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Error ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            localStorage.setItem('flashMessage', data.message || 'Status jadwal berhasil diperbarui!');
            localStorage.setItem('flashType', 'success');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error updating status:', error);
            alert('Gagal memperbarui status jadwal: ' + error.message);
        });
    }
    
    // Modal management functions
    function openModal() {
        document.getElementById('modal').style.display = 'flex';
    }
    
    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }
    
    function openModalPemeriksaan() {
        document.getElementById('modalPemeriksaan').style.display = 'flex';
    }
    
    function closeModalPemeriksaan() {
        document.getElementById('modalPemeriksaan').style.display = 'none';
    }
    
    function openModalImunisasi() {
        document.getElementById('modalImunisasi').style.display = 'flex';
    }
    
    function closeModalImunisasi() {
        document.getElementById('modalImunisasi').style.display = 'none';
    }
    
    function openModalVitamin() {
        document.getElementById('modalVitamin').style.display = 'flex';
    }
    
    function closeModalVitamin() {
        document.getElementById('modalVitamin').style.display = 'none';
    }
    
    // Edit modal functions
    function openEditModal(id, title, type, tanggal, waktu) {
        if (type === 'pemeriksaan rutin') {
            openEditModalPemeriksaan(id, title, tanggal, waktu);
        } else if (type === 'imunisasi') {
            openEditModalImunisasi(id, tanggal, waktu);
        } else if (type === 'vitamin') {
            openEditModalVitamin(id, tanggal, waktu);
        }
    }
    
    function openEditModalPemeriksaan(id, title, tanggal, waktu) {
        document.getElementById('editPemeriksaanJudul').value = title;
        document.getElementById('editPemeriksaanTanggal').value = tanggal;
        document.getElementById('editPemeriksaanWaktu').value = waktu;
        
        document.getElementById('formEditPemeriksaan').action = "{{ url('jadwal/pemeriksaan') }}/" + id;
        document.getElementById('modalEditPemeriksaan').style.display = 'flex';
    }
    
    function closeEditModalPemeriksaan() {
        document.getElementById('modalEditPemeriksaan').style.display = 'none';
    }
    
    function openEditModalImunisasi(id, tanggal, waktu) {
        // Fetch imunisasi data to get the jenis_imunisasi_id
        fetch("{{ url('jadwal/imunisasi') }}/" + id + "/edit", {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('editImunisasiJenis').value = data.jadwal.jenis_imunisasi_id;
            document.getElementById('editImunisasiTanggal').value = tanggal;
            document.getElementById('editImunisasiWaktu').value = waktu;
            
            document.getElementById('formEditImunisasi').action = "{{ url('jadwal/imunisasi') }}/" + id;
            document.getElementById('modalEditImunisasi').style.display = 'flex';
        })
        .catch(error => {
            console.error('Error fetching imunisasi data:', error);
            alert('Gagal mengambil data imunisasi: ' + error.message);
        });
    }
    
    function closeEditModalImunisasi() {
        document.getElementById('modalEditImunisasi').style.display = 'none';
    }
    
    function openEditModalVitamin(id, tanggal, waktu) {
        // Fetch vitamin data to get the jenis_vitamin_id
        fetch("{{ url('jadwal/vitamin') }}/" + id + "/edit", {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('editVitaminJenis').value = data.jadwal.jenis_vitamin_id;
            document.getElementById('editVitaminTanggal').value = tanggal;
            document.getElementById('editVitaminWaktu').value = waktu;
            
            document.getElementById('formEditVitamin').action = "{{ url('jadwal/vitamin') }}/" + id;
            document.getElementById('modalEditVitamin').style.display = 'flex';
        })
        .catch(error => {
            console.error('Error fetching vitamin data:', error);
            alert('Gagal mengambil data vitamin: ' + error.message);
        });
    }
    
    function closeEditModalVitamin() {
        document.getElementById('modalEditVitamin').style.display = 'none';
    }

    /**
     * Toggle jadwal status from implemented/not implemented
     */
    function toggleStatus(id, type, title, isImplemented) {
        const statusText = isImplemented ? "selesai" : "belum dilaksanakan";
        if (!confirm(`Apakah Anda yakin ingin mengubah status jadwal "${title}" menjadi ${statusText}?`)) {
            return;
        }
        
        const url = type === 'imunisasi' 
            ? `{{ url('/api/test-update-imunisasi-status') }}/${id}`
            : type === 'vitamin'
                ? `{{ url('/api/test-update-vitamin-status') }}/${id}`
                : `{{ url('/api/test-update-pemeriksaan-status') }}/${id}`;
        
        // Tampilkan indikator loading
        const loadingMessage = 'Sedang memproses... Mohon tunggu.';
        alert(loadingMessage);
            
        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                'is_implemented': isImplemented
            }),
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(`Error ${response.status}: ${text}`);
                });
            }
            return response.json();
        })
        .then(data => {
            localStorage.setItem('flashMessage', data.message || `Status jadwal berhasil diubah menjadi ${statusText}!`);
            localStorage.setItem('flashType', 'success');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error updating status:', error);
            alert('Gagal mengubah status jadwal: ' + error.message);
        });
    }
</script>

@endsection
