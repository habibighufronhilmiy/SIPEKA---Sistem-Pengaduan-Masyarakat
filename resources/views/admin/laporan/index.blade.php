<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">📊 Laporan & Export</h1>
        <p class="text-gray-500 text-sm mt-1">Filter dan export data pengaduan</p>
    </div>

    <form method="GET" action="{{ route('admin.laporan') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="grid md:grid-cols-4 gap-4 mb-4">
            <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Kategori</label>
                <select name="kategori" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:ring-2 focus:ring-primary-500">
                    <option value="">Semua</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diverifikasi" {{ request('status') == 'diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Dari Tanggal</label><input type="date" name="dari" value="{{ request('dari') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:ring-2 focus:ring-primary-500"></div>
            <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Sampai Tanggal</label><input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white text-sm focus:ring-2 focus:ring-primary-500"></div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
            <button type="submit" name="export" value="pdf" class="px-5 py-2.5 bg-red-500 text-white rounded-xl text-sm font-bold hover:bg-red-600 transition shadow-sm">Export PDF</button>
            <button type="submit" name="export" value="excel" class="px-5 py-2.5 bg-accent-500 text-white rounded-xl text-sm font-bold hover:bg-accent-600 transition shadow-sm">Export Excel</button>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left p-4 text-sm font-bold text-gray-600">No</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Judul</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Pelapor</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Kategori</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Status</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengaduans as $i => $p)
                        <tr class="border-t border-gray-100">
                            <td class="p-4 text-sm text-gray-600 font-semibold">{{ $i + 1 }}</td>
                            <td class="p-4 font-semibold text-gray-900">{{ $p->judul }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $p->user?->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $p->kategori?->nama_kategori ?? '-' }}</td>
                            <td class="p-4"><span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                @if($p->status == 'menunggu') bg-yellow-100 text-yellow-700
                                @elseif($p->status == 'diproses') bg-indigo-100 text-indigo-700
                                @elseif($p->status == 'selesai') bg-accent-100 text-accent-700
                                @else bg-red-100 text-red-700 @endif">{{ ucfirst($p->status) }}</span></td>
                            <td class="p-4 text-sm text-gray-500">{{ $p->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-10 text-center text-gray-400">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
