<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <a href="{{ route('voting.index') }}" class="inline-flex items-center gap-1 text-primary-600 font-bold hover:text-primary-700 mb-4 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>Kembali</a>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <h1 class="text-xl font-bold mb-2">{{ $voting->pertanyaan }}</h1>
            @if($voting->deskripsi)<p class="text-gray-600 mb-4">{{ $voting->deskripsi }}</p>@endif
            <p class="text-xs text-gray-400 mb-4">{{ $voting->tanggal_mulai->format('d/m/Y H:i') }} - {{ $voting->tanggal_selesai->format('d/m/Y H:i') }}</p>

            @if ($voting->status == 'aktif')
                <form method="POST" action="{{ route('voting.vote', $voting) }}" class="mb-6 p-4 bg-gray-50 rounded-xl border">
                    @csrf
                    <h2 class="font-bold text-sm mb-3">Pilih salah satu:</h2>
                    <div class="space-y-2 mb-4">
                        @foreach ($voting->pilihans as $p)
                            <label class="flex items-center gap-2 cursor-pointer p-2 border rounded-lg hover:bg-gray-100 bg-white">
                                <input type="radio" name="id_pilihan" value="{{ $p->id }}" class="accent-primary-600">
                                <span>{{ $p->pilihan }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Kirim Pilihan</button>
                </form>
            @else
                <div class="mb-6 p-3 bg-red-50 text-red-600 rounded-xl text-sm font-semibold text-center">Voting telah ditutup</div>
            @endif

            <h2 class="font-bold text-sm mb-3">Hasil Voting:</h2>
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
