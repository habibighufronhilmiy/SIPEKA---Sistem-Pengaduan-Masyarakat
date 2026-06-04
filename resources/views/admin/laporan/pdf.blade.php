<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Laporan Pengaduan</title>
<style>
    body { font-family: sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background: #003366; color: white; }
    h1 { text-align: center; margin-bottom: 20px; }
</style>
</head>
<body>
    <h1>Laporan Pengaduan SIPEKA</h1>
    <p>Tanggal: {{ date('d/m/Y') }}</p>
    <table>
        <thead>
            <tr><th>No</th><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Status</th><th>Tanggal</th></tr>
        </thead>
        <tbody>
            @foreach ($pengaduans as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->judul }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->kategori->nama_kategori }}</td>
                    <td>{{ ucfirst($p->status) }}</td>
                    <td>{{ $p->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
