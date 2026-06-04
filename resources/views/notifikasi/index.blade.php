<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">🔔 Notifikasi</h1>
                <p class="text-gray-500 text-sm mt-1">Semua pemberitahuan untuk kamu</p>
            </div>
            <form method="POST" action="{{ route('notifikasi.readAll') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-50 text-primary-600 rounded-xl text-sm font-bold hover:bg-primary-100 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Semua Dibaca
                </button>
            </form>
        </div>

        <div class="space-y-3">
            @forelse ($notifikasis as $notif)
                <div class="bg-white rounded-2xl border p-5 transition {{ !$notif->is_read ? 'border-l-4 border-l-primary-500 border-gray-100 shadow-sm' : 'border-gray-100 shadow-sm opacity-75' }}">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                @if (!$notif->is_read)
                                    <span class="w-2 h-2 rounded-full bg-primary-500 shrink-0"></span>
                                @endif
                                <h3 class="font-bold text-gray-900 {{ !$notif->is_read ? '' : '' }}">{{ $notif->judul }}</h3>
                            </div>
                            <p class="text-gray-500 text-sm">{{ $notif->pesan }}</p>
                            <p class="text-gray-400 text-xs mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @if (!$notif->is_read)
                            <form method="POST" action="{{ route('notifikasi.read', $notif) }}">
                                @csrf
                                <button type="submit" class="text-xs text-primary-600 font-bold hover:text-primary-700 whitespace-nowrap">Tandai</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <p class="font-medium">Belum ada notifikasi</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $notifikasis->links() }}</div>
    </div>
</x-app-layout>
