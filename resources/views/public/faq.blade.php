<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SEKECAM - FAQ / Panduan</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .hero-grad { background: linear-gradient(135deg, #0f5fea 0%, #10b981 100%); }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <header class="hero-grad">
        <nav class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="SEKECAM" class="h-9 w-9 rounded-full ring-2 ring-white/30">
                <span class="text-white text-xl font-extrabold">SEKECAM</span>
            </a>
            <div class="flex gap-2">
                <a href="{{ route('login') }}" class="px-4 py-2 text-white border border-white/30 rounded-xl hover:bg-white/10 transition text-sm font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-white text-primary-600 rounded-xl text-sm font-bold hover:bg-gray-100 transition">Daftar</a>
            </div>
        </nav>
        <div class="max-w-7xl mx-auto px-4 py-12 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">❓ Panduan & FAQ</h1>
            <p class="text-white/80">Semua yang perlu Anda ketahui tentang SEKECAM</p>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-8">
        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-1">📝 Apa itu SEKECAM?</h3>
                <p class="text-gray-600">SEKECAM adalah Sistem Elektronik Keluhan dan Aspirasi Kecamatan. Platform ini memungkinkan warga untuk melaporkan keluhan, memberikan saran/kritik, dan berpartisipasi dalam voting desa secara digital.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-1">👤 Bagaimana cara membuat laporan?</h3>
                <p class="text-gray-600">Daftar akun gratis (atau login dengan Google), klik "Buat Laporan", isi detail keluhan beserta foto dan lokasi, lalu submit. Laporan akan diverifikasi otomatis oleh AI dan diteruskan ke petugas.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-1">🔍 Bagaimana cara melacak laporan?</h3>
                <p class="text-gray-600">Setelah membuat laporan, Anda akan mendapatkan <strong>kode tracking</strong> (contoh: SPK-A1B2C3D4). Masukkan kode tersebut di halaman <a href="{{ route('public.tracking') }}" class="text-primary-600 font-bold hover:underline">Lacak Pengaduan</a> untuk melihat status terbaru tanpa perlu login.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-1">💬 Apa itu Kritik & Saran?</h3>
                <p class="text-gray-600">Fitur untuk menyampaikan kritik, saran, atau aspirasi secara tertulis. Berbeda dengan laporan yang bersifat keluhan spesifik, Kritik & Saran lebih umum dan akan ditanggapi langsung oleh petugas.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-1">🗳️ Bagaimana cara voting?</h3>
                <p class="text-gray-600">Fitur voting memungkinkan masyarakat untuk memilih opsi terbaik dalam suatu pemungutan suara desa. Hasil voting bersifat transparan dan bisa dilihat oleh siapa saja.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-1">🔒 Apakah data saya aman?</h3>
                <p class="text-gray-600">Ya. Data Anda dilindungi dan hanya digunakan untuk keperluan pelayanan pengaduan. Kami tidak membagikan data pribadi Anda ke pihak ketiga.</p>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-400">
            <a href="/" class="hover:text-white transition">Kembali ke Beranda</a> &middot;
            &copy; {{ date('Y') }} SEKECAM
        </div>
    </footer>
</body>
</html>
