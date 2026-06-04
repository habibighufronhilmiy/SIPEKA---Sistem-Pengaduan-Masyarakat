<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">📝 Buat Pengaduan</h1>
            <p class="text-gray-500 text-sm mt-1">Sampaikan keluhan atau aspirasi kamu dengan jelas</p>
        </div>

        <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select name="id_kategori" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                    <option value="">Pilih Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('id_kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('id_kategori')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Judul Laporan <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Jalan berlubang di depan kampus" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                @error('judul')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Isi Laporan <span class="text-red-500">*</span></label>
                <textarea name="isi_laporan" rows="5" required placeholder="Jelaskan detail keluhan atau aspirasi kamu..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">{{ old('isi_laporan') }}</textarea>
                @error('isi_laporan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Lokasi Kejadian <span class="text-red-500">*</span></label>
                <div class="flex gap-2 mb-2">
                    <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" required class="flex-1 px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition" placeholder="Klik deteksi otomatis atau ketik manual">
                    <button type="button" id="btnDetectLocation" class="px-5 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-accent-500/25 transition whitespace-nowrap">
                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Deteksi
                    </button>
                </div>
                <p id="locationStatus" class="text-xs text-gray-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Klik "Deteksi" untuk isi lokasi & koordinat otomatis
                </p>
                @error('lokasi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Latitude</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" readonly class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Longitude</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" readonly class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-100 text-gray-500 text-sm">
                </div>
            </div>

            <div id="mapPreview" class="mb-5 hidden">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Pratinjau Peta</label>
                <div id="miniMap" class="w-full h-48 rounded-xl border border-gray-200"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Foto/Video Bukti</label>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary-300 transition cursor-pointer">
                    <input type="file" name="media[]" multiple accept="image/*,video/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                </div>
                <p class="text-xs text-gray-400 mt-1.5">Format: JPG, PNG, MP4. Maks 20MB per file.</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" name="draft" value="0" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                    <svg class="w-5 h-5 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Laporan
                </button>
                <button type="submit" name="draft" value="1" class="px-6 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-600 hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Simpan Draft
                </button>
                <a href="{{ route('pengaduan.index') }}" class="px-6 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-600 hover:bg-gray-50 transition">Batal</a>
            </div>
        </form>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let mapInstance = null;
        let marker = null;

        document.getElementById('btnDetectLocation').addEventListener('click', function() {
            const status = document.getElementById('locationStatus');

            if (!navigator.geolocation) {
                status.textContent = 'Browser tidak mendukung deteksi lokasi. Silakan ketik manual.';
                status.classList.add('text-red-500');
                return;
            }

            status.textContent = 'Mendeteksi lokasi...';
            status.classList.remove('text-red-500');
            status.classList.add('text-blue-500');

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;

                    status.textContent = 'Mengambil alamat...';
                    status.classList.remove('text-blue-500');

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=id`)
                        .then(res => res.json())
                        .then(data => {
                            const alamat = data.display_name || `${lat}, ${lng}`;
                            document.getElementById('lokasi').value = alamat;
                            status.textContent = 'Lokasi berhasil terdeteksi.';
                            status.classList.remove('text-blue-500', 'text-red-500');
                            status.classList.add('text-green-500');
                            showMap(lat, lng, alamat);
                        })
                        .catch(() => {
                            document.getElementById('lokasi').value = `${lat}, ${lng}`;
                            status.textContent = 'Lokasi terdeteksi (alamat tidak ditemukan).';
                            status.classList.remove('text-blue-500');
                            status.classList.add('text-green-500');
                            showMap(lat, lng, `${lat}, ${lng}`);
                        });
                },
                function(error) {
                    let msg = 'Gagal mendeteksi lokasi.';
                    if (error.code === 1) msg = 'Izin lokasi ditolak. Silakan ketik manual.';
                    else if (error.code === 2) msg = 'Lokasi tidak tersedia. Silakan ketik manual.';
                    else if (error.code === 3) msg = 'Waktu habis. Silakan coba lagi.';
                    status.textContent = msg;
                    status.classList.add('text-red-500');
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        });

        function showMap(lat, lng, alamat) {
            const container = document.getElementById('mapPreview');
            container.classList.remove('hidden');

            if (!mapInstance) {
                mapInstance = L.map('miniMap').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(mapInstance);
            } else {
                mapInstance.setView([lat, lng], 15);
            }

            if (marker) mapInstance.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(mapInstance)
                .bindPopup(alamat)
                .openPopup();
        }
    </script>
</x-app-layout>
