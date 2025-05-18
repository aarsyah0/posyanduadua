@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Orang Tua</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Orang Tua</span>
            </nav>
        </div>
    </div>
    @if(isset($action) && $action == 'create')
        <!-- Form Create (if needed) -->
    @elseif(isset($action) && $action == 'edit')
        <!-- Form Edit (if needed) -->
    @else
        <!-- Index View (List) -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="bg-white/60 p-6 rounded-xl border border-white/40">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">
                    <div class="flex gap-2">
                        <a href="#" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Cetak</a>
                    </div>
                    <form action="{{ route('data_orangtua.index') }}" method="GET" class="flex-grow md:max-w-xs">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('data_orangtua.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
                @if(isset($search) && $search)
                    <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded-lg">
                        Menampilkan hasil pencarian untuk: <strong>{{ $search }}</strong> ({{ $pengguna->total() }} hasil)
                    </div>
                @endif
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="flex justify-end mb-4">
                    <form action="{{ route('data_orangtua.index') }}" method="GET" class="flex items-center">
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
                                <th class="border border-gray-300 p-3">Email</th>
                                <th class="border border-gray-300 p-3">Nama Anak</th>
                                <th class="border border-gray-300 p-3">Usia Anak</th>
                                <th class="border border-gray-300 p-3">Jenis Kelamin Anak</th>
                                <th class="border border-gray-300 p-3">No. Telp</th>
                                <th class="border border-gray-300 p-3 w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($pengguna as $index => $p)
                                <tr class="hover:bg-[#63BE9A]/10 transition">
                                    <td class="border border-gray-300 p-3">{{ ($pengguna->currentPage() - 1) * $pengguna->perPage() + $index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->nik }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->nama }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->email ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->isEmpty() ? '-' : $p->anak->first()->nama_anak }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->isEmpty() ? '-' : $p->anak->first()->usia }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->isEmpty() ? '-' : $p->anak->first()->jenis_kelamin }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->no_telp }}</td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="fetchOrangtuaDetail('{{ $p->nik }}')" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</button>
                                            <form action="{{ route('data_orangtua.destroy', $p->nik) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                                            Tidak ada data orang tua yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data orang tua
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($pengguna) > 0 && count($pengguna) < 4)
                                @for($i = 0; $i < 4 - count($pengguna); $i++)
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
                        Menampilkan {{ $pengguna->firstItem() ?? 0 }} sampai {{ $pengguna->lastItem() ?? 0 }} dari {{ $pengguna->total() }} data
                    </div>
                    <div>
                        {{ $pengguna->appends(['search' => $search ?? '', 'perPage' => request('perPage') ?? 10])->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Modal -->
        <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-black">Detail Orang Tua</h3>
                    <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="overflow-y-auto max-h-[70vh]">
                    <div class="space-y-4 mb-6">
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">NIK</h3>
                            <p id="detail_nik" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Ibu</h3>
                            <p id="detail_nama" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Email</h3>
                            <p id="detail_email" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Anak</h3>
                            <p id="detail_nama_anak" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">No Telp</h3>
                            <p id="detail_no_telp" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Usia Anak</h3>
                            <p id="detail_usia" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Jenis Kelamin Anak</h3>
                            <p id="detail_jenis_kelamin" class="text-lg text-black">Loading...</p>
                        </div>
                        <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm">
                            <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Alamat</h3>
                            <p id="detail_alamat" class="text-lg text-black">Loading...</p>
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

function fetchOrangtuaDetail(nik) {
    // Show loading state
    document.getElementById('detail_nik').textContent = 'Loading...';
    document.getElementById('detail_nama').textContent = 'Loading...';
    document.getElementById('detail_email').textContent = 'Loading...';
    document.getElementById('detail_nama_anak').textContent = 'Loading...';
    document.getElementById('detail_no_telp').textContent = 'Loading...';
    document.getElementById('detail_usia').textContent = 'Loading...';
    document.getElementById('detail_jenis_kelamin').textContent = 'Loading...';
    document.getElementById('detail_alamat').textContent = 'Loading...';
    document.getElementById('detail_terdaftar').textContent = 'Loading...';
    
    // Open modal
    openDetailModal();
    
    // Fetch data
    fetch(`{{ url('/data_orangtua') }}/${nik}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Format date
        const createdAt = new Date(data.pengguna.created_at);
        const formattedCreatedAt = createdAt.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Populate data
        document.getElementById('detail_nik').textContent = data.pengguna.nik;
        document.getElementById('detail_nama').textContent = data.pengguna.nama;
        document.getElementById('detail_email').textContent = data.pengguna.email || '-';
        document.getElementById('detail_nama_anak').textContent = data.anak && data.anak.length > 0 ? data.anak[0].nama_anak : '-';
        document.getElementById('detail_no_telp').textContent = data.pengguna.no_telp || '-';
        document.getElementById('detail_usia').textContent = data.anak && data.anak.length > 0 ? data.anak[0].usia : '-';
        document.getElementById('detail_jenis_kelamin').textContent = data.anak && data.anak.length > 0 ? data.anak[0].jenis_kelamin : '-';
        document.getElementById('detail_alamat').textContent = data.pengguna.alamat || '-';
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
