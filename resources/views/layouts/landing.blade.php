<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KLINIKA — Sistem Manajemen Klinik & Rekam Medis Elektronik')</title>
    <meta name="description" content="@yield('description', 'KLINIKA menyatukan pendaftaran pasien, rekam medis elektronik (RME), manajemen antrean, farmasi, dan kasir dalam satu ruang kerja klinik yang terintegrasi.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-page="landing" class="min-h-screen bg-white text-[#182B35] font-sans antialiased selection:bg-[#E1F0ED] selection:text-[#087F83] overflow-x-hidden">
    <a href="#konten-utama"
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2.5 focus:rounded-lg focus:bg-[#087F83] focus:text-white focus:text-sm focus:font-semibold focus:shadow-lg">
        Lewati ke konten utama
    </a>

    @yield('content')
</body>
</html>
