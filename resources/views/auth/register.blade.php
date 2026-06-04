<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - SIPEKA</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .auth-grad { background: linear-gradient(135deg, #0f5fea 0%, #10b981 100%); }
    </style>
</head>
<body class="min-h-screen flex items-start justify-center auth-grad relative overflow-y-auto py-6">
    <div class="fixed inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 25% 25%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="fixed top-10 right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed bottom-10 left-10 w-96 h-96 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md px-4">
        <a href="{{ route('landing') }}" class="flex items-center justify-center gap-3 mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="SIPEKA" class="h-12 w-12 rounded-full ring-4 ring-white/30">
            <span class="text-white text-3xl font-extrabold tracking-tight">SIPEKA</span>
        </a>

        <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-8 shadow-2xl">
            <h2 class="text-2xl font-extrabold text-gray-900 text-center mb-1">Gabung Yuk! 🚀</h2>
            <p class="text-gray-500 text-center text-sm mb-6">Isi data diri, dijamin gampang!</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                    @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">{{ old('alamat') }}</textarea>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-gray-50/50 transition">
                </div>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/25 transition text-base">
                    Daftar
                </button>
            </form>

            <div class="relative my-5">
                <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-gray-200"></span></div>
                <div class="relative flex justify-center text-sm"><span class="bg-white px-3 text-gray-400 font-medium">atau</span></div>
            </div>

            <a href="{{ route('auth.google') }}"
                class="w-full flex items-center justify-center gap-3 py-3 border-2 border-gray-200 rounded-xl font-bold hover:bg-gray-50 transition text-gray-700">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Daftar dengan Google
            </a>

            <p class="text-center mt-5 text-sm text-gray-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-primary-600 font-bold hover:text-primary-700">Masuk</a>
            </p>
        </div>
    </div>
</body>
</html>
