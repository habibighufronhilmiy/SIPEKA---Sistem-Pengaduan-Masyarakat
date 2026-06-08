<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPEKA - Pengumuman</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .hero-grad { background: linear-gradient(135deg, #0f5fea 0%, #10b981 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15); }
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
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">📢 Pengumuman</h1>
            <p class="text-white/80">Informasi terbaru dari desa</p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($pengumumen as $p)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden card-hover">
                    @if ($p->foto)
                        <img src="{{ asset('storage/' . $p->foto) }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-6">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-blue-100 text-blue-700">{{ ucfirst($p->tipe) }}</span>
                        <h3 class="font-bold text-lg text-gray-900 mt-3 mb-2">{{ $p->judul }}</h3>
                        <p class="text-gray-500 text-sm line-clamp-3">{{ Str::limit($p->isi, 150) }}</p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <span class="text-xs text-gray-400">{{ $p->created_at->format('d/m/Y') }}</span>
                            @if ($p->lokasi)<span class="text-xs text-gray-400">📍 {{ $p->lokasi }}</span>@endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 text-center py-16 text-gray-400">
                    <p class="text-4xl mb-3">📢</p>
                    <p class="font-semibold">Belum ada pengumuman</p>
                </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $pengumumen->links() }}</div>
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-400">
            <a href="/" class="hover:text-white transition">Kembali ke Beranda</a> &middot;
            &copy; {{ date('Y') }} SIPEKA
        </div>
    </footer>
</body>
</html>
