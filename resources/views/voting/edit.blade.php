<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Edit Voting</h1>
        <form method="POST" class="bg-white rounded-xl shadow-sm border p-6">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Pertanyaan</label>
                <input type="text" name="pertanyaan" value="{{ $voting->pertanyaan }}" required class="w-full px-4 py-2.5 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 border rounded-lg">{{ $voting->deskripsi }}</textarea>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div><label class="block text-sm font-medium mb-1">Tanggal Mulai</label><input type="datetime-local" name="tanggal_mulai" value="{{ $voting->tanggal_mulai->format('Y-m-d\TH:i') }}" required class="w-full px-4 py-2.5 border rounded-lg"></div>
                <div><label class="block text-sm font-medium mb-1">Tanggal Selesai</label><input type="datetime-local" name="tanggal_selesai" value="{{ $voting->tanggal_selesai->format('Y-m-d\TH:i') }}" required class="w-full px-4 py-2.5 border rounded-lg"></div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border rounded-lg">
                    <option value="aktif" {{ $voting->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="ditutup" {{ $voting->status == 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Simpan</button>
        </form>
    </div>
</x-app-layout>
