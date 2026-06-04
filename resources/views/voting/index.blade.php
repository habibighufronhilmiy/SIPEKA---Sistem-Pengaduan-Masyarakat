<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Voting & Aspirasi</h1>
        @if (in_array(auth()->user()->role, ['admin', 'petugas']))
            <a href="{{ route('voting.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">+ Buat Voting</a>
        @endif
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        @forelse ($votings as $v)
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-lg">{{ $v->pertanyaan }}</h3>
                        @if ($v->deskripsi)<p class="text-sm text-gray-600 mt-1">{{ $v->deskripsi }}</p>@endif
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $v->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($v->status) }}
                    </span>
                </div>

                <p class="text-xs text-gray-400 mb-4">{{ $v->tanggal_mulai->format('d/m/Y') }} - {{ $v->tanggal_selesai->format('d/m/Y') }} | {{ $v->pilihans_count }} pilihan</p>

                <div class="space-y-3 mb-4">
                    @foreach ($v->pilihans as $p)
                        @php $suara = $p->users->count(); $totalSuara = $v->pilihans->sum(fn($x) => $x->users->count()); @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>{{ $p->pilihan }}</span>
                                <span class="font-semibold">{{ $suara }} suara ({{ $totalSuara > 0 ? round($suara / $totalSuara * 100) : 0 }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-primary-500 to-accent-500 h-2 rounded-full" style="width: {{ $totalSuara > 0 ? ($suara / $totalSuara * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($v->status == 'aktif')
                    <form method="POST" action="{{ route('voting.vote', $v) }}">
                        @csrf
                        <div class="space-y-2 mb-4">
                            @foreach ($v->pilihans as $p)
                                <label class="flex items-center gap-2 cursor-pointer p-2 border rounded-lg hover:bg-gray-50">
                                    <input type="radio" name="id_pilihan" value="{{ $p->id }}" class="accent-primary-600">
                                    <span>{{ $p->pilihan }}</span>
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Pilih</button>
                    </form>
                @else
                    <p class="text-center text-gray-500 text-sm">Voting telah ditutup</p>
                @endif

                @if (in_array(auth()->user()->role, ['admin', 'petugas']))
                    <div class="mt-4 flex gap-2 border-t pt-4">
                        <a href="{{ route('voting.edit', $v) }}" class="text-sm text-blue-600 font-semibold hover:underline">Edit</a>
                        <form method="POST" action="{{ route('voting.destroy', $v) }}" onsubmit="return confirm('Hapus voting?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">Hapus</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="md:col-span-2 text-center py-12 text-gray-500">Belum ada voting</div>
        @endforelse
    </div>
    <div class="mt-6">{{ $votings->links() }}</div>
</x-app-layout>
