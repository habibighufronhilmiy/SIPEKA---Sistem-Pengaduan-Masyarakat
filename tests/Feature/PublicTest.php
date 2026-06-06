<?php

use App\Models\User;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Pengumuman;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('public tracking shows report by tracking code', function () {
    $user = User::factory()->create(['role' => 'masyarakat']);
    $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

    $pengaduan = Pengaduan::create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
        'judul' => 'Tracked Report',
        'isi_laporan' => 'Isi tracked',
        'lokasi' => 'Jl. Tracking',
        'status' => 'diverifikasi',
        'kode_tracking' => 'SPK-TRACK',
    ]);

    $this->post('/tracking', [
        'kode' => 'SPK-TRACK',
    ])->assertSee($pengaduan->judul);
});

test('public announcements page loads', function () {
    $this->get('/pengumuman-umum')->assertOk();
});

test('public voting results page loads', function () {
    $this->get('/hasil-voting')->assertOk();
});

test('public dapat melihat pengumuman', function () {
    $pengumuman = Pengumuman::create([
        'judul' => 'Pengumuman Test',
        'isi' => 'Isi pengumuman',
        'tipe' => 'pengumuman',
        'status' => 'publish',
        'tanggal_mulai' => now(),
        'tanggal_selesai' => now()->addDays(7),
    ]);

    $this->get('/pengumuman-umum')->assertOk()->assertSee('Pengumuman Test');
});

test('masyarakat dapat melihat pengaduan ditolak', function () {
    $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);
    $user = User::factory()->create(['role' => 'masyarakat']);

    $pengaduan = Pengaduan::create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
        'judul' => 'Laporan Ditolak AI',
        'isi_laporan' => 'Isi laporan yang ditolak',
        'lokasi' => 'Jl. Test',
        'status' => 'ditolak',
        'kode_tracking' => 'SPK-DTLK01',
    ]);

    $this->actingAs($user);
    $this->get('/pengaduan')->assertOk()->assertSee('Laporan Ditolak AI')->assertSee('Ditolak');
    $this->get("/pengaduan/{$pengaduan->id}")->assertOk()->assertSee('Laporan Ditolak AI');
});
