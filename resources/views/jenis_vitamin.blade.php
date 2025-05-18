@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Data Jenis Vitamin</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Data Jenis Vitamin</span>
            </nav>
        </div>
    </div>
    @if(isset($action) && $action == 'create')
        <!-- Form Create -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-black">Tambah Jenis Vitamin</h3>
                <a href="{{ route('jenis_vitamin.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('jenis_vitamin.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Jenis Vitamin*</label>
                        <select name="nama" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama') border-red-500 @enderror">
                            <option value="">-Pilih Jenis Vitamin-</option>
                            @foreach($jenisOptions as $jenis)
                                <option value="{{ $jenis }}" {{ old('nama') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Umur Minimum (bulan)*</label>
                        <input type="number" name="min_umur_bulan" min="0" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('min_umur_bulan') border-red-500 @enderror" placeholder="Umur Minimum" value="{{ old('min_umur_bulan') }}">
                        @error('min_umur_bulan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Umur Maksimum (bulan)*</label>
                        <input type="number" name="max_umur_bulan" min="0" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('max_umur_bulan') border-red-500 @enderror" placeholder="Umur Maksimum" value="{{ old('max_umur_bulan') }}">
                        @error('max_umur_bulan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-black">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('keterangan') border-red-500 @enderror" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
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
                <h3 class="text-2xl font-bold text-black">Edit Jenis Vitamin</h3>
                <a href="{{ route('jenis_vitamin.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <form action="{{ route('jenis_vitamin.update', $jenisVitamin->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-black">Nama Jenis Vitamin*</label>
                        <select name="nama" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('nama') border-red-500 @enderror">
                            <option value="">-Pilih Jenis Vitamin-</option>
                            @foreach($jenisOptions as $jenis)
                                <option value="{{ $jenis }}" {{ old('nama', $jenisVitamin->nama) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                            @endforeach
                        </select>
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Umur Minimum (bulan)*</label>
                        <input type="number" name="min_umur_bulan" min="0" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('min_umur_bulan') border-red-500 @enderror" placeholder="Umur Minimum" value="{{ old('min_umur_bulan', $jenisVitamin->min_umur_bulan) }}">
                        @error('min_umur_bulan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-black">Umur Maksimum (bulan)*</label>
                        <input type="number" name="max_umur_bulan" min="0" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('max_umur_bulan') border-red-500 @enderror" placeholder="Umur Maksimum" value="{{ old('max_umur_bulan', $jenisVitamin->max_umur_bulan) }}">
                        @error('max_umur_bulan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-black">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300 @error('keterangan') border-red-500 @enderror" placeholder="Keterangan">{{ old('keterangan', $jenisVitamin->keterangan) }}</textarea>
                        @error('keterangan')
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
                <h3 class="text-2xl font-bold text-black">Detail Jenis Vitamin</h3>
                <a href="{{ route('jenis_vitamin.index') }}" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Kembali</a>
            </div>
            <div class="bg-white/60 p-6 rounded-xl border border-white/40 shadow-md">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm hover:shadow-md transition-all duration-300">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Nama Jenis Vitamin</h3>
                        <p class="text-lg text-black">{{ $jenisVitamin->nama }}</p>
                    </div>
                    <div class="bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm hover:shadow-md transition-all duration-300">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Rentang Umur</h3>
                        <p class="text-lg text-black">{{ $jenisVitamin->min_umur_bulan }} - {{ $jenisVitamin->max_umur_bulan }} bulan</p>
                    </div>
                    <div class="md:col-span-2 bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm hover:shadow-md transition-all duration-300">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Keterangan</h3>
                        <p class="text-lg text-black">{{ $jenisVitamin->keterangan ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2 bg-white/90 p-4 rounded-xl border border-[#63BE9A]/30 shadow-sm hover:shadow-md transition-all duration-300">
                        <h3 class="text-sm font-semibold text-[#06B3BF] mb-1">Terdaftar Pada</h3>
                        <p class="text-lg text-black">{{ $jenisVitamin->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('jenis_vitamin.edit', $jenisVitamin->id) }}" class="bg-yellow-500 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Edit</a>
                    <form action="{{ route('jenis_vitamin.destroy', $jenisVitamin->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Index View (List) -->
        <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
            <div class="bg-white/60 p-6 rounded-xl border border-white/40">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 my-4">
                    <div class="flex gap-2">
                        <a href="#" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Cetak</a>
                        <a href="{{ route('jenis_vitamin.create') }}" class="bg-gradient-to-r from-green-500 to-[#63BE9A] text-white px-5 py-2 rounded-xl shadow hover:opacity-90 transition">Tambah</a>
                    </div>
                    <form action="{{ route('jenis_vitamin.index') }}" method="GET" class="flex-grow md:max-w-xs">
                        <div class="flex">
                            <input type="text" name="search" placeholder="Pencarian..." class="border p-3 rounded-l-xl w-full focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" value="{{ $search ?? '' }}">
                            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-400 text-white px-5 py-2 rounded-r-xl shadow hover:opacity-90 transition">Cari</button>
                            @if(isset($search) && $search)
                                <a href="{{ route('jenis_vitamin.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-xl ml-2 shadow hover:opacity-90 transition">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(isset($search) && $search)
                    <div class="mb-4 p-2 bg-blue-100 text-blue-700 rounded-lg">
                        Menampilkan hasil pencarian untuk: <strong>{{ $search }}</strong> ({{ $jenisVitamin->total() }} hasil)
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-end mb-4">
                    <form action="{{ route('jenis_vitamin.index') }}" method="GET" class="flex items-center">
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
                                <th class="border border-gray-300 p-3">Jenis Vitamin</th>
                                <th class="border border-gray-300 p-3">Umur Minimum</th>
                                <th class="border border-gray-300 p-3">Umur Maksimum</th>
                                <th class="border border-gray-300 p-3">Keterangan</th>
                                <th class="border border-gray-300 p-3 w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white/80">
                            @forelse($jenisVitamin as $index => $jv)
                                <tr class="hover:bg-[#63BE9A]/10 transition">
                                    <td class="border border-gray-300 p-3">{{ ($jenisVitamin->currentPage() - 1) * $jenisVitamin->perPage() + $index + 1 }}</td>
                                    <td class="border border-gray-300 p-3">{{ $jv->nama }}</td>
                                    <td class="border border-gray-300 p-3">{{ $jv->min_umur_bulan }} bulan</td>
                                    <td class="border border-gray-300 p-3">{{ $jv->max_umur_bulan }} bulan</td>
                                    <td class="border border-gray-300 p-3">{{ Str::limit($jv->keterangan, 50) ?? '-' }}</td>
                                    <td class="border border-gray-300 p-3">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('jenis_vitamin.show', $jv->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Detail</a>
                                            <a href="{{ route('jenis_vitamin.edit', $jv->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Edit</a>
                                            <form action="{{ route('jenis_vitamin.destroy', $jv->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg text-xs shadow hover:opacity-90 transition">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border border-gray-300 p-3 text-center bg-white/60">
                                        @if(isset($search) && $search)
                                            Tidak ada data jenis vitamin yang sesuai dengan pencarian
                                        @else
                                            Tidak ada data jenis vitamin
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            @if(count($jenisVitamin) > 0 && count($jenisVitamin) < 4)
                                @for($i = 0; $i < 4 - count($jenisVitamin); $i++)
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
                        Menampilkan {{ $jenisVitamin->firstItem() ?? 0 }} sampai {{ $jenisVitamin->lastItem() ?? 0 }} dari {{ $jenisVitamin->total() }} data
                    </div>
                    <div>
                        {{ $jenisVitamin->appends(['search' => $search ?? '', 'perPage' => request('perPage') ?? 10])->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 