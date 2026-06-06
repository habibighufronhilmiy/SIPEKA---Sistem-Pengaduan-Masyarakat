<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">📋 Semua Pengaduan</h1>
        <p class="text-gray-500 text-sm mt-1">Monitor seluruh laporan yang masuk</p>
    </div>

    <form class="mb-6 flex gap-3 flex-wrap" method="GET">
        <select name="kategori" class="px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:ring-2 focus:ring-primary-500">
            <option value="">Semua Kategori</option>
            @foreach ($kategoris as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:ring-2 focus:ring-primary-500">
            <option value="">Semua Status</option>
            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
            <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:ring-2 focus:ring-primary-500">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:ring-2 focus:ring-primary-500">
        <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter
        </button>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Judul</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Pelapor</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Kategori</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Petugas</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Status</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Tanggal</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengaduans as $p)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                            <td class="p-4 font-semibold text-gray-900">{{ $p->judul }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $p->user?->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $p->kategori?->nama_kategori ?? '-' }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $p->petugas->name ?? '-' }}</td>
                            <td class="p-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    @if($p->status == 'menunggu') bg-yellow-100 text-yellow-700
                                    @elseif($p->status == 'diverifikasi') bg-blue-100 text-blue-700
                                    @elseif($p->status == 'diproses') bg-indigo-100 text-indigo-700
                                    @elseif($p->status == 'selesai') bg-accent-100 text-accent-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-500">{{ $p->created_at->format('d/m/Y') }}</td>
                            <td class="p-4 flex gap-2">
                                <a href="{{ route('admin.pengaduan.show', $p) }}" class="text-primary-600 font-bold text-sm hover:text-primary-700">Detail</a>
                                <form action="{{ route('admin.pengaduan.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengaduan ini? Semua data terkait akan dihapus permanen.')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 font-bold text-sm hover:text-red-700">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="p-10 text-center text-gray-400">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $pengaduans->links() }}</div>
</x-app-layout>
