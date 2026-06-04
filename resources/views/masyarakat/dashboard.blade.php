<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Halo, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-500 mt-1">Selamat datang di dashboard SIPEKA</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-400 text-sm font-medium">Total Laporan</span>
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ $total }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-yellow-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-yellow-600 text-sm font-medium">Menunggu</span>
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-yellow-600">{{ $menunggu }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-blue-600 text-sm font-medium">Diproses</span>
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-blue-600">{{ $diproses }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-accent-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-accent-600 text-sm font-medium">Selesai</span>
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-accent-100 to-accent-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-accent-600">{{ $selesai }}</p>
        </div>
    </div>

    <div class="flex gap-3 flex-wrap mb-8">
        <a href="{{ route('pengaduan.create') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Laporan
        </a>
        <a href="{{ route('kritik-saran.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            Kritik & Saran
        </a>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-5">
                <h2 class="font-extrabold text-lg text-gray-900">📋 Laporan Terbaru</h2>
                <a href="{{ route('pengaduan.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Baru
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($pengaduans as $p)
                    <a href="{{ route('pengaduan.show', $p) }}" class="block p-4 border border-gray-100 rounded-xl hover:bg-gray-50 hover:border-gray-200 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <span class="font-bold text-gray-900">{{ $p->judul }}</span>
                                <p class="text-sm text-gray-400 mt-0.5">{{ $p->kategori->nama_kategori }} &middot; {{ $p->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold shrink-0 ml-2
                                @if($p->status == 'menunggu') bg-yellow-100 text-yellow-700
                                @elseif($p->status == 'diverifikasi') bg-blue-100 text-blue-700
                                @elseif($p->status == 'diproses') bg-indigo-100 text-indigo-700
                                @elseif($p->status == 'selesai') bg-accent-100 text-accent-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p>Belum ada laporan</p>
                        <a href="{{ route('pengaduan.create') }}" class="text-primary-600 font-bold text-sm mt-1 inline-block hover:underline">Buat laporan sekarang</a>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-extrabold text-lg text-gray-900 mb-5">📢 Pengumuman</h2>
                <div class="space-y-3">
                    @forelse ($pengumumen as $p)
                        <div class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                            <h3 class="font-bold text-gray-900">{{ $p->judul }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($p->isi, 100) }}</p>
                            <span class="text-xs text-gray-400 mt-2 inline-block">{{ $p->created_at->format('d M Y') }}</span>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">Belum ada pengumuman</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-extrabold text-lg text-gray-900">🗳️ Voting Aktif</h2>
                    <span class="text-xs px-2.5 py-1 bg-accent-100 text-accent-700 rounded-full font-semibold">Aktif</span>
                </div>
                <div class="space-y-3">
                    @forelse ($votings as $v)
                        <div class="p-4 border border-gray-100 rounded-xl">
                            <h3 class="font-bold text-gray-900">{{ $v->pertanyaan }}</h3>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-400">{{ $v->pilihans->count() }} pilihan</span>
                                <a href="{{ route('voting.show', $v) }}" class="text-sm text-primary-600 font-bold hover:text-primary-700">Ikut Voting →</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">Tidak ada voting aktif</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
