<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tipe === 'baru' ? 'Kritik & Saran Baru' : 'Tanggapan Kritik & Saran' }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2563eb, #10b981); color: white; padding: 30px; text-align: center; border-radius: 16px 16px 0 0; }
        .header h1 { margin: 0; font-size: 20px; }
        .body { background: white; padding: 30px; border-radius: 0 0 16px 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .detail { background: #f9fafb; border-radius: 12px; padding: 16px; margin: 16px 0; }
        .detail p { margin: 6px 0; color: #374151; }
        .detail strong { color: #111827; }
        .kategori-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 12px; background: #dbeafe; color: #1e40af; }
        .content-box { background: #f0fdf4; border-radius: 12px; padding: 16px; margin: 16px 0; border-left: 4px solid #10b981; }
        .footer { text-align: center; padding: 20px; color: #9ca3af; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $tipe === 'baru' ? 'Kritik & Saran Baru' : 'Tanggapan Kritik & Saran' }}</h1>
        </div>
        <div class="body">
            @if ($tipe === 'baru')
                <p>Halo, <strong>{{ $kritikSaran->user->name ?? 'Petugas' }}</strong></p>
                <p>{{ $kritikSaran->user->name }} mengirimkan {{ $kritikSaran->kategori }} baru:</p>
            @else
                <p>Halo, <strong>{{ $kritikSaran->user->name }}</strong></p>
                <p>Petugas telah memberikan tanggapan untuk {{ $kritikSaran->kategori }} Anda:</p>
            @endif

            <div class="detail">
                <p><strong>Judul:</strong> {{ $kritikSaran->judul }}</p>
                <p><strong>Kategori:</strong> <span class="kategori-badge">{{ ucfirst($kritikSaran->kategori) }}</span></p>
            </div>

            <div class="content-box">
                <p style="margin: 0; color: #374151;">{!! nl2br(e($kritikSaran->isi_kritik)) !!}</p>
            </div>

            @if ($tipe === 'tanggapan' && $kritikSaran->tanggapan)
                <h3 style="color: #2563eb; margin-top: 20px;">Tanggapan:</h3>
                <div style="background: #dbeafe; border-radius: 12px; padding: 16px; margin: 8px 0; border-left: 4px solid #2563eb;">
                    <p style="margin: 0; color: #374151;">{!! nl2br(e($kritikSaran->tanggapan)) !!}</p>
                </div>
            @endif

            <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">Terima kasih telah menggunakan SIPEKA.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SIPEKA - Sistem Pengaduan Aspirasi. All rights reserved.
        </div>
    </div>
</body>
</html>
