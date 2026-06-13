<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('pengaduan.index') }}" class="inline-flex items-center gap-1 text-primary-600 font-bold hover:text-primary-700 mb-4 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 mb-6">
            <div class="flex justify-between items-start mb-5">
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl md:text-2xl font-extrabold text-gray-900">{{ $pengaduan->judul }}</h1>
                    <p class="text-sm text-gray-400 mt-1">{{ $pengaduan->kategori->nama_kategori }} &middot; {{ $pengaduan->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="text-sm px-3 py-1 rounded-full font-bold shrink-0 ml-3
                    @if($pengaduan->status == 'menunggu') bg-yellow-100 text-yellow-700
                    @elseif($pengaduan->status == 'diverifikasi') bg-blue-100 text-blue-700
                    @elseif($pengaduan->status == 'diproses') bg-indigo-100 text-indigo-700
                    @elseif($pengaduan->status == 'selesai') bg-accent-100 text-accent-700
                    @else bg-red-100 text-red-700 @endif">
                    {{ ucfirst($pengaduan->status) }}
                </span>
            </div>

            <div class="text-gray-700 leading-relaxed mb-5">
                <p>{{ $pengaduan->isi_laporan }}</p>
            </div>

            @if ($pengaduan->lokasi)
                <div class="flex items-start gap-2 text-sm text-gray-500 mb-3 bg-gray-50 p-3 rounded-xl">
                    <svg class="w-5 h-5 text-primary-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ $pengaduan->lokasi }}</span>
                </div>
            @endif

            @if ($pengaduan->latitude && $pengaduan->longitude)
                <div id="detailMap" class="w-full h-48 rounded-xl border border-gray-200 mb-3"></div>
                <a href="https://www.google.com/maps?q={{ $pengaduan->latitude }},{{ $pengaduan->longitude }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl text-sm font-bold hover:bg-red-600 transition shadow-sm mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    Buka di Google Maps
                </a>
            @endif

            @if ($pengaduan->media->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4">
                    @foreach ($pengaduan->media as $m)
                        <img src="{{ asset('storage/' . $m->file_path) }}" class="rounded-xl w-full h-32 object-cover border border-gray-100">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 mb-6">
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

        @if ($pengaduan->tanggapans->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 mb-6">
                <h2 class="font-extrabold text-lg text-gray-900 mb-5">💬 Tanggapan</h2>
                <div class="space-y-4">
                    @foreach ($pengaduan->tanggapans as $t)
                        @php $penulis = $t->petugas ?? $t->user; @endphp
                        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr($penulis->name, 0, 1)) }}</div>
                                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $penulis->name }} <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">{{ $t->petugas ? '(Petugas)' : '(Anda)' }}</span></p>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $t->isi_tanggapan }}</p>
                            @if ($t->bukti_foto)
                                <img src="{{ asset('storage/' . $t->bukti_foto) }}" class="mt-2 rounded-xl max-w-xs border">
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ $t->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($pengaduan->status !== 'menunggu')
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 mb-6">
                <h2 class="font-extrabold text-lg text-gray-900 mb-5">✍️ Kirim Tanggapan</h2>
                <form method="POST" action="{{ route('pengaduan.tanggapan.masyarakat', $pengaduan) }}">
                    @csrf
                    <textarea name="isi_tanggapan" rows="3" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition mb-4" placeholder="Tulis tanggapan Anda..."></textarea>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Tanggapan
                    </button>
                </form>
            </div>
        @endif

        @if ($pengaduan->status === 'selesai' && !$pengaduan->rating)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 mb-6">
                <h2 class="font-extrabold text-lg text-gray-900 mb-5">⭐ Beri Rating</h2>
                <form method="POST" action="{{ route('pengaduan.rating', $pengaduan) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rating</label>
                        <div class="flex gap-2" id="starRating">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer star-label" data-value="{{ $i }}">
                                    <input type="radio" name="rating" value="{{ $i }}" class="hidden">
                                    <span class="star text-3xl text-gray-200 inline-block">&#9733;</span>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <script>
                        (function() {
                            const container = document.getElementById('starRating');
                            const stars = container.querySelectorAll('.star');
                            const labels = container.querySelectorAll('.star-label');
                            let selected = 0;

                            function highlight(n) {
                                stars.forEach((s, i) => {
                                    s.style.color = i < n ? '#eab308' : '#d1d5db';
                                });
                            }

                            labels.forEach((label, idx) => {
                                label.addEventListener('mouseenter', () => highlight(idx + 1));
                                label.addEventListener('click', () => {
                                    selected = idx + 1;
                                    highlight(selected);
                                });
                            });

                            container.addEventListener('mouseleave', () => highlight(selected));
                        })();
                    </script>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Komentar</label>
                        <textarea name="komentar" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition" placeholder="Bagaimana pengalaman kamu?"></textarea>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                        Kirim Rating
                    </button>
                </form>
            </div>
        @endif
    </div>

        <div class="text-center mt-6">
            <a href="{{ route('pengaduan.pdf', $pengaduan) }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition shadow-sm">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </a>
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
