<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Diterima</title>
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
            <h1>Pengaduan Diterima</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>{{ $pengaduan->user->name }}</strong></p>
            <p>Pengaduan Anda telah berhasil dikirim dan sedang diproses oleh sistem.</p>
            <div class="detail">
                <p><strong>Judul:</strong> {{ $pengaduan->judul }}</p>
                <p><strong>Kategori:</strong> {{ $pengaduan->kategori->nama_kategori ?? '-' }}</p>
                <p><strong>Kode Tracking:</strong> {{ $pengaduan->kode_tracking }}</p>
                <p><strong>Status:</strong> {{ strtoupper($pengaduan->status) }}</p>
                <p><strong>Tanggal:</strong> {{ $pengaduan->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <p>Gunakan kode tracking di atas untuk memantau status pengaduan Anda.</p>
            <div style="text-align: center;">
                <a href="{{ url('/tracking?kode=' . $pengaduan->kode_tracking) }}" class="btn">Lacak Pengaduan</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SEKECAM - Sistem Elektronik Keluhan dan Aspirasi Kecamatan. All rights reserved.
        </div>
    </div>
</body>
</html>
