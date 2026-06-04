<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Halo, {{ auth()->user()->name }}! 🛡️</h1>
        <p class="text-gray-500 mt-1">Dashboard petugas SIPEKA</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-400 text-sm font-medium">Total Tugas</span>
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ $totalTugas }}</p>
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
        <a href="{{ route('petugas.pengaduan.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Pengaduan
        </a>
        <a href="{{ route('voting.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Kelola Voting
        </a>
        <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Pengumuman
        </a>
        <a href="{{ route('kelola-kritik-saran.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            Kritik & Saran
        </a>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-lg text-gray-900 mb-5">📋 Tugas Saya</h2>
            <div class="space-y-3">
                @forelse ($pengaduans as $p)
                    <a href="{{ route('petugas.pengaduan.show', $p) }}" class="block p-4 border border-gray-100 rounded-xl hover:bg-gray-50 hover:border-gray-200 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <span class="font-bold text-gray-900">{{ $p->judul }}</span>
                                <p class="text-sm text-gray-400 mt-0.5">{{ $p->user->name }} &middot; {{ $p->kategori->nama_kategori }}</p>
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
                    <p class="text-gray-400 text-center py-8">Belum ada tugas</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-5">
                <h2 class="font-extrabold text-lg text-gray-900">📌 Semua Pengaduan</h2>
                <a href="{{ route('petugas.pengaduan.index') }}" class="text-sm text-primary-600 font-bold hover:text-primary-700">Lihat Semua →</a>
            </div>
            <div class="space-y-3">
                @forelse ($semuaPengaduan as $p)
                    <a href="{{ route('petugas.pengaduan.show', $p) }}" class="block p-4 border border-gray-100 rounded-xl hover:bg-gray-50 hover:border-gray-200 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1 min-w-0">
                                <span class="font-bold text-gray-900">{{ $p->judul }}</span>
                                <p class="text-sm text-gray-400 mt-0.5">{{ $p->user->name }} &middot; {{ $p->kategori->nama_kategori }}</p>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold shrink-0 ml-2
                                @if($p->status == 'menunggu') bg-yellow-100 text-yellow-700
                                @elseif($p->status == 'diverifikasi') bg-blue-100 text-blue-700
                                @else bg-accent-100 text-accent-700 @endif">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-400 text-center py-8">Semua pengaduan sudah ditangani</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
