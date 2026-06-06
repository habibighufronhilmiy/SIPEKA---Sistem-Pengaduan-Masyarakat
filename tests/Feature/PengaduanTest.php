<?php

use App\Models\User;
use App\Models\Kategori;
use App\Models\Pengaduan;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'masyarakat']);
    $this->kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);
});

test('masyarakat can view pengaduan index and create page', function () {
    $this->actingAs($this->user);
    $this->get('/pengaduan')->assertOk();
    $this->get('/pengaduan/create')->assertOk();
});

test('masyarakat can create pengaduan', function () {
    $this->actingAs($this->user);

    $this->post('/pengaduan', [
        'id_kategori' => $this->kategori->id,
        'judul' => 'Jalan Rusak di Merdeka',
        'isi_laporan' => 'Jalan mengalami kerusakan parah',
        'lokasi' => 'Jl. Merdeka No.1',
        'latitude' => '-6.2',
        'longitude' => '106.8',
        'draft' => '0',
    ])->assertSessionHas('success');

    $pengaduan = Pengaduan::first();
    expect($pengaduan)->not->toBeNull();
    expect($pengaduan->judul)->toBe('Jalan Rusak di Merdeka');
    expect($pengaduan->status)->toBe('diverifikasi');
});

test('masyarakat can view their pengaduan detail', function () {
    $this->actingAs($this->user);

    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'Test Report',
        'isi_laporan' => 'Isi test',
        'lokasi' => 'Jl. Test',
        'status' => 'menunggu',
        'kode_tracking' => 'SPK-TEST01',
    ]);

    $this->get("/pengaduan/{$pengaduan->id}")->assertOk()->assertSee('Test Report');
});

test('masyarakat can edit pengaduan while status menunggu', function () {
    $this->actingAs($this->user);

    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'Judul Awal',
        'isi_laporan' => 'Isi awal',
        'lokasi' => 'Lokasi awal',
        'status' => 'menunggu',
        'kode_tracking' => 'SPK-EDIT01',
    ]);

    $this->get("/pengaduan/{$pengaduan->id}/edit")->assertOk();

    $this->put("/pengaduan/{$pengaduan->id}", [
        'id_kategori' => $this->kategori->id,
        'judul' => 'Judul Diubah',
        'isi_laporan' => 'Isi diubah',
        'lokasi' => 'Lokasi baru',
    ])->assertSessionHas('success');

    $pengaduan->refresh();
    expect($pengaduan->judul)->toBe('Judul Diubah');
});

test('masyarakat can create draft and submit it', function () {
    $this->actingAs($this->user);

    $this->post('/pengaduan', [
        'id_kategori' => $this->kategori->id,
        'judul' => 'Draft Report',
        'isi_laporan' => 'Isi draft',
        'lokasi' => 'Jl. Draft',
        'draft' => '1',
    ])->assertSessionHas('success');

    $pengaduan = Pengaduan::where('draft', true)->first();
    expect($pengaduan)->not->toBeNull();
    expect($pengaduan->draft)->toBe(1);

    $this->post("/pengaduan/{$pengaduan->id}/submit")->assertSessionHas('success');

    $pengaduan->refresh();
    expect($pengaduan->draft)->toBe(0);
});

test('masyarakat can give rating to completed pengaduan', function () {
    $this->actingAs($this->user);

    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'Selesai Report',
        'isi_laporan' => 'Isi selesai',
        'lokasi' => 'Jl. Selesai',
        'status' => 'selesai',
        'kode_tracking' => 'SPK-RATE01',
    ]);

    $this->post("/pengaduan/{$pengaduan->id}/rating", [
        'rating' => 5,
        'komentar' => 'Sangat bagus!',
    ])->assertSessionHas('success');

    expect($pengaduan->rating)->not->toBeNull();
    expect($pengaduan->rating->rating)->toBe(5);
});

test('masyarakat can download pengaduan pdf', function () {
    $this->actingAs($this->user);

    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $this->kategori->id,
        'judul' => 'PDF Report',
        'isi_laporan' => 'Isi PDF',
        'lokasi' => 'Jl. PDF',
        'status' => 'diverifikasi',
        'kode_tracking' => 'SPK-PDF01',
    ]);

    $this->get("/pengaduan/{$pengaduan->id}/pdf")->assertOk();
});
