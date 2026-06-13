<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di SEKECAM</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2563eb, #10b981); color: white; padding: 30px; text-align: center; border-radius: 16px 16px 0 0; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { background: white; padding: 30px; border-radius: 0 0 16px 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .detail { background: #f9fafb; border-radius: 12px; padding: 16px; margin: 16px 0; }
        .detail p { margin: 6px 0; color: #374151; }
        .detail strong { color: #111827; }
        .btn { display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #2563eb, #10b981); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; margin-top: 16px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang di SEKECAM!</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $user->name }}</strong></p>
            <p>Terima kasih telah mendaftar di <strong>SEKECAM</strong> (Sistem Elektronik Keluhan dan Aspirasi Kecamatan).</p>
            <p>Berikut data akun Anda:</p>
            <div class="detail">
                <p><strong>Nama:</strong> {{ $user->name }}</p>
                <p><strong>Username:</strong> {{ $user->username }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> {{ $passwordPlain }}</p>
            </div>
            <p style="color: #ef4444; font-size: 14px;">* Simpan password Anda dengan aman. Jangan bagikan kepada siapapun.</p>
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Masuk ke SEKECAM</a>
            </div>
            <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">Dengan SEKECAM, Anda dapat menyampaikan keluhan, aspirasi, dan kritik saran kepada Kecamatan dengan mudah.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SEKECAM - Sistem Elektronik Keluhan dan Aspirasi Kecamatan. All rights reserved.
        </div>
    </div>
</body>
</html>
