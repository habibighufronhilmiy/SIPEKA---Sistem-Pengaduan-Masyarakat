<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">👥 Kelola User</h1>
            <p class="text-gray-500 text-sm mt-1">Manajemen pengguna sistem SEKECAM</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl text-sm font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Nama</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Username</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Email</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Role</th>
                        <th class="text-left p-4 text-sm font-bold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                            <td class="p-4 font-semibold text-gray-900">{{ $u->name }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $u->username }}</td>
                            <td class="p-4 text-sm text-gray-600">{{ $u->email }}</td>
                            <td class="p-4">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                    @if($u->role == 'admin') bg-purple-100 text-purple-700
                                    @elseif($u->role == 'petugas') bg-blue-100 text-blue-700
                                    @else bg-accent-100 text-accent-700 @endif">
                                    {{ ucfirst($u->role) }}
                                </span>
                            </td>
                            <td class="p-4 flex gap-3">
                                <a href="{{ route('admin.users.edit', $u) }}" class="inline-flex items-center gap-1 text-primary-600 text-sm font-bold hover:text-primary-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                @if ($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 text-red-600 text-sm font-bold hover:text-red-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-6">{{ $users->links() }}</div>
</x-app-layout>
