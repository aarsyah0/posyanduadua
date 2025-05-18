@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#63BE9A] to-[#06B3BF] p-6">
    <div class="max-w-5xl mx-auto relative mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <h2 class="text-3xl font-bold text-black">Tambah Petugas</h2>
            <nav class="text-gray-600 text-sm bg-white/60 px-4 py-2 rounded-xl shadow border border-white/40">
                <a href="{{ route('petugas.index') }}" class="text-[#06B3BF] font-semibold hover:underline">Petugas</a> / <span>Tambah Petugas</span>
            </nav>
        </div>
    </div>

    <div class="w-full max-w-5xl bg-white/70 backdrop-blur-md rounded-2xl shadow-xl p-8 mx-auto mt-8 border border-white/40">
        <form action="{{ route('petugas.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-black">Username*</label>
                    <input type="text" name="username" class="w-full p-3 border border-gray-200 rounded-xl focus:border-2 focus:border-[#06B3BF] focus:ring-1 focus:ring-[#06B3BF] focus:bg-blue-50 transition-all duration-300 @error('username') border-red-500 @enderror" placeholder="Username" value="{{ old('username') }}">
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-black">Password*</label>
                    <input type="password" name="password" class="w-full p-3 border border-gray-200 rounded-xl focus:border-2 focus:border-[#06B3BF] focus:ring-1 focus:ring-[#06B3BF] focus:bg-blue-50 transition-all duration-300 @error('password') border-red-500 @enderror" placeholder="Password">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-8 flex justify-end">
                <a href="{{ route('petugas.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-3 rounded-xl font-semibold shadow hover:shadow-md transform hover:scale-105 transition-all duration-300 border border-transparent hover:border-white mr-4">Kembali</a>
                <button type="submit" class="bg-gradient-to-r from-[#63BE9A] to-[#06B3BF] hover:from-[#06B3BF] hover:to-[#63BE9A] text-white px-6 py-3 rounded-xl font-semibold shadow hover:shadow-lg transform hover:scale-105 transition-all duration-300 border border-transparent hover:border-white">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection 