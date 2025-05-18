<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Posyandu Mahoni 54</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="h-screen flex items-center justify-center bg-gradient-to-br from-[#63BE9A] via-[#FFA4D3] to-[#06B3BF]">
    <div class="w-full max-w-4xl mx-4 flex flex-col md:flex-row items-center bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden">
        <!-- Bagian Kiri: Form Login -->
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-white shadow-lg">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">POSYANDU MAHONI 54</h3>
                <p class="text-gray-600">Selamat datang kembali!</p>
            </div>
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <div class="flex items-center bg-white p-3 rounded-xl shadow-sm border border-gray-200 transition-all duration-300 hover:border-blue-400">
                        <span class="text-gray-400 px-3 text-xl">👤</span>
                        <input type="text" name="nik" class="w-full p-2 focus:outline-none" placeholder="NIK atau Email" required value="{{ old('nik') }}">
                    </div>
                    <div class="flex items-center bg-white p-3 rounded-xl shadow-sm border border-gray-200 transition-all duration-300 hover:border-blue-400">
                        <span class="text-gray-400 px-3 text-xl">🔒</span>
                        <input type="password" name="password" class="w-full p-2 focus:outline-none" placeholder="Password" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-4 rounded-xl font-medium hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Masuk
                </button>
            </form>
        </div>
        
        <!-- Bagian Kanan: Ilustrasi -->
        <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-500 to-blue-600 p-8 md:p-12 flex items-center justify-center">
            <img src="{{ asset('images/ilustrasi.png') }}" alt="Illustration" class="max-w-full h-auto transform hover:scale-105 transition-transform duration-300">
        </div>
    </div>
</body>
</html>
