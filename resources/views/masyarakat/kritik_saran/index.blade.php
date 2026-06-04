<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">💬 Kritik & Saran</h1>
            <p class="text-gray-500 text-sm mt-1">Sampaikan kritik, saran, atau aspirasi Anda</p>
        </div>

    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-accent-50 border border-accent-200 rounded-xl text-accent-800 text-sm font-semibold">{{ session('success') }}</div>
    @endif

    <div class="space-y-4">
        @forelse ($kritikSarans as $k)
            <a href="{{ route('kritik-saran.show', $k) }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-gray-200 transition">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                            @if($k->kategori == 'kritik') bg-red-100 text-red-700
                            @elseif($k->kategori == 'saran') bg-blue-100 text-blue-700
                            @else bg-purple-100 text-purple-700 @endif">
                            {{ ucfirst($k->kategori) }}
                        </span>
                        <h3 class="font-bold text-gray-900">{{ $k->judul }}</h3>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold shrink-0 ml-2
                        @if($k->status == 'menunggu') bg-yellow-100 text-yellow-700
                        @elseif($k->status == 'ditanggapi') bg-blue-100 text-blue-700
                        @else bg-accent-100 text-accent-700 @endif">
                        {{ ucfirst($k->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($k->isi_kritik, 120) }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $k->created_at->format('d/m/Y H:i') }}</p>
            </a>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-4xl mb-3">💬</p>
                <p class="text-gray-400 font-semibold">Belum ada kritik atau saran</p>
                <a href="{{ route('kritik-saran.create') }}" class="inline-block mt-4 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Tulis sekarang</a>
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $kritikSarans->links() }}</div>
</x-app-layout>
