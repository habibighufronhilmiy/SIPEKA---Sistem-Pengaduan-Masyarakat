<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('kelola-kritik-saran.index') }}" class="inline-flex items-center gap-1 text-primary-600 font-bold hover:text-primary-700 mb-4 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                    @if($kritikSaran->kategori == 'kritik') bg-red-100 text-red-700
                    @elseif($kritikSaran->kategori == 'saran') bg-blue-100 text-blue-700
                    @else bg-purple-100 text-purple-700 @endif">
                    {{ ucfirst($kritikSaran->kategori) }}
                </span>
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                    @if($kritikSaran->status == 'menunggu') bg-yellow-100 text-yellow-700
                    @elseif($kritikSaran->status == 'ditanggapi') bg-blue-100 text-blue-700
                    @else bg-accent-100 text-accent-700 @endif">
                    {{ ucfirst($kritikSaran->status) }}
                </span>
            </div>

            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-200 to-gray-100 flex items-center justify-center text-gray-600 font-bold text-sm">
                    {{ substr($kritikSaran->user?->name ?? '-', 0, 1) }}
                </div>
                <div>
                    <p class="font-bold text-gray-900">{{ $kritikSaran->user?->name ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $kritikSaran->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <h2 class="font-extrabold text-lg text-gray-900 mb-3">{{ $kritikSaran->judul }}</h2>

            <div class="prose prose-gray max-w-none">
                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $kritikSaran->isi_kritik }}</p>
            </div>
        </div>

        @if ($kritikSaran->tanggapan)
            <div class="bg-gradient-to-br from-primary-50 to-accent-50 rounded-2xl border border-primary-100 shadow-sm p-6 md:p-8 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($kritikSaran->petugas?->name ?? 'P', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $kritikSaran->petugas?->name ?? 'Petugas' }}</p>
                        <p class="text-xs text-gray-400">{{ $kritikSaran->tanggapan_at?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                <div class="bg-white/80 rounded-xl p-4 border border-primary-100">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $kritikSaran->tanggapan }}</p>
                </div>
            </div>
        @endif

        @if (!$kritikSaran->tanggapan || $kritikSaran->status !== 'selesai')
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                <h3 class="font-extrabold text-lg text-gray-900 mb-4">✍️ Berikan Tanggapan</h3>
                <form method="POST" action="{{ route('kelola-kritik-saran.tanggapan', $kritikSaran) }}">
                    @csrf
                    <div class="mb-4">
                        <textarea name="tanggapan" rows="5" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition" placeholder="Tulis tanggapan Anda...">{{ old('tanggapan', $kritikSaran->tanggapan) }}</textarea>
                        @error('tanggapan')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                            <option value="ditanggapi" {{ $kritikSaran->status == 'ditanggapi' ? 'selected' : '' }}>Ditanggapi (butuh tindak lanjut)</option>
                            <option value="selesai" {{ $kritikSaran->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Simpan Tanggapan</button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
