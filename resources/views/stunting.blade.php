@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <!-- Add CSRF meta tag for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Stunting</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Stunting</span>
            </nav>
        </div>
    </div>
    
    @if(isset($action) && $action == 'create')
        <!-- Form Create -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Tambah Data Stunting</h3>
                <a href="{{ route('stunting.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('stunting.store') }}" method="POST" id="createForm">
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
                        <label class="block text-sm font-semibold text-black">Data Perkembangan*</label>
                        <select name="perkembangan_id" id="create_perkembangan_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('perkembangan_id') border-red-500 @enderror">
                            <option value="">-Pilih Data Perkembangan-</option>
                            @foreach($dataPerkembangan as $perkembangan)
                                <option 
                                    value="{{ $perkembangan->id }}" 
                                    data-tinggi="{{ $perkembangan->tinggi_badan }}"
                                    data-berat="{{ $perkembangan->berat_badan }}"
                                    {{ old('perkembangan_id') == $perkembangan->id ? 'selected' : '' }}>
                                    {{ $perkembangan->anak->nama_anak ?? 'Anak' }} - {{ $perkembangan->tanggal->format('d/m/Y') }} ({{ $perkembangan->berat_badan }}kg, {{ $perkembangan->tinggi_badan }}cm)
                                </option>
                            @endforeach
                        </select>
                        @error('perkembangan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal Pemeriksaan*</label>
                        <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal') }}">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Usia*</label>
                        <input type="text" name="usia" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('usia') border-red-500 @enderror" placeholder="Contoh: 2 tahun" value="{{ old('usia') }}">
                        @error('usia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Status*</label>
                        <select name="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('status') border-red-500 @enderror">
                            <option value="">-Pilih-</option>
                            <option value="Stunting" {{ old('status') == 'Stunting' ? 'selected' : '' }}>Stunting</option>
                            <option value="Tidak Stunting" {{ old('status') == 'Tidak Stunting' ? 'selected' : '' }}>Tidak Stunting</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Keterangan</label>
                        <textarea name="catatan" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('catatan') border-red-500 @enderror" placeholder="Keterangan" rows="3">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Hidden fields for tinggi and berat badan -->
                    <input type="hidden" name="tinggi_badan" value="0">
                    <input type="hidden" name="berat_badan" value="0">
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Simpan Data</button>
                </div>
            </form>
            
            <script>
                // Menangani perubahan pada select perkembangan untuk mengupdate nilai tinggi dan berat badan
                document.addEventListener('DOMContentLoaded', function() {
                    const perkembanganSelect = document.getElementById('create_perkembangan_id');
                    const tinggiBadanInput = document.querySelector('input[name="tinggi_badan"]');
                    const beratBadanInput = document.querySelector('input[name="berat_badan"]');
                    
                    // Update nilai awal jika sudah ada perkembangan yang dipilih
                    if (perkembanganSelect.selectedIndex > 0) {
                        const selectedOption = perkembanganSelect.options[perkembanganSelect.selectedIndex];
                        tinggiBadanInput.value = selectedOption.dataset.tinggi || '0';
                        beratBadanInput.value = selectedOption.dataset.berat || '0';
                    }
                    
                    // Event listener untuk perubahan select
                    perkembanganSelect.addEventListener('change', function() {
                        if (this.selectedIndex > 0) {
                            const selectedOption = this.options[this.selectedIndex];
                            tinggiBadanInput.value = selectedOption.dataset.tinggi || '0';
                            beratBadanInput.value = selectedOption.dataset.berat || '0';
                        } else {
                            tinggiBadanInput.value = '0';
                            beratBadanInput.value = '0';
                        }
                    });
                });
            </script>
        </div>
    @elseif(isset($action) && $action == 'edit')
        <!-- Form Edit -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Edit Data Stunting</h3>
                <a href="{{ route('stunting.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('stunting.update', $stunting->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <select name="anak_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('anak_id') border-red-500 @enderror">
                            <option value="">-Pilih Anak-</option>
                            @foreach($dataAnak as $anak)
                                <option value="{{ $anak->id }}" {{ old('anak_id', $stunting->anak_id) == $anak->id ? 'selected' : '' }}>{{ $anak->nama_anak }}</option>
                            @endforeach
                        </select>
                        @error('anak_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Data Perkembangan*</label>
                        <select name="perkembangan_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('perkembangan_id') border-red-500 @enderror">
                            <option value="">-Pilih Data Perkembangan-</option>
                            @foreach($dataPerkembangan as $perkembangan)
                                <option value="{{ $perkembangan->id }}" {{ old('perkembangan_id', $stunting->perkembangan_id) == $perkembangan->id ? 'selected' : '' }}>
                                    {{ $perkembangan->anak->nama_anak ?? 'Anak' }} - {{ $perkembangan->tanggal->format('d/m/Y') }} ({{ $perkembangan->berat_badan }}kg, {{ $perkembangan->tinggi_badan }}cm)
                                </option>
                            @endforeach
                        </select>
                        @error('perkembangan_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal Pemeriksaan*</label>
                        <input type="date" name="tanggal" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal', $stunting->tanggal ? $stunting->tanggal->format('Y-m-d') : '') }}">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Usia*</label>
                        <input type="text" name="usia" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('usia') border-red-500 @enderror" placeholder="Contoh: 2 tahun" value="{{ old('usia', $stunting->usia) }}">
                        @error('usia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Status*</label>
                        <select name="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('status') border-red-500 @enderror">
                            <option value="">-Pilih-</option>
                            <option value="Stunting" {{ old('status', $stunting->status) == 'Stunting' ? 'selected' : '' }}>Stunting</option>
                            <option value="Tidak Stunting" {{ old('status', $stunting->status) == 'Tidak Stunting' ? 'selected' : '' }}>Tidak Stunting</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Keterangan</label>
                        <textarea name="catatan" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('catatan') border-red-500 @enderror" placeholder="Keterangan" rows="3">{{ old('catatan', $stunting->catatan) }}</textarea>
                        @error('catatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-6 py-3 rounded-xl font-semibold shadow hover:opacity-90 transition">Perbarui Data</button>
                </div>
            </form>
        </div>
    @else
        <!-- Index View (List) -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="bg-white/60 p-6 rounded-xl border border-white/40">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">
                    <div class="flex gap-2">
                        <a href="#" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Cetak</a>
                    </div>
                    <form action="{{ route('stunting.index') }}" method="GET" class="flex-grow md:max-w-xs">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('stunting.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(isset($search) && $search)
                    <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded-lg">
                        Menampilkan hasil pencarian untuk: <strong>{{ $search }}</strong> ({{ $stunting->total() }} hasil)
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Row Page Selector -->
                <div class="flex justify-end mb-4">
                    <form action="{{ route('stunting.index') }}" method="GET" class="flex items-center">
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

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] border-collapse border border-gray-300 text-left rounded-xl overflow-hidden">
                        <thead class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white">
                            <tr>
                                <th class="border border-gray-300 p-3 w-16">No</th>
                                <th class="border border-gray-300 p-3">Nama Anak</th>
                                <th class="border border-gray-300 p-3">Tanggal Pemeriksaan</th>
                                <th class="border border-gray-300 p-3">Tinggi Badan</th>
                                <th class="border border-gray-300 p-3">Berat Badan</th>
                                <th class="border border-gray-300 p-3">Status</th>
                                <th class="border border-gray-300 p-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($stunting as $index => $s)
                                <tr class="hover:bg-[#63BE9A]/10 transition">
                                    <td class="border border-gray-300 p-3">{{ ($stunting->currentPage() - 1) * $stunting->perPage() + $index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">{{ $s->anak->nama_anak ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $s->tanggal ? $s->tanggal->format('d F Y') : '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $s->perkembangan->tinggi_badan ?? '-' }} cm</td>
                                    <td class="border border-gray-300 p-3">{{ $s->perkembangan->berat_badan ?? '-' }} kg</td>
                                    <td class="border border-gray-300 p-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($s->status == 'Tidak Stunting') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $s->status }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="fetchStuntingDetail({{ $s->id }})" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</button>
                                            <form action="{{ route('stunting.destroy', $s->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="border border-gray-300 p-3 text-center bg-white/60">
                                        @if(isset($search) && $search)
                                            Tidak ada data stunting yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data stunting
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($stunting) > 0 && count($stunting) < 4)
                                @for($i = 0; $i < 4 - count($stunting); $i++)
                                    <tr>
                                        <td class="border border-gray-300 p-3 h-12 bg-white/60" colspan="7"></td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $stunting->firstItem() ?? 0 }} sampai {{ $stunting->lastItem() ?? 0 }} dari {{ $stunting->total() }} data
                    </div>
                    <div>
                        {{ $stunting->appends(['search' => $search ?? '', 'perPage' => request('perPage') ?? 10])->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Modal -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Detail Data Stunting</h3>
                    <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
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
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tanggal Pemeriksaan</h3>
                            <p id="detail_tanggal" class="text-lg text-black">Loading...</p>
                        </div>
                        
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Usia Saat Pemeriksaan</h3>
                            <p id="detail_usia" class="text-lg text-black">Loading...</p>
                        </div>
                        
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tinggi Badan</h3>
                            <p id="detail_tinggi_badan" class="text-lg text-black">Loading...</p>
                        </div>
                        
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Berat Badan</h3>
                            <p id="detail_berat_badan" class="text-lg text-black">Loading...</p>
                        </div>
                        
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Status Stunting</h3>
                            <p id="detail_status" class="text-lg text-black font-bold">Loading...</p>
                        </div>
                        
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Keterangan</h3>
                            <p id="detail_catatan" class="text-lg text-black">Loading...</p>
                        </div>
                        
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Terdaftar Pada</h3>
                            <p id="detail_terdaftar" class="text-lg text-black">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if(!isset($action))
<script>
function openDetailModal() {
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailModal').classList.add('flex');
    document.body.style.overflow = 'hidden'; // Disable scrolling
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
    document.getElementById('detailModal').classList.remove('flex');
    document.body.style.overflow = ''; // Re-enable scrolling
}

function fetchStuntingDetail(id) {
    // Show loading state
    document.getElementById('detail_nama_anak').textContent = 'Loading...';
    document.getElementById('detail_tanggal').textContent = 'Loading...';
    document.getElementById('detail_usia').textContent = 'Loading...';
    document.getElementById('detail_tinggi_badan').textContent = 'Loading...';
    document.getElementById('detail_berat_badan').textContent = 'Loading...';
    document.getElementById('detail_status').textContent = 'Loading...';
    document.getElementById('detail_catatan').textContent = 'Loading...';
    document.getElementById('detail_terdaftar').textContent = 'Loading...';
    
    // Open modal
    openDetailModal();
    
    // Fetch data
    fetch(`{{ url('/stunting') }}/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Format date
        let formattedTanggal = '-';
        if (data.stunting && data.stunting.tanggal) {
            const tanggal = new Date(data.stunting.tanggal);
            formattedTanggal = tanggal.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
        
        let formattedCreatedAt = '-';
        if (data.stunting && data.stunting.created_at) {
            const createdAt = new Date(data.stunting.created_at);
            formattedCreatedAt = createdAt.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        // Format status dengan label yang sesuai
        let statusText = data.stunting ? data.stunting.status : '-';
        let statusClass = '';
        
        if (statusText === 'Stunting') {
            statusClass = 'text-red-600';
        } else if (statusText === 'Tidak Stunting') {
            statusClass = 'text-green-600';
        }
        
        // Selalu ambil tinggi badan dan berat badan dari data perkembangan yang terelasi
        let tinggiBadan = '-';
        let beratBadan = '-';
        
        if (data.perkembangan) {
            tinggiBadan = data.perkembangan.tinggi_badan ? `${data.perkembangan.tinggi_badan} cm` : '-';
            beratBadan = data.perkembangan.berat_badan ? `${data.perkembangan.berat_badan} kg` : '-';
        }
        
        // Populate data safely
        document.getElementById('detail_nama_anak').textContent = data.anak ? data.anak.nama_anak : '-';
        document.getElementById('detail_tanggal').textContent = formattedTanggal;
        document.getElementById('detail_usia').textContent = data.stunting && data.stunting.usia ? data.stunting.usia : '-';
        document.getElementById('detail_tinggi_badan').textContent = tinggiBadan;
        document.getElementById('detail_berat_badan').textContent = beratBadan;
        
        // Set status dengan warna yang sesuai
        const statusElement = document.getElementById('detail_status');
        statusElement.textContent = statusText;
        statusElement.className = `text-lg font-bold ${statusClass}`;
        
        document.getElementById('detail_catatan').textContent = data.stunting && data.stunting.catatan ? data.stunting.catatan : '-';
        document.getElementById('detail_terdaftar').textContent = formattedCreatedAt;
    })
    .catch(error => {
        console.error('Error fetching data:', error);
        closeDetailModal();
        alert('Terjadi kesalahan saat mengambil data');
    });
}

// Close modal when clicking outside of it
window.addEventListener('click', function(event) {
    const modal = document.getElementById('detailModal');
    if (event.target === modal) {
        closeDetailModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('detailModal').classList.contains('hidden')) {
            closeDetailModal();
        }
    }
});
</script>
@endif

@endsection
