<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">📋 Riwayat Pengaduan</h1>
            <p class="text-gray-500 text-sm mt-1">Semua laporan yang pernah kamu buat</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pengaduan.create') }}?draft=1" class="inline-flex items-center gap-1.5 px-4 py-2.5 border-2 border-gray-200 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Draft
            </a>
            <a href="{{ route('pengaduan.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Baru
            </a>
        </div>
    </div>

    <div class="flex gap-2 mb-5">
        <a href="{{ route('pengaduan.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ !$filter ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Semua</a>
        <a href="{{ route('pengaduan.index', ['filter' => 'draft']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $filter === 'draft' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Draft</a>
        <a href="{{ route('pengaduan.index', ['filter' => 'submitted']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $filter === 'submitted' ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Terkirim</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Judul</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Kategori</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Status</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Tanggal</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengaduans as $p)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                            <td class="p-4 font-semibold text-gray-900">
                                {{ $p->judul }}
                                @if ($p->draft)
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-bold ml-1.5">DRAFT</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-gray-600">{{ $p->kategori->nama_kategori ?? '-' }}</td>
                            <td class="p-4">
                                @if ($p->draft)
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-yellow-100 text-yellow-700">Draft</span>
                                @else
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                        @if($p->status == 'menunggu') bg-yellow-100 text-yellow-700
                                        @elseif($p->status == 'diverifikasi') bg-blue-100 text-blue-700
                                        @elseif($p->status == 'diproses') bg-indigo-100 text-indigo-700
                                        @elseif($p->status == 'selesai') bg-accent-100 text-accent-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($p->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-gray-500">{{ $p->created_at->format('d/m/Y') }}</td>
                            <td class="p-4">
                                @if ($p->draft)
                                    <form action="{{ route('pengaduan.submit', $p) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 text-accent-600 text-sm font-bold hover:text-accent-700" onclick="return confirm('Kirim draft ini untuk diproses?')">
                                            Kirim <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('pengaduan.show', $p) }}" class="inline-flex items-center gap-1 text-primary-600 text-sm font-bold hover:text-primary-700">
                                        Detail <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-10 text-center text-gray-400">Belum ada pengaduan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $pengaduans->links() }}</div>
</x-app-layout>
