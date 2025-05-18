@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Petugas</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Petugas</span>
            </nav>
        </div>
    </div>
    <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
        <div class="bg-white/60 p-6 rounded-xl border border-white/40">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">
                <button onclick="openModal()" class="bg-gradient-to-r from-green-500 to-[#63BE9A] text-white px-5 py-2 rounded-xl shadow border border-white/40 hover:opacity-90 transition">Tambah</button>
                <form action="{{ route('petugas.index') }}" method="GET" class="flex-grow md:max-w-xs">
                    <div class="flex">
                        <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ request('search') }}">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                        @if(request('search'))
                            <a href="{{ route('petugas.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
                        @endif
                    </div>
                </form>
            </div>
            @if(request('search'))
                <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded-lg">
                    Menampilkan hasil pencarian untuk: <strong>{{ request('search') }}</strong> ({{ $petugas->total() }} hasil)
                </div>
            @endif
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            <div class="flex justify-end mb-4">
                <form action="{{ route('petugas.index') }}" method="GET" class="flex items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
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
                <table class="w-full border-collapse border border-gray-300 text-left rounded-xl overflow-hidden">
                    <thead class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white">
                        <tr>
                            <th class="border border-gray-300 p-3 w-16">No</th>
                            <th class="border border-gray-300 p-3">NIK</th>
                            <th class="border border-gray-300 p-3">Nama</th>
                            <th class="border border-gray-300 p-3">Email</th>
                            <th class="border border-gray-300 p-3">No Telp</th>
                            <th class="border border-gray-300 p-3">Alamat</th>
                            <th class="border border-gray-300 p-3 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/80">
                        @forelse($petugas as $index => $p)
                            <tr class="hover:bg-[#63BE9A]/10 transition">
                                <td class="border border-gray-300 p-3">{{ ($petugas->currentPage() - 1) * $petugas->perPage() + $index + 1 }}</td>
                                <td class="border border-gray-300 p-3">{{ $p->nik }}</td>
                                <td class="border border-gray-300 p-3">{{ $p->nama }}</td>
                                <td class="border border-gray-300 p-3">{{ $p->email }}</td>
                                <td class="border border-gray-300 p-3">{{ $p->no_telp }}</td>
                                <td class="border border-gray-300 p-3">{{ $p->alamat }}</td>
                                <td class="border border-gray-300 p-3">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="openEditModal('{{ $p->nik }}', '{{ $p->nama }}', '{{ $p->email }}', '{{ $p->no_telp }}', '{{ $p->alamat }}')" class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Edit</button>
                                        <form action="{{ route('petugas.destroy', $p->nik) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                                    @if(request('search'))
                                        Tidak ada data petugas yang sesuai dengan pencarian
                                    @else
                                        Tidak ada data petugas
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                        @if(count($petugas) > 0 && count($petugas) < 4)
                            @for($i = 0; $i < 4 - count($petugas); $i++)
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
                    Menampilkan {{ $petugas->firstItem() ?? 0 }} sampai {{ $petugas->lastItem() ?? 0 }} dari {{ $petugas->total() }} data
                </div>
                <div>
                    {{ $petugas->appends(['search' => request('search'), 'perPage' => request('perPage') ?? 10])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Petugas -->
<div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Tambah Petugas Baru</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('petugas.store') }}" method="POST" class="overflow-y-auto max-h-[70vh]">
            @csrf
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">NIK*</label>
                    <input type="text" name="nik" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nik') border-red-500 @enderror" placeholder="NIK" pattern="\d{16}" title="NIK harus terdiri dari 16 digit" maxlength="16" value="{{ old('nik') }}">
                    @error('nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Nama*</label>
                    <input type="text" name="nama" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama') border-red-500 @enderror" placeholder="Nama Lengkap" value="{{ old('nama') }}">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Email*</label>
                    <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('email') border-red-500 @enderror" placeholder="Email" value="{{ old('email') }}">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Password*</label>
                    <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">No Telp</label>
                    <input type="text" name="no_telp" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('no_telp') border-red-500 @enderror" placeholder="Nomor Telepon" value="{{ old('no_telp') }}">
                    @error('no_telp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Alamat</label>
                    <textarea name="alamat" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('alamat') border-red-500 @enderror" rows="3" placeholder="Alamat Lengkap">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Hidden role field -->
                <input type="hidden" name="role" value="admin">
            </div>
            <div class="mt-8 flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-black px-5 py-2 rounded-xl font-semibold shadow hover:shadow-md transition">Batal</button>
                <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl font-semibold shadow-md hover:shadow-lg hover:opacity-90 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Petugas -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
    <div class="bg-white/70 backdrop-blur-md p-8 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-black">Edit Petugas</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="editForm" method="POST" class="overflow-y-auto max-h-[70vh]">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">NIK</label>
                    <input type="text" id="edit_nik" class="w-full p-3 border border-gray-300 rounded-xl bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Nama*</label>
                    <input type="text" name="nama" id="edit_nama" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama') border-red-500 @enderror" required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Email*</label>
                    <input type="email" name="email" id="edit_email" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Password (Kosongkan jika tidak ingin diubah)</label>
                    <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">No Telp</label>
                    <input type="text" name="no_telp" id="edit_no_telp" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('no_telp') border-red-500 @enderror">
                    @error('no_telp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black mb-2">Alamat</label>
                    <textarea name="alamat" id="edit_alamat" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('alamat') border-red-500 @enderror" rows="3"></textarea>
                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Hidden role field -->
                <input type="hidden" name="role" value="admin">
            </div>
            <div class="mt-8 flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-300 hover:bg-gray-400 text-black px-5 py-2 rounded-xl font-semibold shadow hover:shadow-md transition">Batal</button>
                <button type="submit" class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white px-5 py-2 rounded-xl font-semibold shadow-md hover:shadow-lg hover:opacity-90 transition">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('tambahModal').classList.remove('hidden');
    document.getElementById('tambahModal').classList.add('flex');
    // Disable scrolling on body
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('tambahModal').classList.add('hidden');
    document.getElementById('tambahModal').classList.remove('flex');
    // Re-enable scrolling
    document.body.style.overflow = '';
}

function openEditModal(nik, nama, email, no_telp, alamat) {
    document.getElementById('editForm').action = `{{ url('/petugas') }}/${nik}`;
    document.getElementById('edit_nik').value = nik;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_no_telp').value = no_telp;
    document.getElementById('edit_alamat').value = alamat;
    
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
    // Disable scrolling on body
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
    // Re-enable scrolling
    document.body.style.overflow = '';
}

// Buka modal tambah jika ada error validasi
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any() && session('modal') === 'tambah')
        openModal();
    @endif
    
    @if($errors->any() && session('modal') === 'edit')
        openEditModal('{{ session('nik') }}', '{{ session('nama') }}', '{{ session('email') }}', '{{ session('no_telp') }}', '{{ session('alamat') }}');
    @endif
    
    // Close modal when clicking outside
    document.querySelectorAll('#tambahModal, #editModal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                if (modal.id === 'tambahModal') {
                    closeModal();
                } else {
                    closeEditModal();
                }
            }
        });
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('tambahModal').classList.contains('hidden')) {
                closeModal();
            }
            if (!document.getElementById('editModal').classList.contains('hidden')) {
                closeEditModal();
            }
        }
    });
});
</script>

@endsection
