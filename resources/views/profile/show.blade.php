<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">👤 Profil Saya</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola data diri dan keamanan akun</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 mb-6">
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-2xl font-extrabold shadow-md">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-extrabold text-xl text-gray-900">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Nama</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Username</label>
                        <input type="text" name="username" value="{{ auth()->user()->username }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Telepon</label>
                        <input type="text" name="telepon" value="{{ auth()->user()->telepon }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">{{ auth()->user()->alamat }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Foto Profil</label>
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-primary-300 transition cursor-pointer">
                            <input type="file" name="foto_profil" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                    <svg class="w-5 h-5 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            <h2 class="font-extrabold text-lg text-gray-900 mb-5">🔒 Ubah Password</h2>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Password Lama</label>
                        <input type="password" name="password_lama" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Password Baru</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">
                    <svg class="w-5 h-5 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Ubah Password
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
