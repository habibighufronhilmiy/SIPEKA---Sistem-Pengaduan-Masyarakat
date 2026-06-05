<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">✏️ Edit Pengaduan</h1>
            <p class="text-gray-500 text-sm mt-1">Ubah laporan kamu sebelum diproses</p>
        </div>

        <form method="POST" action="{{ route('pengaduan.update', $pengaduan) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            @csrf @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select name="id_kategori" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                    <option value="">Pilih Kategori</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('id_kategori', $pengaduan->id_kategori) == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('id_kategori')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Judul Laporan <span class="text-red-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $pengaduan->judul) }}" required placeholder="Contoh: Jalan berlubang di depan kampus" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                @error('judul')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Isi Laporan <span class="text-red-500">*</span></label>
                <textarea name="isi_laporan" rows="5" required placeholder="Jelaskan detail keluhan atau aspirasi kamu..." class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">{{ old('isi_laporan', $pengaduan->isi_laporan) }}</textarea>
                @error('isi_laporan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Lokasi Kejadian <span class="text-red-500">*</span></label>
                <input type="text" name="lokasi" value="{{ old('lokasi', $pengaduan->lokasi) }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition" placeholder="Masukkan lokasi kejadian">
                @error('lokasi')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude', $pengaduan->latitude) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude', $pengaduan->longitude) }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 transition text-sm">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                    <svg class="w-5 h-5 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('pengaduan.index') }}" class="px-6 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-600 hover:bg-gray-50 transition">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
