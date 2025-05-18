@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Artikel Kesehatan</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / <span>Artikel</span>
            </nav>
        </div>
    </div>

    <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
            <button id="create-article-btn" class="flex items-center bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] hover:from-[#06B3BF] hover:to-[#63BE9A] text-white font-medium px-5 py-2.5 rounded-xl transition-all duration-300 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Buat Artikel Baru
            </button>
            
            <form action="{{ route('artikel.search') }}" method="GET" class="w-full md:w-auto">
                <div class="flex items-center">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" name="keyword" placeholder="Cari artikel..." value="{{ isset($keyword) ? $keyword : '' }}" class="bg-white/80 border border-gray-300 text-gray-900 rounded-l-lg focus:ring-[#06B3BF] focus:border-[#06B3BF] block w-full pl-10 p-2.5">
                    </div>
                    <button type="submit" class="bg-[#06B3BF] hover:bg-[#05a1ac] text-white font-medium px-4 py-2.5 rounded-r-lg transition-colors duration-300">Cari</button>
                    @if(isset($keyword) && $keyword)
                        <a href="{{ route('artikel.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-3 py-2.5 rounded-lg ml-2 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if(isset($keyword) && $keyword)
            <div class="mb-6 p-3 bg-blue-50 border border-blue-100 text-blue-700 rounded-xl flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Menampilkan hasil pencarian untuk: <span class="font-semibold mx-1">{{ $keyword }}</span> ({{ count($artikels) }} hasil)
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Modal untuk menambah artikel -->
        <div id="create-article-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50 backdrop-blur-sm">
            <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full mx-auto transform transition-all duration-300">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Tambah Artikel Baru</h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Artikel*</label>
                            <input type="text" name="judul" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" placeholder="Masukkan judul artikel" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Artikel*</label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-3 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                                        <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (Max. 2MB)</p>
                                    </div>
                                    <input type="file" name="gambar_artikel" class="hidden" required accept="image/*" />
                                </label>
                            </div> 
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal*</label>
                            <input type="date" name="tanggal" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Isi Artikel*</label>
                            <textarea name="isi_artikel" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#06B3BF] focus:border-transparent transition-all duration-300" rows="6" placeholder="Tulis isi artikel di sini..." required></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-medium transition-colors duration-300">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] hover:from-[#06B3BF] hover:to-[#63BE9A] text-white rounded-xl font-medium transition-all duration-300 shadow-md">Terbitkan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Daftar Artikel dengan Grid Layout -->
        <div class="max-h-[70vh] overflow-y-auto pr-2 scrollbar">
            @if(count($artikels) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($artikels as $artikel)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:shadow-xl hover:-translate-y-1 hover:scale-102 border border-gray-100">
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset('storage/artikel/' . $artikel->gambar_artikel) }}" alt="Gambar Artikel" class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                        </div>
                        <div class="p-5">
                            <div class="flex items-center text-xs text-gray-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-[#06B3BF]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($artikel->tanggal)->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $artikel->judul }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ Str::limit($artikel->isi_artikel, 120, '...') }}</p>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('artikel.show', $artikel->id) }}" class="inline-flex items-center text-sm font-medium text-[#06B3BF] hover:text-[#05a1ac] transition-colors duration-300">
                                    Baca Selengkapnya
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination Controls -->
                <div class="mt-8">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Menampilkan {{ $artikels->firstItem() ?? 0 }} - {{ $artikels->lastItem() ?? 0 }} dari {{ $artikels->total() }} artikel
                        </div>
                        <div class="flex items-center space-x-1">
                            @if($artikels->onFirstPage())
                                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $artikels->previousPageUrl() }}" class="px-3 py-1 bg-white border border-gray-200 text-[#06B3BF] rounded-lg hover:bg-[#06B3BF]/5 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif

                            @foreach($artikels->getUrlRange(1, $artikels->lastPage()) as $page => $url)
                                <a href="{{ $url }}" 
                                   class="{{ $page == $artikels->currentPage() 
                                             ? 'px-3 py-1 bg-[#06B3BF] text-white rounded-lg' 
                                             : 'px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors duration-300' }}">
                                    {{ $page }}
                                </a>
                            @endforeach

                            @if($artikels->hasMorePages())
                                <a href="{{ $artikels->nextPageUrl() }}" class="px-3 py-1 bg-white border border-gray-200 text-[#06B3BF] rounded-lg hover:bg-[#06B3BF]/5 transition-colors duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white/90 p-8 rounded-xl text-center border border-gray-100 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    @if(isset($keyword) && $keyword)
                        <p class="text-gray-600 mb-2">Tidak ada artikel yang sesuai dengan pencarian</p>
                        <a href="{{ route('artikel.index') }}" class="inline-flex items-center text-[#06B3BF] hover:text-[#05a1ac] font-medium transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke semua artikel
                        </a>
                    @else
                        <p class="text-gray-600 mb-2">Belum ada artikel yang ditambahkan</p>
                        <button id="empty-create-btn" class="mt-2 inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] text-white rounded-lg hover:from-[#06B3BF] hover:to-[#63BE9A] transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Artikel Baru
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar styling */
    .scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    
    .scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .scrollbar::-webkit-scrollbar-thumb {
        background: #a8dbd9;
        border-radius: 10px;
    }
    
    .scrollbar::-webkit-scrollbar-thumb:hover {
        background: #63BE9A;
    }
    
    /* Animation for hover */
    .hover\:scale-102:hover {
        transform: scale(1.02);
    }
    
    /* Line clamping for text */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    document.getElementById('create-article-btn').onclick = function() {
        const modal = document.getElementById('create-article-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Add slight animation
        const modalContent = modal.querySelector('div');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 10);
    };
    
    // For the empty state button
    if (document.getElementById('empty-create-btn')) {
        document.getElementById('empty-create-btn').onclick = function() {
            const modal = document.getElementById('create-article-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Add slight animation
            const modalContent = modal.querySelector('div');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
            }, 10);
        };
    }

    function closeModal() {
        const modal = document.getElementById('create-article-modal');
        const modalContent = modal.querySelector('div');
        
        // Add exit animation
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modalContent.classList.remove('scale-95', 'opacity-0');
        }, 200);
    }
    
    // Close modal when clicking outside
    document.getElementById('create-article-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection
