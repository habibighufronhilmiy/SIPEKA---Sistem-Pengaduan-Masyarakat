<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tentang Kami - SIPEKA</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .hero-grad { background: linear-gradient(135deg, #0f5fea 0%, #10b981 100%); }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <header class="hero-grad relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        <nav class="relative max-w-7xl mx-auto px-4 lg:px-6 py-3">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="SIPEKA" class="h-9 w-9 rounded-full ring-2 ring-white/30">
                    <span class="text-white text-xl font-extrabold">SIPEKA</span>
                </a>
                <div class="hidden lg:flex items-center gap-1">
                    <a href="{{ route('public.tracking') }}" class="px-3.5 py-2 text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition">Lacak Pengaduan</a>
                    <a href="{{ route('public.pengumuman') }}" class="px-3.5 py-2 text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition">Pengumuman</a>
                    <a href="{{ route('public.voting') }}" class="px-3.5 py-2 text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition">Hasil Voting</a>
                    <a href="{{ route('public.faq') }}" class="px-3.5 py-2 text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition">FAQ / Panduan</a>
                    <a href="{{ route('public.tentang') }}" class="px-3.5 py-2 text-sm font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition">Tentang Kami</a>
                </div>
                <div class="hidden lg:flex items-center gap-2">
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-white border border-white/30 rounded-xl hover:bg-white/10 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-bold bg-white text-primary-600 rounded-xl hover:bg-gray-100 transition shadow-lg shadow-black/10">Daftar</a>
                </div>
                <button id="menuBtn" class="lg:hidden p-2 text-white rounded-xl hover:bg-white/10 transition" aria-label="Menu">
                    <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </nav>
        <div id="mobileMenu" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="menuOverlay"></div>
            <div class="absolute top-0 right-0 w-72 h-full hero-grad p-6 shadow-2xl">
                <div class="flex justify-end mb-6">
                    <button id="closeBtn" class="p-2 text-white/80 hover:text-white rounded-xl hover:bg-white/10 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex flex-col gap-1">
                    <a href="{{ route('public.tracking') }}" class="block px-4 py-3 text-white/85 hover:text-white hover:bg-white/10 rounded-xl font-semibold transition">Lacak Pengaduan</a>
                    <a href="{{ route('public.pengumuman') }}" class="block px-4 py-3 text-white/85 hover:text-white hover:bg-white/10 rounded-xl font-semibold transition">Pengumuman</a>
                    <a href="{{ route('public.voting') }}" class="block px-4 py-3 text-white/85 hover:text-white hover:bg-white/10 rounded-xl font-semibold transition">Hasil Voting</a>
                    <a href="{{ route('public.faq') }}" class="block px-4 py-3 text-white/85 hover:text-white hover:bg-white/10 rounded-xl font-semibold transition">FAQ / Panduan</a>
                    <a href="{{ route('public.tentang') }}" class="block px-4 py-3 text-white/85 hover:text-white hover:bg-white/10 rounded-xl font-semibold transition">Tentang Kami</a>
                </div>
                <div class="mt-6 pt-6 border-t border-white/20 flex flex-col gap-3">
                    <a href="{{ route('login') }}" class="block text-center px-4 py-3 text-white border border-white/30 rounded-xl hover:bg-white/10 font-semibold transition">Masuk</a>
                    <a href="{{ route('register') }}" class="block text-center px-4 py-3 bg-white text-primary-600 rounded-xl font-bold hover:bg-gray-100 transition shadow-lg">Daftar</a>
                </div>
            </div>
        </div>
        <script>
            const menuBtn = document.getElementById('menuBtn');
            const closeBtn = document.getElementById('closeBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('menuOverlay');
            function openMenu() { mobileMenu.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
            function closeMenu() { mobileMenu.classList.add('hidden'); document.body.style.overflow = ''; }
            menuBtn?.addEventListener('click', openMenu);
            closeBtn?.addEventListener('click', closeMenu);
            overlay?.addEventListener('click', closeMenu);
        </script>
        <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-20 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Tentang Kami</h1>
            <p class="text-lg text-white/80 max-w-xl mx-auto">SIPEKA — Sistem Informasi Pengaduan, Aspirasi, dan Partisipasi Masyarakat</p>
        </div>
        <div class="relative h-12 md:h-16">
            <div class="absolute bottom-0 left-0 right-0 h-full bg-gray-50" style="border-radius: 100% 100% 0 0 / 30px 30px 0 0;"></div>
        </div>
    </header>

    <section class="py-16 max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 md:p-12">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-6">Apa itu SIPEKA? 🤔</h2>
            <p class="text-gray-600 leading-relaxed mb-6">
                SIPEKA adalah platform digital yang memfasilitasi masyarakat untuk menyampaikan pengaduan, aspirasi, kritik, dan saran secara online. Dibangun dengan semangat transparansi dan responsivitas, SIPEKA memungkinkan setiap suara masyarakat didengar dan ditindaklanjuti oleh petugas terkait.
            </p>
            <p class="text-gray-600 leading-relaxed mb-6">
                Dilengkapi dengan teknologi verifikasi AI, sistem tracking real-time, dan fitur partisipasi seperti voting, SIPEKA hadir sebagai solusi modern untuk meningkatkan kualitas pelayanan publik dan pembangunan desa.
            </p>

            <h3 class="text-xl font-extrabold text-gray-900 mt-10 mb-5">🎯 Misi Kami</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="p-5 bg-primary-50 rounded-2xl border border-primary-100">
                    <p class="font-bold text-gray-900">Transparansi</p>
                    <p class="text-sm text-gray-600 mt-1">Setiap laporan dapat dilacak perkembangannya secara publik.</p>
                </div>
                <div class="p-5 bg-accent-50 rounded-2xl border border-accent-100">
                    <p class="font-bold text-gray-900">Responsivitas</p>
                    <p class="text-sm text-gray-600 mt-1">Petugas menindaklanjuti setiap pengaduan dengan cepat.</p>
                </div>
                <div class="p-5 bg-purple-50 rounded-2xl border border-purple-100">
                    <p class="font-bold text-gray-900">Partisipasi</p>
                    <p class="text-sm text-gray-600 mt-1">Masyarakat aktif berkontribusi melalui voting dan aspirasi.</p>
                </div>
                <div class="p-5 bg-yellow-50 rounded-2xl border border-yellow-100">
                    <p class="font-bold text-gray-900">Inovasi</p>
                    <p class="text-sm text-gray-600 mt-1">Verifikasi AI untuk efisiensi dan akurasi penanganan.</p>
                </div>
            </div>

            <h3 class="text-xl font-extrabold text-gray-900 mt-10 mb-5">📊 Capaian Kami</h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-5 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-2xl font-extrabold text-primary-600">{{ $totalPengaduan }}</p>
                    <p class="text-xs text-gray-500 mt-1">Laporan Masuk</p>
                </div>
                <div class="text-center p-5 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-2xl font-extrabold text-accent-600">{{ $selesai }}</p>
                    <p class="text-xs text-gray-500 mt-1">Laporan Selesai</p>
                </div>
                <div class="text-center p-5 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-2xl font-extrabold text-purple-600">{{ $totalUser }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pengguna</p>
                </div>
                <div class="text-center p-5 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-2xl font-extrabold text-orange-600">{{ $totalKritik }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kritik & Saran</p>
                </div>
                <div class="text-center p-5 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-2xl font-extrabold text-teal-600">{{ $totalPetugas }}</p>
                    <p class="text-xs text-gray-500 mt-1">Petugas</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="SIPEKA" class="h-8 w-8 rounded-full">
                        <span class="text-xl font-extrabold">SIPEKA</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">Sistem Informasi Pengaduan, Aspirasi, dan Partisipasi Masyarakat yang transparan dan responsif.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-3">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar</a></li>
                        <li><a href="{{ route('public.tracking') }}" class="hover:text-white transition">Lacak Pengaduan</a></li>
                        <li><a href="{{ route('public.pengumuman') }}" class="hover:text-white transition">Pengumuman</a></li>
                        <li><a href="{{ route('public.voting') }}" class="hover:text-white transition">Hasil Voting</a></li>
                        <li><a href="{{ route('public.faq') }}" class="hover:text-white transition">FAQ / Panduan</a></li>
                        <li><a href="{{ route('public.tentang') }}" class="hover:text-white transition">Tentang Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-3">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>📧 hello@sipeka.test</li>
                        <li>📞 (021) 1234-5678</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} SIPEKA. Dibuat dengan ❤️ untuk masyarakat.
            </div>
        </div>
    </footer>
</body>
</html>
