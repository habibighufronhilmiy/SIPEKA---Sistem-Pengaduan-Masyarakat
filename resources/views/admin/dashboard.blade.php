<x-app-layout>
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Dashboard Admin ⚡</h1>
        <p class="text-gray-500 mt-1">Overview sistem SIPEKA</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-8">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-gray-400 text-xs font-medium mb-1">Total Pengaduan</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $totalPengaduan }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm">
            <p class="text-blue-600 text-xs font-medium mb-1">Hari Ini</p>
            <p class="text-2xl font-extrabold text-blue-600">{{ $pengaduanHariIni }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-yellow-100 shadow-sm">
            <p class="text-yellow-600 text-xs font-medium mb-1">Diproses</p>
            <p class="text-2xl font-extrabold text-yellow-600">{{ $pengaduanDiproses }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-accent-100 shadow-sm">
            <p class="text-accent-600 text-xs font-medium mb-1">Selesai</p>
            <p class="text-2xl font-extrabold text-accent-600">{{ $pengaduanSelesai }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-red-100 shadow-sm">
            <p class="text-red-600 text-xs font-medium mb-1">Ditolak</p>
            <p class="text-2xl font-extrabold text-red-600">{{ $pengaduanDitolak }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-gray-400 text-xs font-medium mb-1">Total User</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $totalUser }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-gray-400 text-xs font-medium mb-1">Petugas</p>
            <p class="text-2xl font-extrabold text-gray-900">{{ $totalPetugas }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-lg text-gray-900 mb-5">📈 Grafik Bulanan {{ date('Y') }}</h2>
            <canvas id="chartBulanan" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-lg text-gray-900 mb-5">📊 Statistik per Kategori</h2>
            <canvas id="chartKategori" height="200"></canvas>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-lg text-gray-900 mb-5">📋 Pengaduan Terbaru</h2>
            <div class="space-y-3">
                @forelse ($pengaduans as $p)
                    <div class="p-4 border border-gray-100 rounded-xl">
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
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-lg text-gray-900 mb-5">🗺️ Peta Persebaran Pengaduan</h2>
            @if ($pengaduanMap->count() > 0)
                <div id="mapPersebaran" class="w-full h-64 rounded-xl border border-gray-200"></div>
                <p class="text-xs text-gray-400 mt-2">{{ $pengaduanMap->count() }} pengaduan dengan lokasi terdeteksi</p>
            @else
                <div class="flex flex-col items-center justify-center h-48 text-gray-400">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <p class="text-sm">Belum ada data lokasi pengaduan</p>
                </div>
            @endif
        </div>
    </div>

    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
            Kelola User
        </a>
        <a href="{{ route('admin.kategoris') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Kelola Kategori
        </a>
        <a href="{{ route('admin.pengaduan') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Semua Pengaduan
        </a>
        <a href="{{ route('admin.laporan') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Laporan & Export
        </a>
        <a href="{{ route('kelola-kritik-saran.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
            Kritik & Saran
        </a>
        <a href="{{ route('voting.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Kelola Voting
        </a>
        <a href="{{ route('pengumuman.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Pengumuman
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        @php $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']; @endphp

        const bulanData = @json($bulan);
        const bulanCounts = bulanData.map((_, i) => {
            const item = @json($statistikBulanan).find(b => b.bulan === i + 1);
            return item ? item.total : 0;
        });

        new Chart(document.getElementById('chartBulanan'), {
            type: 'line',
            data: {
                labels: bulanData,
                datasets: [{
                    label: 'Pengaduan',
                    data: bulanCounts,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });

        const kategoriLabels = @json($statistikKategori->pluck('nama_kategori'));
        const kategoriCounts = @json($statistikKategori->pluck('pengaduans_count'));

        new Chart(document.getElementById('chartKategori'), {
            type: 'doughnut',
            data: {
                labels: kategoriLabels,
                datasets: [{
                    data: kategoriCounts,
                    backgroundColor: ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316'],
                    borderWidth: 3,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 12 }
                    }
                }
            }
        });

        @if ($pengaduanMap->count() > 0)
            const mapData = @json($pengaduanMap);

            const map = L.map('mapPersebaran').setView([-2.5, 118], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const bounds = [];
            mapData.forEach(function(d) {
                const marker = L.marker([d.lat, d.lng]).addTo(map);
                marker.bindPopup(`<b>${d.judul}</b><br>${d.kategori} &middot; ${d.status}`);
                bounds.push([d.lat, d.lng]);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        @endif
    </script>
</x-app-layout>
