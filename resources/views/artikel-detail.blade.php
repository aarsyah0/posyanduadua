@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Detail Artikel</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('dashboard') }}" class="text-[#06B3BF] font-semibold hover:underline">Dashboard</a> / 
                <a href="{{ route('artikel.index') }}" class="text-[#06B3BF] font-semibold hover:underline">Artikel</a> / <span>Detail</span>
            </nav>
        </div>
    </div>

    <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
        <article class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header Image -->
            <div class="h-[300px] w-full overflow-hidden relative">
                <img src="{{ asset('storage/artikel/' . $artikel->gambar_artikel) }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                    <div class="p-6 w-full">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $artikel->judul }}</h1>
                        <div class="flex items-center text-sm text-white/90">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($artikel->tanggal)->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-6 md:p-8">
                <div class="prose prose-lg max-w-none">
                    <div class="bg-[#F9FAFB] p-4 border-l-4 border-[#06B3BF] rounded-r-lg mb-6">
                        <p class="text-gray-700 italic">{{ Str::limit($artikel->isi_artikel, 200) }}</p>
                    </div>
                    <div class="text-gray-700 leading-relaxed space-y-4">
                        {!! nl2br(e($artikel->isi_artikel)) !!}
                    </div>
                </div>
                
                <!-- Action buttons -->
                <div class="mt-10 flex flex-col sm:flex-row sm:justify-between gap-3">
                    <a href="{{ route('artikel.index') }}" class="flex items-center justify-center bg-white text-[#06B3BF] border border-[#06B3BF] px-5 py-2.5 rounded-xl hover:bg-[#06B3BF]/5 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Daftar Artikel
                    </a>
                    
                    <form action="{{ route('artikel.destroy', $artikel->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center bg-red-50 text-red-600 border border-red-300 px-5 py-2.5 rounded-xl hover:bg-red-100 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Artikel
                        </button>
                    </form>
                </div>
            </div>
        </article>
        
        <!-- Share buttons or related articles could go here -->
        <div class="mt-6 p-4 bg-[#F0F9FF] rounded-xl border border-[#BFDBFE]">
            <h3 class="font-medium text-[#06B3BF] mb-2">Informasi</h3>
            <p class="text-sm text-gray-600">Artikel ini dipublikasikan oleh Admin Posyandu pada {{ \Carbon\Carbon::parse($artikel->created_at)->format('d F Y') }}.</p>
        </div>
    </div>
</div>

<style>
    /* Improved styling for the content */
    .prose {
        color: #374151;
    }
    
    .prose p {
        margin-bottom: 1.25em;
        line-height: 1.75;
    }
</style>
@endsection 