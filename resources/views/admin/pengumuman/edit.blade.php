<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">✏️ Edit Pengumuman</h1>
            <p class="text-gray-500 text-sm mt-1">Ubah informasi pengumuman</p>
        </div>
        <form method="POST" action="{{ route('pengumuman.update', $pengumuman) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            @csrf @method('PUT')
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Judul</label>
                <input type="text" name="judul" value="{{ $pengumuman->judul }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
            </div>
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Tipe</label>
                <select name="tipe" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    <option value="pengumuman" {{ $pengumuman->tipe == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                    <option value="jadwal" {{ $pengumuman->tipe == 'jadwal' ? 'selected' : '' }}>Jadwal</option>
                    <option value="kegiatan" {{ $pengumuman->tipe == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                    <option value="pembangunan" {{ $pengumuman->tipe == 'pembangunan' ? 'selected' : '' }}>Pembangunan</option>
                </select>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Isi</label>
                <textarea name="isi" rows="6" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">{{ $pengumuman->isi }}</textarea>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Lokasi</label>
                <input type="text" name="lokasi" value="{{ $pengumuman->lokasi }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-5">
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Tanggal Mulai</label><input type="datetime-local" name="tanggal_mulai" value="{{ $pengumuman->tanggal_mulai?->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Tanggal Selesai</label><input type="datetime-local" name="tanggal_selesai" value="{{ $pengumuman->tanggal_selesai?->format('Y-m-d\TH:i') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
            </div>
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    <option value="draft" {{ $pengumuman->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="publish" {{ $pengumuman->status == 'publish' ? 'selected' : '' }}>Publish</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Simpan</button>
        </form>
    </div>
</x-app-layout>
