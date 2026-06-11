<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SEKECAM - Lupa Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .auth-grad { background: linear-gradient(135deg, #0f5fea 0%, #10b981 100%); }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <a href="/" class="flex items-center justify-center gap-2 mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="SEKECAM" class="h-10 w-10 rounded-full">
                <span class="text-2xl font-extrabold bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">SEKECAM</span>
            </a>
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                <h1 class="text-2xl font-extrabold text-gray-900 mb-2">🔑 Lupa Password</h1>
                <p class="text-gray-500 text-sm mb-6">Masukkan email Anda, kami akan kirim link reset password.</p>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-accent-50 border border-accent-200 text-accent-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
                @endif

                <form method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 bg-gray-50/50 transition">
                        @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-primary-600 to-accent-500 text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary-500/20 transition">Kirim Link Reset</button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-primary-600 font-bold hover:text-primary-700">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
