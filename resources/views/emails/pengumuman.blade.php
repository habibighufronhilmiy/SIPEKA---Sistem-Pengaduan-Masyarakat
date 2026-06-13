<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumuman Baru</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2563eb, #10b981); color: white; padding: 30px; text-align: center; border-radius: 16px 16px 0 0; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { background: white; padding: 30px; border-radius: 0 0 16px 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .detail { background: #f9fafb; border-radius: 12px; padding: 16px; margin: 16px 0; }
        .detail p { margin: 6px 0; color: #374151; }
        .detail strong { color: #111827; }
        .content-box { background: #f0fdf4; border-radius: 12px; padding: 16px; margin: 16px 0; border-left: 4px solid #10b981; }
        .content-box p { margin: 0; color: #374151; }
        .btn { display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #2563eb, #10b981); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; margin-top: 16px; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pengumuman Baru</h1>
        </div>
        <div class="body">
            <p>Halo, <strong>Warga Kecamatan</strong></p>
            <p>Berikut pengumuman terbaru dari Kecamatan:</p>
            <div class="detail">
                <p><strong>Judul:</strong> {{ $pengumuman->judul }}</p>
                <p><strong>Tipe:</strong> {{ ucfirst($pengumuman->tipe) }}</p>
                @if($pengumuman->lokasi)<p><strong>Lokasi:</strong> {{ $pengumuman->lokasi }}</p>@endif
                @if($pengumuman->tanggal_mulai)<p><strong>Tanggal Mulai:</strong> {{ \Carbon\Carbon::parse($pengumuman->tanggal_mulai)->format('d/m/Y') }}</p>@endif
                @if($pengumuman->tanggal_selesai)<p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($pengumuman->tanggal_selesai)->format('d/m/Y') }}</p>@endif
            </div>
            <div class="content-box">
                <p>{{ $pengumuman->isi }}</p>
            </div>
            <div style="text-align: center;">
                <a href="{{ url('/pengumuman-umum') }}" class="btn">Lihat Pengumuman</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SEKECAM - Sistem Elektronik Keluhan dan Aspirasi Kecamatan. All rights reserved.
        </div>
    </div>
</body>
</html>
