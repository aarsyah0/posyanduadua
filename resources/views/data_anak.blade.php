@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Anak</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Anak</span>
            </nav>
        </div>
    </div>
    @if(isset($action) && $action == 'create')
        <!-- Form Create -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Tambah Data Anak</h3>
                <a href="{{ route('anak.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('anak.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Orang Tua*</label>
                        <select name="pengguna_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('pengguna_id') border-red-500 @enderror">
                            <option value="">-Pilih Orang Tua-</option>
                            @foreach($orangtua as $parent)
                                <option value="{{ $parent->id }}" {{ old('pengguna_id') == $parent->id ? 'selected' : '' }}>{{ $parent->nama }} ({{ $parent->nik }})</option>
                            @endforeach
                        </select>
                        @error('pengguna_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <input type="text" name="nama_anak" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama_anak') border-red-500 @enderror" placeholder="Nama Anak" value="{{ old('nama_anak') }}">
                        @error('nama_anak')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tempat Lahir*</label>
                        <input type="text" name="tempat_lahir" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tempat_lahir') border-red-500 @enderror" placeholder="Tempat Lahir" value="{{ old('tempat_lahir') }}">
                        @error('tempat_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal Lahir*</label>
                        <input type="date" name="tanggal_lahir" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal_lahir') border-red-500 @enderror" value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Usia*</label>
                        <input type="text" name="usia" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('usia') border-red-500 @enderror" placeholder="Usia" value="{{ old('usia') }}" maxlength="10">
                        @error('usia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Jenis Kelamin*</label>
                        <select name="jenis_kelamin" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">-Pilih-</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
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
                <h3 class="text-2xl font-bold text-black">Edit Data Anak</h3>
                <a href="{{ route('anak.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('anak.update', $anak->nik) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Orang Tua*</label>
                        <select name="pengguna_id" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('pengguna_id') border-red-500 @enderror">
                            <option value="">-Pilih Orang Tua-</option>
                            @foreach($orangtua as $parent)
                                <option value="{{ $parent->id }}" {{ old('pengguna_id', $anak->pengguna_id) == $parent->id ? 'selected' : '' }}>{{ $parent->nama }} ({{ $parent->nik }})</option>
                            @endforeach
                        </select>
                        @error('pengguna_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <input type="text" name="nama_anak" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama_anak') border-red-500 @enderror" placeholder="Nama Anak" value="{{ old('nama_anak', $anak->nama_anak) }}">
                        @error('nama_anak')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tempat Lahir*</label>
                        <input type="text" name="tempat_lahir" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tempat_lahir') border-red-500 @enderror" placeholder="Tempat Lahir" value="{{ old('tempat_lahir', $anak->tempat_lahir) }}">
                        @error('tempat_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal Lahir*</label>
                        <input type="date" name="tanggal_lahir" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal_lahir') border-red-500 @enderror" value="{{ old('tanggal_lahir', $anak->tanggal_lahir->format('Y-m-d')) }}">
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Usia*</label>
                        <input type="text" name="usia" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('usia') border-red-500 @enderror" placeholder="Contoh: 2 bulan" value="{{ old('usia', $anak->usia) }}" maxlength="10">
                        @error('usia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Jenis Kelamin*</label>
                        <select name="jenis_kelamin" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">-Pilih-</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $anak->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $anak->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
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
                    <form action="{{ route('anak.index') }}" method="GET" class="flex-grow md:max-w-xs">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('anak.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
                @if(isset($search) && $search)
                    <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded-lg">
                        Menampilkan hasil pencarian untuk: <strong>{{ $search }}</strong> ({{ $anak->total() }} hasil)
                    </div>
                @endif
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="flex justify-end mb-4">
                    <form action="{{ route('anak.index') }}" method="GET" class="flex items-center">
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
                                <th class="border border-gray-300 p-3">NIK</th>
                                <th class="border border-gray-300 p-3">Nama Ibu</th>
                                <th class="border border-gray-300 p-3">Nama Anak</th>
                                <th class="border border-gray-300 p-3">Tempat Lahir</th>
                                <th class="border border-gray-300 p-3">Tanggal Lahir</th>
                                <th class="border border-gray-300 p-3">Usia</th>
                                <th class="border border-gray-300 p-3">Jenis Kelamin</th>
                                <th class="border border-gray-300 p-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($anak as $index => $a)
                                <tr class="hover:bg-[#63BE9A]/10 transition">
                                    <td class="border border-gray-300 p-3">{{ ($anak->currentPage() - 1) * $anak->perPage() + $index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">{{ $a->pengguna->nik ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $a->pengguna->nama ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $a->nama_anak }}</td>
                                    <td class="border border-gray-300 p-3">{{ $a->tempat_lahir }}</td>
                                    <td class="border border-gray-300 p-3">{{ $a->tanggal_lahir ? $a->tanggal_lahir->format('d F Y') : '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $a->usia }}</td>
                                    <td class="border border-gray-300 p-3">{{ $a->jenis_kelamin }}</td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="fetchAnakDetail({{ $a->id }})" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</button>
                                            @if(!$a->pengguna_id)
                                            <button onclick="openLinkModal({{ $a->id }}, '{{ $a->nama_anak }}')" class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Kaitkan</button>
                                            @endif
                                            <form action="{{ route('anak.destroy', $a->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="border border-gray-300 p-3 text-center bg-white/60">
                                        @if(isset($search) && $search)
                                            Tidak ada data anak yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data anak
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($anak) > 0 && count($anak) < 4)
                                @for($i = 0; $i < 4 - count($anak); $i++)
                                    <tr>
                                        <td class="border border-gray-300 p-3 h-12 bg-white/60" colspan="9"></td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $anak->firstItem() ?? 0 }} sampai {{ $anak->lastItem() ?? 0 }} dari {{ $anak->total() }} data
                    </div>
                    <div>
                        {{ $anak->appends(['search' => $search ?? '', 'perPage' => request('perPage') ?? 10])->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Modal -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Detail Data Anak</h3>
                    <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto max-h-[70vh]">
                    <div class="space-y-4 mb-6">
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">NIK Ibu</h3>
                            <p id="detail_nik" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Ibu</h3>
                            <p id="detail_nama_orangtua" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Anak</h3>
                            <p id="detail_nama_anak" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tempat Lahir</h3>
                            <p id="detail_tempat_lahir" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Tanggal Lahir</h3>
                            <p id="detail_tanggal_lahir" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Usia</h3>
                            <p id="detail_usia" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Jenis Kelamin</h3>
                            <p id="detail_jenis_kelamin" class="text-lg text-black">Loading...</p>
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

function fetchAnakDetail(id) {
    // Show loading state
    document.getElementById('detail_nik').textContent = 'Loading...';
    document.getElementById('detail_nama_orangtua').textContent = 'Loading...';
    document.getElementById('detail_nama_anak').textContent = 'Loading...';
    document.getElementById('detail_tempat_lahir').textContent = 'Loading...';
    document.getElementById('detail_tanggal_lahir').textContent = 'Loading...';
    document.getElementById('detail_usia').textContent = 'Loading...';
    document.getElementById('detail_jenis_kelamin').textContent = 'Loading...';
    
    // Open modal
    openDetailModal();
    
    // Fetch data
    fetch(`{{ url('/anak') }}/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Format date
        let formattedTanggalLahir = '-';
        if (data.anak.tanggal_lahir) {
            const tanggalLahir = new Date(data.anak.tanggal_lahir);
            formattedTanggalLahir = tanggalLahir.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
        
        // Populate data
        document.getElementById('detail_nik').textContent = data.pengguna ? data.pengguna.nik : '-';
        document.getElementById('detail_nama_orangtua').textContent = data.pengguna ? data.pengguna.nama : '-';
        document.getElementById('detail_nama_anak').textContent = data.anak.nama_anak;
        document.getElementById('detail_tempat_lahir').textContent = data.anak.tempat_lahir;
        document.getElementById('detail_tanggal_lahir').textContent = formattedTanggalLahir;
        document.getElementById('detail_usia').textContent = data.anak.usia || '-';
        document.getElementById('detail_jenis_kelamin').textContent = data.anak.jenis_kelamin;
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
