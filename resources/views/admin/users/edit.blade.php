<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">✏️ Edit User</h1>
            <p class="text-gray-500 text-sm mt-1">Ubah data pengguna</p>
        </div>
        <form method="POST" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            @csrf @method('PUT')
            <div class="grid md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Nama</label><input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Username</label><input type="text" name="username" value="{{ $user->username }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Email</label><input type="email" name="email" value="{{ $user->email }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Role</label>
                    <select name="role" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                        <option value="masyarakat" {{ $user->role == 'masyarakat' ? 'selected' : '' }}>Masyarakat</option>
                        <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Password Baru</label><input type="password" name="password" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition" placeholder="Kosongkan jika tidak diubah"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Konfirmasi Password</label><input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Telepon</label><input type="text" name="telepon" value="{{ $user->telepon }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
            </div>
            <div class="mt-5"><label class="block text-sm font-bold text-gray-700 mb-1.5">Alamat</label><textarea name="alamat" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">{{ $user->alamat }}</textarea></div>
            <button type="submit" class="mt-6 px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Simpan</button>
        </form>
    </div>
</x-app-layout>
