<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SEKECAM - Sistem Elektronik Keluhan dan Aspirasi Kecamatan</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .hero-grad { background: #0f5fea; }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15); }
        .float-anim { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }

    </style>
</head>
<body class="bg-white antialiased">
    <nav class="sticky top-0 z-50 bg-[#0f5fea]/90 backdrop-blur-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 lg:px-6">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5 shrink-0 group">
                    <img src="{{ asset('images/logo.png') }}" alt="SEKECAM" class="h-8 w-8 lg:h-9 lg:w-9 rounded-full ring-2 ring-white/30 group-hover:ring-white/50 transition">
                    <span class="text-white text-lg lg:text-xl font-extrabold tracking-tight">SEKECAM</span>
                </a>
                <div class="hidden lg:flex items-center gap-0.5">
                    <a href="{{ route('login') }}" class="relative px-3.5 py-2 text-sm font-medium text-white/70 hover:text-white transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="relative px-3.5 py-2 text-sm font-medium text-white/70 hover:text-white transition-colors">Daftar</a>
                    <span class="w-px h-4 bg-white/15 mx-2"></span>
                    <a href="{{ route('public.tracking') }}" class="relative px-3 py-2 text-sm font-medium text-white/70 hover:text-white transition-colors">Lacak Pengaduan</a>
                    <a href="{{ route('public.pengumuman') }}" class="relative px-3 py-2 text-sm font-medium text-white/70 hover:text-white transition-colors">Pengumuman</a>
                    <a href="{{ route('public.voting') }}" class="relative px-3 py-2 text-sm font-medium text-white/70 hover:text-white transition-colors">Hasil Voting</a>
                    <a href="{{ route('public.faq') }}" class="relative px-3 py-2 text-sm font-medium text-white/70 hover:text-white transition-colors">FAQ / Panduan</a>
                    <a href="{{ route('public.tentang') }}" class="relative px-3 py-2 text-sm font-medium text-white/70 hover:text-white transition-colors">Tentang Kami</a>
                </div>
                <div class="flex lg:hidden items-center gap-2">
                    <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-semibold text-white/80 border border-white/20 rounded-lg hover:bg-white/10 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-3.5 py-1.5 text-xs font-bold bg-white text-[#0f5fea] rounded-lg hover:bg-white/90 transition shadow-lg">Daftar</a>
                </div>
            </div>
            <div class="flex lg:hidden items-center justify-start gap-1.5 pb-3 overflow-x-auto scrollbar-none -mx-4 px-4">
                <a href="{{ route('login') }}" class="shrink-0 px-3 py-1.5 text-xs font-medium text-white/70 bg-white/10 rounded-lg hover:bg-white/20 hover:text-white transition whitespace-nowrap">Masuk</a>
                <a href="{{ route('register') }}" class="shrink-0 px-3 py-1.5 text-xs font-bold text-white bg-white/20 rounded-lg hover:bg-white/30 transition whitespace-nowrap">Daftar</a>
                <a href="{{ route('public.tracking') }}" class="shrink-0 px-3 py-1.5 text-xs font-medium text-white/70 bg-white/10 rounded-lg hover:bg-white/20 hover:text-white transition whitespace-nowrap">Lacak</a>
                <a href="{{ route('public.pengumuman') }}" class="shrink-0 px-3 py-1.5 text-xs font-medium text-white/70 bg-white/10 rounded-lg hover:bg-white/20 hover:text-white transition whitespace-nowrap">Pengumuman</a>
                <a href="{{ route('public.voting') }}" class="shrink-0 px-3 py-1.5 text-xs font-medium text-white/70 bg-white/10 rounded-lg hover:bg-white/20 hover:text-white transition whitespace-nowrap">Voting</a>
                <a href="{{ route('public.faq') }}" class="shrink-0 px-3 py-1.5 text-xs font-medium text-white/70 bg-white/10 rounded-lg hover:bg-white/20 hover:text-white transition whitespace-nowrap">FAQ</a>
                <a href="{{ route('public.tentang') }}" class="shrink-0 px-3 py-1.5 text-xs font-medium text-white/70 bg-white/10 rounded-lg hover:bg-white/20 hover:text-white transition whitespace-nowrap">Tentang</a>
            </div>
        </div>
    </nav>

    <header class="hero-grad relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 40px 40px;"></div>

        <section class="relative max-w-7xl mx-auto px-4 py-20 md:py-28">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="text-center md:text-left">
                    <span class="inline-block px-4 py-1.5 bg-white/20 text-white text-sm font-semibold rounded-full mb-6">💡 Platform Aspirasi Digital</span>
                    <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-4">
                        Wujudkan <span class="text-yellow-300">Perubahan</span><br>Bersama Kami
                    </h1>
                    <p class="text-lg md:text-xl text-white/80 max-w-xl mb-8">
                        Laporkan keluhan, aspirasi, dan partisipasi untuk pembangunan kota yang lebih transparan dan responsif.
                    </p>
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <a href="{{ route('register') }}" class="px-8 py-3.5 bg-white text-primary-600 rounded-xl font-extrabold hover:bg-gray-100 transition shadow-xl shadow-black/20 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            Buat Laporan
                        </a>
                        <a href="{{ route('public.tracking') }}" class="px-8 py-3.5 border-2 border-white/30 text-white rounded-xl font-bold hover:bg-white/10 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            Lacak Laporan
                        </a>
                    </div>
                </div>
                <div class="flex justify-center items-center">
                    <div class="relative w-full max-w-lg">
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl ring-8 ring-white/20 aspect-video">
                            <iframe src="https://www.youtube.com/embed/DEo1_Av7at8" title="SEKECAM" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="absolute inset-0 w-full h-full"></iframe>
                        </div>
                        <div class="absolute -bottom-4 -right-4 bg-white rounded-2xl p-4 shadow-xl">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-accent-500 animate-pulse"></span>
                                <span class="font-bold text-gray-900">Aktif 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="relative h-16 md:h-24">
            <div class="absolute bottom-0 left-0 right-0 h-full bg-white" style="border-radius: 100% 100% 0 0 / 40px 40px 0 0;"></div>
        </div>
    </header>

    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-accent-50 text-accent-600 text-sm font-semibold rounded-full mb-4">✨ FITUR UNGGULAN</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Kenapa Memilih SEKECAM?</h2>
                <p class="text-gray-500 mt-3 max-w-lg mx-auto">Platform pengaduan modern yang dirancang untuk kemudahan dan transparansi.</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group bg-white p-8 rounded-2xl border border-gray-100 card-hover shadow-sm">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="font-bold text-xl mb-2 text-gray-900">📋 Laporan Online</h3>
                    <p class="text-gray-500 leading-relaxed">Laporkan keluhan dengan mudah, lengkap foto, lokasi, dan kategori. Diverifikasi AI otomatis.</p>
                </div>
                <div class="group bg-white p-8 rounded-2xl border border-gray-100 card-hover shadow-sm">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-accent-100 to-accent-50 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 class="font-bold text-xl mb-2 text-gray-900">🔍 Tracking Publik</h3>
                    <p class="text-gray-500 leading-relaxed">Pantau laporan dengan kode tracking unik. Cek status kapan saja, tanpa perlu login!</p>
                </div>
                <div class="group bg-white p-8 rounded-2xl border border-gray-100 card-hover shadow-sm">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    </div>
                    <h3 class="font-bold text-xl mb-2 text-gray-900">💬 Kritik & Saran</h3>
                    <p class="text-gray-500 leading-relaxed">Sampaikan kritik, saran, atau aspirasi. Setiap masukan akan ditanggapi langsung oleh petugas.</p>
                </div>
                <div class="group bg-white p-8 rounded-2xl border border-gray-100 card-hover shadow-sm">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-50 flex items-center justify-center mb-5 group-hover:scale-110 transition">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-xl mb-2 text-gray-900">🗳️ Voting & Aspirasi</h3>
                    <p class="text-gray-500 leading-relaxed">Partisipasi dalam voting daerah. Hasil transparan dan bisa dilihat oleh seluruh masyarakat.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-14">
                <span class="inline-block px-4 py-1.5 bg-primary-50 text-primary-600 text-sm font-semibold rounded-full mb-4">👥 PENGGUNA PLATFORM</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900">Untuk Siapa SEKECAM?</h2>
                <p class="text-gray-500 mt-3 max-w-lg mx-auto">Setiap peran memiliki pengalaman yang disesuaikan dengan kebutuhan.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                    <div class="p-6 lg:p-8">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2">Masyarakat</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-5">Laporkan keluhan, kirim kritik & saran, ikut voting, dan pantau laporan secara real-time.</p>
                        <ul class="space-y-2.5">
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">✓</span> Buat laporan pengaduan</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">✓</span> Lacak status laporan</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">✓</span> Kirim kritik & saran</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">✓</span> Partisipasi voting</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">✓</span> Beri rating & tanggapan</li>
                        </ul>
                        <div class="mt-6 pt-5 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akses Publik</span>
                                <span class="px-2.5 py-0.5 text-xs font-bold text-blue-600 bg-blue-50 rounded-full">Gratis</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <div class="p-6 lg:p-8">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2">Petugas</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-5">Verifikasi, proses, dan tanggapi laporan masyarakat dengan sistem yang terintegrasi.</p>
                        <ul class="space-y-2.5">
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">✓</span> Verifikasi laporan masuk</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">✓</span> Proses & tindak lanjut</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">✓</span> Beri tanggapan resmi</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">✓</span> Kelola status pengaduan</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">✓</span> Dashboard monitoring</li>
                        </ul>
                        <div class="mt-6 pt-5 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akses Terbatas</span>
                                <span class="px-2.5 py-0.5 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-full">Petugas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-purple-500 to-purple-600"></div>
                    <div class="p-6 lg:p-8">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2">Admin</h3>
                        <p class="text-gray-500 text-sm leading-relaxed mb-5">Kelola pengguna, atur kategori, monitoring laporan, dan lihat laporan audit secara lengkap.</p>
                        <ul class="space-y-2.5">
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">✓</span> Kelola pengguna</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">✓</span> Atur kategori pengaduan</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">✓</span> Assign petugas</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">✓</span> Laporan & statistik</li>
                            <li class="flex items-center gap-2.5 text-sm text-gray-600"><span class="w-5 h-5 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xs font-bold">✓</span> Audit log aktivitas</li>
                        </ul>
                        <div class="mt-6 pt-5 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akses Penuh</span>
                                <span class="px-2.5 py-0.5 text-xs font-bold text-purple-600 bg-purple-50 rounded-full">Admin</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 hero-grad relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 75% 75%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="relative max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Siap Bergabung? 🎉</h2>
            <p class="text-lg text-white/80 mb-8 max-w-lg mx-auto">Bantu kami membangun kota yang lebih baik. Satu laporan bisa mengubah segalanya!</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-primary-600 rounded-xl font-extrabold hover:bg-gray-100 transition shadow-2xl shadow-black/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                Daftar Sekarang — Gratis!
            </a>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="SEKECAM" class="h-8 w-8 rounded-full">
                        <span class="text-xl font-extrabold">SEKECAM</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">Sistem Elektronik Keluhan dan Aspirasi Kecamatan yang transparan dan responsif.</p>
                    <div class="flex items-center gap-3 mt-4">
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-primary-600 flex items-center justify-center transition"><svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.6c-.9.4-1.8.7-2.8.8 1-.6 1.8-1.6 2.2-2.7-1 .6-2 1-3.1 1.2-.9-1-2.2-1.6-3.6-1.6-2.7 0-4.9 2.2-4.9 4.9 0 .4 0 .8.1 1.1C7.7 8.1 4.1 6.1 1.7 3.1c-.4.7-.7 1.6-.7 2.5 0 1.7.9 3.2 2.2 4.1-.8 0-1.6-.2-2.2-.6v.1c0 2.4 1.7 4.4 3.9 4.8-.4.1-.8.2-1.3.2-.3 0-.6 0-.9-.1.6 2 2.4 3.4 4.6 3.4-1.7 1.3-3.8 2.1-6.1 2.1-.4 0-.8 0-1.2-.1 2.2 1.4 4.8 2.2 7.5 2.2 9.1 0 14-7.5 14-14v-.6c1-.7 1.8-1.6 2.5-2.6z"/></svg></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-primary-600 flex items-center justify-center transition"><svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.2c6.6 0 12 5.4 12 12 0 5.3-3.4 9.8-8.2 11.4-.6.1-.8-.3-.8-.6v-4c0-1.4-.5-2.3-1-2.8 3.3-.4 6.8-1.6 6.8-7.4 0-1.6-.6-3-1.5-4-.1-.4-.7-2 .2-4.2 0 0 1.3-.4 4.2 1.6 1.2-.3 2.5-.5 3.8-.5s2.6.2 3.8.5c2.9-2 4.2-1.6 4.2-1.6.9 2.2.3 3.8.2 4.2 1 1 1.5 2.4 1.5 4 0 5.7-3.4 6.9-6.7 7.3.5.4 1 1.2 1 2.5v3.7c0 .3.2.7.8.6 4.8-1.6 8.2-6.1 8.2-11.4 0-6.6-5.4-12-12-12z"/></svg></a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-primary-600 flex items-center justify-center transition"><svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M23.5 6.2c-.3-1-1-1.8-2-2.1C19.7 3.5 12 3.5 12 3.5s-7.7 0-9.5.6c-1 .3-1.7 1.1-2 2.1C0 8.1 0 12 0 12s0 3.9.5 5.8c.3 1 1 1.8 2 2.1 1.8.6 9.5.6 9.5.6s7.7 0 9.5-.6c1-.3 1.7-1.1 2-2.1.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8zM9.5 15.5V8.5l6.4 3.5-6.4 3.5z"/></svg></a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold mb-3">Kontak</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center shrink-0">📧</span> hello@sekecam.test</li>
                        <li class="flex items-center gap-2"><span class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center shrink-0">📞</span> (021) 1234-5678</li>

                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} SEKECAM. Dibuat dengan ❤️ untuk masyarakat.
            </div>
        </div>
    </footer>
</body>
</html>
