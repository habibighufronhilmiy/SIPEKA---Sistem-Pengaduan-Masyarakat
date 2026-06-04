<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">💬 Kelola Kritik & Saran</h1>
        <p class="text-gray-500 text-sm mt-1">Tanggapi masukan dari masyarakat</p>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-yellow-100 shadow-sm">
            <p class="text-yellow-600 text-xs font-medium mb-1">Menunggu</p>
            <p class="text-3xl font-extrabold text-yellow-600">{{ $menunggu }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm">
            <p class="text-blue-600 text-xs font-medium mb-1">Ditanggapi</p>
            <p class="text-3xl font-extrabold text-blue-600">{{ $ditanggapi }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-accent-100 shadow-sm">
            <p class="text-accent-600 text-xs font-medium mb-1">Selesai</p>
            <p class="text-3xl font-extrabold text-accent-600">{{ $selesai }}</p>
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($kritikSarans as $k)
            <a href="{{ route('kelola-kritik-saran.show', $k) }}" class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md hover:border-gray-200 transition">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-2 flex-wrap">
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
                <div class="flex items-center gap-3 text-sm text-gray-400">
                    <span>{{ $k->user->name }}</span>
                    <span>&middot;</span>
                    <span>{{ $k->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ Str::limit($k->isi_kritik, 150) }}</p>
            </a>
        @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-4xl mb-3">💬</p>
                <p class="text-gray-400 font-semibold">Belum ada kritik atau saran dari masyarakat</p>
            </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $kritikSarans->links() }}</div>
</x-app-layout>
