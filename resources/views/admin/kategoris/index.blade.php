<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">🏷️ Kelola Kategori</h1>
        <p class="text-gray-500 text-sm mt-1">Atur kategori pengaduan</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-extrabold text-lg text-gray-900 mb-5">Tambah Kategori</h2>
            <form method="POST" action="{{ route('admin.kategoris.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama Kategori</label>
                    <input type="text" name="nama_kategori" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Slug</label>
                    <input type="text" name="slug" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition" placeholder="contoh: jalan-rusak">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Icon (emoji)</label>
                    <input type="text" name="icon" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></textarea>
                </div>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Simpan</button>
            </form>
        </div>

        <div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left p-4 text-sm font-bold text-gray-600">Kategori</th>
                                <th class="text-left p-4 text-sm font-bold text-gray-600">Slug</th>
                                <th class="text-left p-4 text-sm font-bold text-gray-600">Laporan</th>
                                <th class="text-left p-4 text-sm font-bold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategoris as $k)
                                <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                                    <td class="p-4 font-semibold text-gray-900">{{ $k->nama_kategori }}</td>
                                    <td class="p-4 text-sm text-gray-500">{{ $k->slug }}</td>
                                    <td class="p-4 text-sm text-gray-600">{{ $k->pengaduans_count }}</td>
                                    <td class="p-4 flex gap-2">
                                        <form method="POST" action="{{ route('admin.kategoris.update', $k) }}" class="inline">
                                            @csrf
                                            <input type="text" name="nama_kategori" value="{{ $k->nama_kategori }}" class="w-28 px-2 py-1.5 border border-gray-200 rounded-lg text-sm" placeholder="Nama">
                                            <input type="text" name="slug" value="{{ $k->slug }}" class="w-28 px-2 py-1.5 border border-gray-200 rounded-lg text-sm" placeholder="slug">
                                            <button type="submit" class="text-primary-600 text-sm font-bold hover:text-primary-700">Update</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.kategoris.destroy', $k) }}" class="inline" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 text-sm font-bold hover:text-red-700 ml-1">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $kategoris->links() }}</div>
        </div>
    </div>
</x-app-layout>
