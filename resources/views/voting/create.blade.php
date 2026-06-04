<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Buat Voting Baru</h1>
        <form method="POST" class="bg-white rounded-xl shadow-sm border p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Pertanyaan</label>
                <input type="text" name="pertanyaan" required class="w-full px-4 py-2.5 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 border rounded-lg"></textarea>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                <div><label class="block text-sm font-medium mb-1">Tanggal Mulai</label><input type="datetime-local" name="tanggal_mulai" required class="w-full px-4 py-2.5 border rounded-lg"></div>
                <div><label class="block text-sm font-medium mb-1">Tanggal Selesai</label><input type="datetime-local" name="tanggal_selesai" required class="w-full px-4 py-2.5 border rounded-lg"></div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Pilihan (min 2)</label>
                <div id="pilihan-container">
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="pilihans[]" required class="flex-1 px-4 py-2.5 border rounded-lg" placeholder="Pilihan 1">
                    </div>
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="pilihans[]" required class="flex-1 px-4 py-2.5 border rounded-lg" placeholder="Pilihan 2">
                    </div>
                </div>
                <button type="button" onclick="addPilihan()" class="text-sm text-primary-600 font-bold hover:text-primary-700">+ Tambah Pilihan</button>
            </div>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Buat Voting</button>
        </form>
    </div>

    <script>
        let counter = 3;
        function addPilihan() {
            const container = document.getElementById('pilihan-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `<input type="text" name="pilihans[]" required class="flex-1 px-4 py-2.5 border rounded-lg" placeholder="Pilihan ${counter}">`;
            container.appendChild(div);
            counter++;
        }
    </script>
</x-app-layout>
