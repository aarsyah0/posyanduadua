@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Pengguna</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Pengguna</span>
            </nav>
        </div>
    </div>
    @if(isset($action) && $action == 'create')
        <!-- Form Create -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Tambah Pengguna Baru</h3>
                <a href="{{ route('pengguna.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('pengguna.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">NIK*</label>
                        <input type="text" name="nik" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nik') border-red-500 @enderror" placeholder="Masukkan NIK" value="{{ old('nik') }}" pattern="\d{16}" title="NIK harus terdiri dari 16 digit" maxlength="16">
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Ibu*</label>
                        <input type="text" name="nama" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama') border-red-500 @enderror" placeholder="Nama Ibu" value="{{ old('nama') }}">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Email*</label>
                        <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('email') border-red-500 @enderror" placeholder="Email" value="{{ old('email') }}">
                        @error('email')
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
                        <label class="block text-sm font-semibold text-black">No Telp*</label>
                        <input type="number" name="no_telp" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('no_telp') border-red-500 @enderror" placeholder="No Telp" value="{{ old('no_telp') }}" inputmode="numeric" pattern="[0-9]*">
                        @error('no_telp')
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
                        <input type="text" name="usia" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('usia') border-red-500 @enderror" placeholder="Usia" value="{{ old('usia') }}">
                        @error('usia')
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
                    <div>
                        <label class="block text-sm font-semibold text-black">Password*</label>
                        <div class="relative">
                            <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Masukkan Password" id="password">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility('password')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 3a7 7 0 00-7 7 7 7 0 0014 0 7 7 0 00-7-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" />
                                </svg>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-black">Alamat*</label>
                        <textarea name="alamat" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('alamat') border-red-500 @enderror" placeholder="Alamat" rows="3">{{ old('alamat') }}</textarea>
                        @error('alamat')
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
                <h3 class="text-2xl font-bold text-black">Edit Data Pengguna</h3>
                <a href="{{ route('pengguna.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('pengguna.update', $pengguna->nik) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">NIK</label>
                        <input type="text" class="w-full p-3 border border-gray-300 rounded-xl bg-gray-200" value="{{ $pengguna->nik }}" readonly>
                        <p class="text-gray-500 text-xs mt-1">NIK tidak dapat diubah</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Ibu*</label>
                        <input type="text" name="nama" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama') border-red-500 @enderror" placeholder="Nama Ibu" value="{{ old('nama', $pengguna->nama) }}">
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Email*</label>
                        <input type="email" name="email" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('email') border-red-500 @enderror" placeholder="Email" value="{{ old('email', $pengguna->email) }}">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Anak*</label>
                        <input type="text" name="nama_anak" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama_anak') border-red-500 @enderror" placeholder="Nama Anak" value="{{ old('nama_anak', $pengguna->anak->first() ? $pengguna->anak->first()->nama_anak : '') }}">
                        @error('nama_anak')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">No Telp*</label>
                        <input type="number" name="no_telp" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('no_telp') border-red-500 @enderror" placeholder="No Telp" value="{{ old('no_telp', $pengguna->no_telp) }}" inputmode="numeric" pattern="[0-9]*">
                        @error('no_telp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tanggal Lahir*</label>
                        <input type="date" name="tanggal_lahir" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tanggal_lahir') border-red-500 @enderror" value="{{ old('tanggal_lahir', ($pengguna->anak->first() && $pengguna->anak->first()->tanggal_lahir) ? $pengguna->anak->first()->tanggal_lahir->format('Y-m-d') : '') }}">
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Usia*</label>
                        <input type="text" name="usia" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('usia') border-red-500 @enderror" placeholder="Usia" value="{{ old('usia', $pengguna->anak->first() ? $pengguna->anak->first()->usia : '') }}">
                        @error('usia')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Tempat Lahir*</label>
                        <input type="text" name="tempat_lahir" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('tempat_lahir') border-red-500 @enderror" placeholder="Tempat Lahir" value="{{ old('tempat_lahir', $pengguna->anak->first() ? $pengguna->anak->first()->tempat_lahir : '') }}">
                        @error('tempat_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Jenis Kelamin*</label>
                        <select name="jenis_kelamin" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('jenis_kelamin') border-red-500 @enderror">
                            <option value="">-Pilih-</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $pengguna->anak->first() ? $pengguna->anak->first()->jenis_kelamin : '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $pengguna->anak->first() ? $pengguna->anak->first()->jenis_kelamin : '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Password</label>
                        <div class="relative">
                            <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Masukkan Password (Kosongkan jika tidak ingin mengubah)" id="edit-password">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility('edit-password')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 3a7 7 0 00-7 7 7 7 0 0014 0 7 7 0 00-7-7zm0 12a5 5 0 110-10 5 5 0 010 10zm0-8a3 3 0 100 6 3 3 0 000-6z" />
                                </svg>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-black">Alamat*</label>
                        <textarea name="alamat" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('alamat') border-red-500 @enderror" placeholder="Alamat" rows="3">{{ old('alamat', $pengguna->alamat) }}</textarea>
                        @error('alamat')
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
                    <form action="{{ route('pengguna.index') }}" method="GET" class="flex-grow md:max-w-xs">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('pengguna.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
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
                    <form action="{{ route('pengguna.index') }}" method="GET" class="flex items-center">
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
                                <th class="border border-gray-300 p-3">Nama</th>
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
                                    <td class="border border-gray-300 p-3">{{ $p->anak->isEmpty() ? '-' : $p->anak->first()->nama_anak }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->isEmpty() ? '-' : $p->anak->first()->usia }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->anak->isEmpty() ? '-' : $p->anak->first()->jenis_kelamin }}</td>
                                    <td class="border border-gray-300 p-3">{{ $p->no_telp }}</td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            <button onclick="fetchPenggunaDetail('{{ $p->nik }}')" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</button>
                                            <form action="{{ route('pengguna.destroy', $p->nik) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="border border-gray-300 p-3 text-center bg-white/60">
                                        @if(isset($search) && $search)
                                            Tidak ada data pengguna yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data pengguna
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($pengguna) > 0 && count($pengguna) < 4)
                                @for($i = 0; $i < 4 - count($pengguna); $i++)
                                    <tr>
                                        <td class="border border-gray-300 p-3 h-12 bg-white/60" colspan="8"></td>
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
    @endif
    
    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex justify-center items-center z-50">
        <div class="bg-[#d2e8e1] backdrop-blur-md p-6 rounded-2xl shadow-xl max-w-md w-full mx-auto border border-white/40">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-black">Detail Pengguna</h3>
                <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="overflow-y-auto max-h-[70vh]">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">NIK</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_nik">-</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">Nama</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_nama">-</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">Email</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_email">-</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">Nama Anak</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_nama_anak">-</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">No Telp</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_no_telp">-</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">Usia Anak</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_usia">-</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">Jenis Kelamin Anak</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_jenis_kelamin">-</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">Alamat</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_alamat">-</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black mb-1">Terdaftar Pada</label>
                        <div class="w-full p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                            <p class="text-black" id="detail_terdaftar">-</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeDetailModal()" class="bg-gray-300 hover:bg-gray-400 text-black px-5 py-2 rounded-xl font-semibold shadow hover:shadow-md transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fetchPenggunaDetail(nik) {
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
    fetch(`{{ url('/pengguna') }}/${nik}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Populate modal with data
        document.getElementById('detail_nik').textContent = data.pengguna.nik || '-';
        document.getElementById('detail_nama').textContent = data.pengguna.nama || '-';
        document.getElementById('detail_email').textContent = data.pengguna.email || '-';
        document.getElementById('detail_nama_anak').textContent = data.anak && data.anak.length > 0 ? data.anak[0].nama_anak : '-';
        document.getElementById('detail_no_telp').textContent = data.pengguna.no_telp || '-';
        document.getElementById('detail_usia').textContent = data.anak && data.anak.length > 0 ? data.anak[0].usia : '-';
        document.getElementById('detail_jenis_kelamin').textContent = data.anak && data.anak.length > 0 ? data.anak[0].jenis_kelamin : '-';
        document.getElementById('detail_alamat').textContent = data.pengguna.alamat || '-';
        
        // Format the date
        const createdDate = new Date(data.pengguna.created_at);
        const formattedDate = createdDate.toLocaleString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        document.getElementById('detail_terdaftar').textContent = formattedDate;
    })
    .catch(error => {
        console.error('Error fetching data:', error);
        closeDetailModal();
        alert('Terjadi kesalahan saat mengambil data. Silakan coba lagi.');
    });
}

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

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
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
});

function togglePasswordVisibility(id) {
    const passwordField = document.getElementById(id);
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
}
</script>
@endsection 