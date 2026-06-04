<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">📋 Riwayat Aktivitas</h1>
        <p class="text-gray-500 mt-1">Log aktivitas admin dan petugas</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Aksi</label>
                <input type="text" name="aksi" value="{{ request('aksi') }}" placeholder="Cari aksi..." class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50/50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Tipe</label>
                <select name="tipe" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50/50">
                    <option value="">Semua</option>
                    @foreach ($tipeList as $t)
                        <option value="{{ $t }}" {{ request('tipe') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Dari</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50/50">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Sampai</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50/50">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-bold hover:bg-primary-700 transition">Filter</button>
            <a href="{{ route('admin.audit') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-bold hover:bg-gray-200 transition">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Waktu</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">User</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Aksi</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Tipe</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Deskripsi</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                            <td class="p-4 text-sm text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4 text-sm font-semibold text-gray-900">{{ $log->user->name ?? '-' }}</td>
                            <td class="p-4 text-sm text-gray-700">{{ $log->aksi }}</td>
                            <td class="p-4">
                                @if ($log->tipe)
                                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                                        @if($log->tipe === 'pengaduan') bg-blue-100 text-blue-700
                                        @elseif($log->tipe === 'user') bg-purple-100 text-purple-700
                                        @elseif($log->tipe === 'kategori') bg-orange-100 text-orange-700
                                        @elseif($log->tipe === 'kritik_saran') bg-green-100 text-green-700
                                        @elseif($log->tipe === 'voting') bg-yellow-100 text-yellow-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ ucfirst($log->tipe) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-gray-600 max-w-xs truncate">{{ $log->deskripsi ?? '-' }}</td>
                            <td class="p-4 text-xs text-gray-400 font-mono">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-10 text-center text-gray-400">Belum ada aktivitas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $logs->links() }}</div>
</x-app-layout>
