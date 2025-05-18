<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Posyandu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex">
        @include('layouts.sidebar')

        <div class="w-full p-3">
            @yield('content')
        </div>
    </div>

    <script>
        // Cek jika ada pesan kesalahan
        @if(session('error'))
            alert("{{ session('error') }}");
        @endif
    </script>
</body>
</html>
