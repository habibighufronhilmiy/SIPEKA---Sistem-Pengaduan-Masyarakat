<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"><title>Detail Pengaduan - {{ $pengaduan->kode_tracking }}</title>
<style>
    body { font-family: sans-serif; font-size: 12px; color: #333; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #003366; padding-bottom: 10px; }
    .header h1 { margin: 0; font-size: 18px; color: #003366; }
    .header p { margin: 4px 0 0; color: #666; font-size: 11px; }
    .section { margin-bottom: 16px; }
    .section h2 { font-size: 14px; color: #003366; border-bottom: 1px solid #ddd; padding-bottom: 4px; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 4px 8px; vertical-align: top; }
    td.label { font-weight: bold; width: 120px; color: #555; }
    .status-box { display: inline-block; padding: 3px 10px; border-radius: 3px; font-weight: bold; font-size: 11px; }
    .diverifikasi { background: #d1fae5; color: #065f46; }
    .diproses { background: #dbeafe; color: #1e40af; }
    .selesai { background: #d1fae5; color: #065f46; }
    .ditolak { background: #fce7f3; color: #9d174d; }
    .menunggu { background: #fef3c7; color: #92400e; }
    .footer { text-align: center; margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; color: #999; font-size: 10px; }
    .content-box { background: #f9fafb; padding: 10px; border-radius: 4px; margin: 6px 0; }
</style>
</head>
<body>
    <div class="header">
        <h1>SIPEKA - Sistem Pengaduan Aspirasi</h1>
        <p>Detail Pengaduan</p>
    </div>

    <div class="section">
        <h2>Informasi Pengaduan</h2>
        <table>
            <tr><td class="label">Kode Tracking</td><td><strong>{{ $pengaduan->kode_tracking }}</strong></td></tr>
            <tr><td class="label">Judul</td><td><strong>{{ $pengaduan->judul }}</strong></td></tr>
            <tr><td class="label">Kategori</td><td>{{ $pengaduan->kategori->nama_kategori ?? '-' }}</td></tr>
            <tr><td class="label">Status</td><td><span class="status-box {{ $pengaduan->status }}">{{ ucfirst($pengaduan->status) }}</span></td></tr>
            <tr><td class="label">Tanggal Dibuat</td><td>{{ $pengaduan->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td class="label">Terakhir Update</td><td>{{ $pengaduan->updated_at->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h2>Detail Laporan</h2>
        <div class="content-box">
            <p style="margin:0;">{{ $pengaduan->isi_laporan }}</p>
        </div>
    </div>

    @if ($pengaduan->lokasi)
    <div class="section">
        <h2>Lokasi</h2>
        <table>
            <tr><td class="label">Alamat</td><td>{{ $pengaduan->lokasi }}</td></tr>
            <tr><td class="label">Koordinat</td><td>{{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}</td></tr>
        </table>
    </div>
    @endif

    <div class="section">
        <h2>Pelapor</h2>
        <table>
            <tr><td class="label">Nama</td><td>{{ $pengaduan->user->name }}</td></tr>
            <tr><td class="label">Email</td><td>{{ $pengaduan->user->email ?? '-' }}</td></tr>
            <tr><td class="label">Telepon</td><td>{{ $pengaduan->user->telepon ?? '-' }}</td></tr>
        </table>
    </div>

    @if ($pengaduan->petugas)
    <div class="section">
        <h2>Petugas Penangan</h2>
        <table>
            <tr><td class="label">Nama</td><td>{{ $pengaduan->petugas->name }}</td></tr>
            <tr><td class="label">Email</td><td>{{ $pengaduan->petugas->email ?? '-' }}</td></tr>
        </table>
    </div>
    @endif

    @if ($pengaduan->tanggapans->count() > 0)
    <div class="section">
        <h2>Tanggapan</h2>
        @foreach ($pengaduan->tanggapans as $t)
            <div class="content-box" style="margin-bottom:6px;">
                <p style="margin:0 0 4px;"><strong>{{ $t->petugas?->name ?? 'Petugas' }}</strong> &middot; {{ $t->tgl_tanggapan ? \Carbon\Carbon::parse($t->tgl_tanggapan)->format('d/m/Y H:i') : '-' }}</p>
                <p style="margin:0;">{{ $t->isi_tanggapan }}</p>
            </div>
        @endforeach
    </div>
    @endif

    @if ($pengaduan->riwayats->count() > 0)
    <div class="section">
        <h2>Riwayat Status</h2>
        <table>
            @foreach ($pengaduan->riwayats as $r)
                <tr>
                    <td style="width:80px;">{{ $r->created_at->format('d/m/Y') }}</td>
                    <td style="width:100px;"><strong>{{ ucfirst($r->status) }}</strong></td>
                    <td>{{ $r->keterangan }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    @endif

    <div class="footer">
        Dicetak pada {{ date('d/m/Y H:i') }} &middot; SIPEKA &copy; {{ date('Y') }}
    </div>
</body>
</html>
