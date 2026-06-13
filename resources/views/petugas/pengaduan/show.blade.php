<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('petugas.pengaduan.index') }}" class="inline-flex items-center gap-1 text-primary-600 font-bold hover:text-primary-700 mb-4 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        <div class="grid lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex justify-between items-start mb-5">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl md:text-2xl font-extrabold text-gray-900">{{ $pengaduan->judul }}</h1>
                            <p class="text-sm text-gray-400 mt-1">{{ $pengaduan->user->name }} &middot; {{ $pengaduan->kategori->nama_kategori }} &middot; {{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <span class="text-sm px-3 py-1 rounded-full font-bold shrink-0 ml-3 whitespace-nowrap
                            @if($pengaduan->status == 'menunggu') bg-yellow-100 text-yellow-700
                            @elseif($pengaduan->status == 'diverifikasi') bg-blue-100 text-blue-700
                            @elseif($pengaduan->status == 'diproses') bg-indigo-100 text-indigo-700
                            @elseif($pengaduan->status == 'selesai') bg-accent-100 text-accent-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($pengaduan->status) }}
                        </span>
                    </div>

                    <div class="text-gray-700 leading-relaxed mb-5"><p>{{ $pengaduan->isi_laporan }}</p></div>

                    @if ($pengaduan->media->count() > 0)
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($pengaduan->media as $m)
                                <img src="{{ asset('storage/' . $m->file_path) }}" class="rounded-xl w-full h-32 object-cover border border-gray-100">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <h2 class="font-extrabold text-lg text-gray-900 mb-5">📊 Riwayat Status</h2>
                    <div class="space-y-5">
                        @foreach ($pengaduan->riwayats as $r)
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 ring-2 ring-primary-100 shrink-0"></div>
                                    @if (!$loop->last)<div class="w-0.5 flex-1 bg-gray-200 mt-1"></div>@endif
                                </div>
                                <div class="pb-5">
                                    <p class="font-bold text-gray-900">{{ ucfirst($r->status) }}</p>
                                    <p class="text-sm text-gray-500">{{ $r->keterangan }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $r->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <h2 class="font-extrabold text-lg text-gray-900 mb-5">💬 Tanggapan</h2>
                    <form method="POST" action="{{ route('petugas.pengaduan.tanggapan', $pengaduan) }}">
                        @csrf
                        <textarea name="isi_tanggapan" rows="3" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition mb-4" placeholder="Tulis tanggapan..."></textarea>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Tanggapan
                        </button>
                    </form>

                    @if ($pengaduan->tanggapans->count() > 0)
                        <div class="mt-6 space-y-4">
                            @foreach ($pengaduan->tanggapans as $t)
                                @php $penulis = $t->petugas ?? $t->user; @endphp
                                <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr($penulis->name, 0, 1)) }}</div>
                                        <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $penulis->name }} <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">{{ $t->petugas ? '(Petugas)' : '(Masyarakat)' }}</span></p>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $t->isi_tanggapan }}</p>
                                    @if ($t->bukti_foto)
                                        <img src="{{ asset('storage/' . $t->bukti_foto) }}" class="mt-2 rounded-xl max-w-xs border">
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $t->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="font-extrabold text-lg text-gray-900 mb-4">📄 Aksi</h2>
                    <a href="{{ route('pengaduan.pdf', $pengaduan) }}" target="_blank" class="block w-full text-center py-2.5 bg-red-50 text-red-700 rounded-xl font-bold hover:bg-red-100 transition">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download PDF
                    </a>
                </div>

                @if ($pengaduan->latitude && $pengaduan->longitude)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="font-extrabold text-lg text-gray-900 mb-4">📍 Lokasi Kejadian</h2>
                        @if ($pengaduan->lokasi)
                            <p class="text-sm text-gray-500 mb-3">{{ $pengaduan->lokasi }}</p>
                        @endif
                        <div id="detailMap" class="w-full h-48 rounded-xl border border-gray-200 mb-3"></div>
                        <a href="https://www.google.com/maps?q={{ $pengaduan->latitude }},{{ $pengaduan->longitude }}"
                           target="_blank"
                           class="block w-full text-center px-4 py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition shadow-sm">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            Buka di Google Maps
                        </a>
                    </div>
                @endif

                @if ($pengaduan->status === 'menunggu')
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="font-extrabold text-lg text-gray-900 mb-4">🔍 Verifikasi Pengaduan</h2>
                        <p class="mb-4 text-gray-500 text-sm">AI gagal memverifikasi laporan ini. Lakukan verifikasi manual.</p>
                        <div class="space-y-3">
                            <form method="POST" action="{{ route('petugas.pengaduan.verifikasi', $pengaduan) }}">
                                @csrf
                                <input type="hidden" name="status" value="diverifikasi">
                                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Verifikasi & Terima
                                </button>
                            </form>
                            <form method="POST" action="{{ route('petugas.pengaduan.verifikasi', $pengaduan) }}" onsubmit="return confirm('Yakin tolak pengaduan ini?')">
                                @csrf
                                <input type="hidden" name="status" value="ditolak">
                                <div class="flex gap-2">
                                    <input type="text" name="alasan" placeholder="Alasan penolakan..." required class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 bg-gray-50/50 transition text-sm">
                                    <button type="submit" class="px-6 py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition whitespace-nowrap">
                                        Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                @if ($pengaduan->status === 'diverifikasi')
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="font-extrabold text-lg text-gray-900 mb-4">🚀 Proses Pengaduan</h2>
                        <form method="POST" action="{{ route('petugas.pengaduan.proses', $pengaduan) }}">
                            @csrf
                            <p class="mb-4 text-gray-500 text-sm">Pengaduan sudah diverifikasi oleh AI. Ambil alih untuk diproses?</p>
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Proses Sekarang
                            </button>
                        </form>
                    </div>
                @endif

                @if ($pengaduan->status === 'diproses')
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="font-extrabold text-lg text-gray-900 mb-4">✅ Selesaikan Pengaduan</h2>
                        <form method="POST" action="{{ route('petugas.pengaduan.selesai', $pengaduan) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Laporan Penanganan</label>
                                <textarea name="isi_tanggapan" rows="4" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1.5">Bukti Foto</label>
                                <input type="file" name="bukti_foto" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-accent-50 file:text-accent-600 hover:file:bg-accent-100">
                            </div>
                            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-accent-500/25 transition">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Selesaikan
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if ($pengaduan->latitude && $pengaduan->longitude)
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const lat = {{ $pengaduan->latitude }};
        const lng = {{ $pengaduan->longitude }};
        const map = L.map('detailMap').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map)
            .bindPopup(@js($pengaduan->lokasi ?? 'Lokasi Kejadian'))
            .openPopup();
    </script>
    @endif
</x-app-layout>
