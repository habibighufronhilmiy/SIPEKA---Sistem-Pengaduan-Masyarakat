<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('kritik-saran.index') }}" class="inline-flex items-center gap-1 text-primary-600 font-bold hover:text-primary-700 mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">💬 Tulis Kritik & Saran</h1>
            <p class="text-gray-500 text-sm mt-1">Sampaikan pendapat Anda untuk kemajuan bersama</p>
        </div>

        <form method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Kategori</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="group flex items-center justify-center gap-2 p-4 border-2 rounded-xl cursor-pointer transition-all border-gray-200 hover:border-red-300 has-[:checked]:border-red-500 has-[:checked]:bg-red-50 has-[:checked]:shadow-md">
                        <input type="radio" name="kategori" value="kritik" class="accent-red-600 peer" checked>
                        <span class="font-extrabold text-sm text-gray-800 peer-checked:text-red-700">✊ Kritik</span>
                    </label>
                    <label class="group flex items-center justify-center gap-2 p-4 border-2 rounded-xl cursor-pointer transition-all border-gray-200 hover:border-blue-300 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:shadow-md">
                        <input type="radio" name="kategori" value="saran" class="accent-blue-600 peer">
                        <span class="font-extrabold text-sm text-gray-800 peer-checked:text-blue-700">💡 Saran</span>
                    </label>
                    <label class="group flex items-center justify-center gap-2 p-4 border-2 rounded-xl cursor-pointer transition-all border-gray-200 hover:border-purple-300 has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50 has-[:checked]:shadow-md">
                        <input type="radio" name="kategori" value="aspirasi" class="accent-purple-600 peer">
                        <span class="font-extrabold text-sm text-gray-800 peer-checked:text-purple-700">🌟 Aspirasi</span>
                    </label>
                </div>
                @error('kategori')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition" placeholder="Ringkasan pendapat Anda">
                @error('judul')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">Isi</label>
                <textarea name="isi_kritik" rows="6" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition" placeholder="Tuliskan kritik, saran, atau aspirasi Anda secara detail...">{{ old('isi_kritik') }}</textarea>
                @error('isi_kritik')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                Kirim
            </button>
        </form>
    </div>
</x-app-layout>
