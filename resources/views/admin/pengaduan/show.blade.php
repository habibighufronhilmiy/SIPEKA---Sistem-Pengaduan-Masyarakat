<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <a href="{{ route('admin.pengaduan') }}" class="inline-flex items-center gap-1 text-primary-600 font-bold hover:text-primary-700 mb-4 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                @if($pengaduan->status == 'menunggu') bg-yellow-100 text-yellow-700
                                @elseif($pengaduan->status == 'diverifikasi') bg-blue-100 text-blue-700
                                @elseif($pengaduan->status == 'diproses') bg-indigo-100 text-indigo-700
                                @elseif($pengaduan->status == 'selesai') bg-accent-100 text-accent-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ ucfirst($pengaduan->status) }}
                            </span>
                            @if ($pengaduan->draft)<span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-gray-100 text-gray-600">Draft</span>@endif
                        </div>
                    </div>

                    <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-3">{{ $pengaduan->judul }}</h1>
                    <div class="flex items-center gap-2 text-sm text-gray-400 mb-4">
                        <span>{{ $pengaduan->user?->name ?? '-' }}</span>
                        <span>&middot;</span>
                        <span>{{ $pengaduan->kategori?->nama_kategori ?? '-' }}</span>
                        <span>&middot;</span>
                        <span>{{ $pengaduan->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    @if ($pengaduan->kode_tracking)
                        <div class="mb-4 p-3 bg-gray-50 rounded-xl inline-block">
                            <span class="text-xs text-gray-400">Kode Tracking:</span>
                            <span class="font-bold text-primary-600 ml-1">{{ $pengaduan->kode_tracking }}</span>
                        </div>
                    @endif

                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap mb-6">{{ $pengaduan->isi_laporan }}</p>

                    @if ($pengaduan->lokasi)
                        <div class="flex items-center gap-1 text-sm text-gray-500 mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $pengaduan->lokasi }}
                        </div>
                    @endif

                    @if ($pengaduan->media->count() > 0)
                        <div class="flex gap-2 flex-wrap mb-4">
                            @foreach ($pengaduan->media as $m)
                                <a href="{{ Storage::url($m->file_path) }}" target="_blank" class="text-sm text-primary-600 font-bold hover:underline px-3 py-1.5 bg-primary-50 rounded-lg">
                                    {{ $m->file_type === 'foto' ? '📷 Foto' : '🎬 Video' }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if ($pengaduan->latitude && $pengaduan->longitude)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-3">📍 Lokasi</h3>
                        <div id="detailMap" class="h-64 rounded-xl"></div>
                        <a href="https://www.google.com/maps?q={{ $pengaduan->latitude }},{{ $pengaduan->longitude }}" target="_blank" class="mt-3 inline-flex items-center gap-1 text-sm text-primary-600 font-bold hover:text-primary-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Buka di Google Maps
                        </a>
                    </div>
                    @push('scripts')
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <script>
                            const map = L.map('detailMap').setView([{{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
                            L.marker([{{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}]).addTo(map);
                        </script>
                    @endpush
                @endif

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-4">📋 Riwayat Status</h3>
                    <div class="space-y-4">
                        @foreach ($pengaduan->riwayats as $r)
                            <div class="flex gap-3">
                                <div class="w-3 h-3 mt-1.5 rounded-full shrink-0
                                    @if($r->status == 'selesai') bg-accent-500
                                    @elseif($r->status == 'ditolak') bg-red-500
                                    @else bg-primary-500 @endif"></div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ ucfirst($r->status) }}</p>
                                    <p class="text-sm text-gray-500">{{ $r->keterangan }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $r->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if ($pengaduan->tanggapans->count() > 0)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">💬 Tanggapan</h3>
                        @foreach ($pengaduan->tanggapans as $t)
                            <div class="flex gap-3 mb-4 last:mb-0">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-sm font-bold shrink-0">{{ substr($t->petugas?->name ?? 'P', 0, 1) }}</div>
                                <div class="flex-1 bg-gray-50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-sm text-gray-900">{{ $t->petugas?->name ?? 'Petugas' }}</span>
                                        <span class="text-xs text-gray-400">{{ $t->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $t->isi_tanggapan }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($pengaduan->rating)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-2">⭐ Rating</h3>
                        <div class="flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $pengaduan->rating->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        @if ($pengaduan->rating->komentar)
                            <p class="text-sm text-gray-500 mt-2">"{{ $pengaduan->rating->komentar }}"</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-3">📄 Aksi</h3>
                    <div class="space-y-3">
                        <a href="{{ route('pengaduan.pdf', $pengaduan) }}" target="_blank" class="block w-full text-center py-2.5 bg-red-50 text-red-700 rounded-xl font-bold hover:bg-red-100 transition">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download PDF
                        </a>
                        <a href="{{ route('admin.pengaduan') }}" class="block w-full text-center py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Kembali ke Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
