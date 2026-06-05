<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPEKA - Lacak Pengaduan</title>
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
                <img src="{{ asset('images/logo.png') }}" alt="SIPEKA" class="h-9 w-9 rounded-full ring-2 ring-white/30">
                <span class="text-white text-xl font-extrabold">SIPEKA</span>
            </a>
            <div class="flex gap-2">
                <a href="{{ route('login') }}" class="px-4 py-2 text-white border border-white/30 rounded-xl hover:bg-white/10 transition text-sm font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-white text-primary-600 rounded-xl text-sm font-bold hover:bg-gray-100 transition">Daftar</a>
            </div>
        </nav>
        <div class="max-w-7xl mx-auto px-4 py-12 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">🔍 Lacak Pengaduan</h1>
            <p class="text-white/80">Masukkan kode tracking untuk melihat status laporan Anda</p>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 mb-8">
            <form method="POST" class="flex gap-3">
                @csrf
                <input type="text" name="kode" value="{{ old('kode') }}" placeholder="Masukkan kode tracking (contoh: SPK-A1B2C3D4)" class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Cari</button>
            </form>
            @error('kode')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
            @if (session('error'))<p class="text-red-600 text-sm mt-2">{{ session('error') }}</p>@endif
        </div>

        @if (isset($pengaduan))
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-sm text-gray-400">Kode Tracking</p>
                        <p class="text-lg font-extrabold text-primary-600">{{ $pengaduan->kode_tracking }}</p>
                    </div>
                    <span class="text-sm px-3 py-1.5 rounded-full font-bold
                        @if($pengaduan->status == 'menunggu') bg-yellow-100 text-yellow-700
                        @elseif($pengaduan->status == 'diverifikasi') bg-blue-100 text-blue-700
                        @elseif($pengaduan->status == 'diproses') bg-indigo-100 text-indigo-700
                        @elseif($pengaduan->status == 'selesai') bg-accent-100 text-accent-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ ucfirst($pengaduan->status) }}
                    </span>
                </div>

                <h2 class="font-extrabold text-xl text-gray-900 mb-2">{{ $pengaduan->judul }}</h2>
                <p class="text-sm text-gray-400 mb-4">{{ $pengaduan->kategori?->nama_kategori ?? '-' }} &middot; {{ $pengaduan->created_at->format('d/m/Y H:i') }}</p>
                <p class="text-gray-700 mb-6">{{ $pengaduan->isi_laporan }}</p>

                @if ($pengaduan->media->count() > 0)
                    <div class="flex gap-2 mb-6 flex-wrap">
                        @foreach ($pengaduan->media as $m)
                            <a href="{{ Storage::url($m->file_path) }}" target="_blank" class="text-sm text-primary-600 font-bold hover:underline">📎 {{ $m->file_type === 'foto' ? 'Foto' : 'Video' }}</a>
                        @endforeach
                    </div>
                @endif

                <div class="border-t pt-6">
                    <h3 class="font-bold text-gray-900 mb-4">📋 Riwayat Status</h3>
                    <div class="space-y-4">
                        @foreach ($pengaduan->riwayats as $r)
                            <div class="flex gap-3">
                                <div class="w-3 h-3 mt-1.5 rounded-full shrink-0
                                    @if($r->status == 'selesai') bg-accent-500
                                    @elseif($r->status == 'ditolak') bg-red-500
                                    @else bg-primary-500 @endif"></div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($r->status) }}</p>
                                    <p class="text-sm text-gray-500">{{ $r->keterangan }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $r->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($pengaduan->tanggapans->count() > 0)
                    <div class="border-t pt-6 mt-6">
                        <h3 class="font-bold text-gray-900 mb-4">💬 Tanggapan Petugas</h3>
                        @foreach ($pengaduan->tanggapans as $t)
                            <div class="bg-gray-50 rounded-xl p-4 mb-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-xs font-bold">{{ substr($t->petugas?->name ?? 'P', 0, 1) }}</div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900">{{ $t->petugas?->name ?? 'Petugas' }}</p>
                                        <p class="text-xs text-gray-400">{{ $t->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700">{{ $t->isi_tanggapan }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($pengaduan->rating)
                    <div class="border-t pt-6 mt-6">
                        <h3 class="font-bold text-gray-900 mb-2">⭐ Rating</h3>
                        <div class="flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $pengaduan->rating->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        @if ($pengaduan->rating->komentar)
                            <p class="text-sm text-gray-500 mt-2">"{{ $pengaduan->rating->komentar }}"</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-400">
            <a href="/" class="hover:text-white transition">Kembali ke Beranda</a> &middot;
            &copy; {{ date('Y') }} SIPEKA
        </div>
    </footer>
</body>
</html>
