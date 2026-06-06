<?php

use App\Models\User;
use App\Models\Kategori;
use App\Models\Pengaduan;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'masyarakat']);
    $this->petugas = User::factory()->create(['role' => 'petugas']);
    $this->kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);
});

test('petugas can view pengaduan list', function () {
    $this->actingAs($this->petugas);
    $this->get('/petugas/pengaduan')->assertOk();
});

test('petugas can view pengaduan detail', function () {
    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'Petugas View',
        'isi_laporan' => 'Isi laporan',
        'lokasi' => 'Jl. Test',
        'status' => 'diverifikasi',
        'kode_tracking' => 'SPK-PTG01',
    ]);

    $this->actingAs($this->petugas);
    $this->get("/petugas/pengaduan/{$pengaduan->id}")->assertOk()->assertSee('Petugas View');
});

test('petugas can process pengaduan from verified to processed', function () {
    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'Process Report',
        'isi_laporan' => 'Isi laporan',
        'lokasi' => 'Jl. Test',
        'status' => 'diverifikasi',
        'kode_tracking' => 'SPK-PTG02',
    ]);

    $this->actingAs($this->petugas);
    $this->post("/petugas/pengaduan/{$pengaduan->id}/proses")->assertSessionHas('success');

    $pengaduan->refresh();
    expect($pengaduan->status)->toBe('diproses');
});

test('petugas can complete processed pengaduan', function () {
    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'Complete Report',
        'isi_laporan' => 'Isi laporan',
        'lokasi' => 'Jl. Test',
        'status' => 'diproses',
        'id_petugas' => $this->petugas->id,
        'kode_tracking' => 'SPK-PTG03',
    ]);

    $this->actingAs($this->petugas);
    $this->post("/petugas/pengaduan/{$pengaduan->id}/selesai", [
        'isi_tanggapan' => 'Laporan telah ditangani',
    ])->assertSessionHas('success');

    $pengaduan->refresh();
    expect($pengaduan->status)->toBe('selesai');
});

test('petugas cannot complete report assigned to another petugas', function () {
    $anotherPetugas = User::factory()->create(['role' => 'petugas']);

    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'Auth Test',
        'isi_laporan' => 'Isi auth test',
        'lokasi' => 'Jl. Auth',
        'status' => 'diproses',
        'id_petugas' => $anotherPetugas->id,
        'kode_tracking' => 'SPK-PTG04',
    ]);

    $this->actingAs($this->petugas);
    $this->post("/petugas/pengaduan/{$pengaduan->id}/selesai", [
        'isi_tanggapan' => 'Mencoba selesaikan',
    ])->assertSessionHas('error');
});

test('petugas sees rejected report', function () {
    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'Laporan Ditolak',
        'isi_laporan' => 'Isi',
        'lokasi' => 'Jl. Test',
        'status' => 'ditolak',
        'kode_tracking' => 'SPK-DTLK',
    ]);

    $this->actingAs($this->petugas);
    $this->get('/petugas/pengaduan')->assertOk()->assertSee('Laporan Ditolak');
    $this->get("/petugas/pengaduan/{$pengaduan->id}")->assertOk()->assertSee('Ditolak');
});
