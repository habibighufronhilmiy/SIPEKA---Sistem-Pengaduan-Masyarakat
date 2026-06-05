<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">👤 Tambah User</h1>
            <p class="text-gray-500 text-sm mt-1">Buat akun baru untuk petugas atau admin</p>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            @csrf
            <div class="grid md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Nama</label><input type="text" name="name" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Username</label><input type="text" name="username" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Email</label><input type="email" name="email" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Role</label>
                    <select name="role" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                        <option value="masyarakat">Masyarakat</option>
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Password</label><input type="password" name="password" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Konfirmasi Password</label><input type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
                <div><label class="block text-sm font-bold text-gray-700 mb-1.5">Telepon</label><input type="text" name="telepon" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></div>
            </div>
            <div class="mt-5"><label class="block text-sm font-bold text-gray-700 mb-1.5">Alamat</label><textarea name="alamat" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition"></textarea></div>
            <button type="submit" class="mt-6 px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Simpan</button>
        </form>
    </div>
</x-app-layout>
