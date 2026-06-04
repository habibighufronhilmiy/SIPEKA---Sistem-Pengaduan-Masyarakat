<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('voting.index') }}" class="inline-flex items-center gap-1 text-primary-600 font-bold hover:text-primary-700 mb-4 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>Kembali</a>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h1 class="text-xl font-bold mb-2">{{ $voting->pertanyaan }}</h1>
            @if($voting->deskripsi)<p class="text-gray-600 mb-4">{{ $voting->deskripsi }}</p>@endif
            <p class="text-xs text-gray-400 mb-4">{{ $voting->tanggal_mulai->format('d/m/Y H:i') }} - {{ $voting->tanggal_selesai->format('d/m/Y H:i') }}</p>

            <div class="space-y-4 mb-6">
                @foreach ($voting->pilihans as $p)
                    @php $suara = $p->users->count(); $totalSuara = $voting->pilihans->sum(fn($x) => $x->users->count()); @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold">{{ $p->pilihan }}</span>
                            <span>{{ $suara }} suara ({{ $totalSuara > 0 ? round($suara / $totalSuara * 100) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-full rounded-full" style="width: {{ $totalSuara > 0 ? ($suara / $totalSuara * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p class="text-sm text-gray-500">Total suara: {{ $voting->pilihans->sum(fn($x) => $x->users->count()) }}</p>
        </div>
    </div>
</x-app-layout>
