<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SEKECAM - Hasil Voting</title>
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
                <img src="{{ asset('images/logo.png') }}" alt="SEKECAM" class="h-9 w-9 rounded-full ring-2 ring-white/30">
                <span class="text-white text-xl font-extrabold">SEKECAM</span>
            </a>
            <div class="flex gap-2">
                <a href="{{ route('login') }}" class="px-4 py-2 text-white border border-white/30 rounded-xl hover:bg-white/10 transition text-sm font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-white text-primary-600 rounded-xl text-sm font-bold hover:bg-gray-100 transition">Daftar</a>
            </div>
        </nav>
        <div class="max-w-7xl mx-auto px-4 py-12 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">🗳️ Hasil Voting</h1>
            <p class="text-white/80">Transparansi aspirasi masyarakat</p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <div class="space-y-6">
            @forelse ($votings as $v)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-xl text-gray-900">{{ $v->pertanyaan }}</h3>
                            @if ($v->deskripsi)<p class="text-gray-500 mt-1">{{ $v->deskripsi }}</p>@endif
                        </div>
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold {{ $v->status == 'aktif' ? 'bg-accent-100 text-accent-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($v->status) }}</span>
                    </div>
                    <p class="text-xs text-gray-400 mb-4">{{ $v->tanggal_mulai->format('d/m/Y') }} - {{ $v->tanggal_selesai->format('d/m/Y') }}</p>

                    <div class="space-y-3">
                        @foreach ($v->pilihans as $p)
                            @php $suara = $p->users->count(); $totalSuara = $v->pilihans->sum(fn($x) => $x->users->count()); @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-semibold">{{ $p->pilihan }}</span>
                                    <span>{{ $suara }} suara ({{ $totalSuara > 0 ? round($suara / $totalSuara * 100) : 0 }}%)</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                    <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full transition-all" style="width: {{ $totalSuara > 0 ? ($suara / $totalSuara * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-sm text-gray-400 mt-4">Total partisipasi: {{ $v->pilihans->sum(fn($x) => $x->users->count()) }} suara</p>
                </div>
            @empty
                <div class="text-center py-16 text-gray-400">
                    <p class="text-4xl mb-3">🗳️</p>
                    <p class="font-semibold">Belum ada voting</p>
                </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $votings->links() }}</div>
    </main>

    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-400">
            <a href="/" class="hover:text-white transition">Kembali ke Beranda</a> &middot;
            &copy; {{ date('Y') }} SEKECAM
        </div>
    </footer>
</body>
</html>
