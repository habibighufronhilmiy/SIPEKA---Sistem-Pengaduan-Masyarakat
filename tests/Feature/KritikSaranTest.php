<?php

use App\Models\User;
use App\Models\KritikSaran;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'masyarakat']);
    $this->petugas = User::factory()->create(['role' => 'petugas']);
});

test('masyarakat can view kritik saran list and create page', function () {
    $this->actingAs($this->user);
    $this->get('/kritik-saran')->assertOk();
    $this->get('/kritik-saran/create')->assertOk();
});

test('masyarakat can create kritik saran', function () {
    $this->actingAs($this->user);

    $this->post('/kritik-saran', [
        'kategori' => 'kritik',
        'judul' => 'Test Kritik',
        'isi_kritik' => 'Isi kritik test',
    ])->assertSessionHas('success');

    $ks = KritikSaran::first();
    expect($ks)->not->toBeNull();
    expect($ks->judul)->toBe('Test Kritik');
});

test('masyarakat can view their kritik saran detail', function () {
    $this->actingAs($this->user);

    $ks = KritikSaran::create([
        'id_user' => $this->user->id,
        'judul' => 'Detail Kritik',
        'isi_kritik' => 'Isi detail',
        'kategori' => 'saran',
    ]);

    $this->get("/kritik-saran/{$ks->id}")->assertOk()->assertSee('Detail Kritik');
});

test('petugas can respond to kritik saran', function () {
    $ks = KritikSaran::create([
        'id_user' => $this->user->id,
        'judul' => 'Kritik untuk Ditanggapi',
        'isi_kritik' => 'Isi kritik',
        'kategori' => 'kritik',
        'status' => 'menunggu',
    ]);

    $this->actingAs($this->petugas);
    $this->get("/kelola-kritik-saran/{$ks->id}")->assertOk();

    $this->post("/kelola-kritik-saran/{$ks->id}/tanggapan", [
        'tanggapan' => 'Terima kasih atas masukannya',
        'status' => 'selesai',
    ])->assertSessionHas('success');

    $ks->refresh();
    expect($ks->status)->toBe('selesai');
    expect($ks->tanggapan)->toBe('Terima kasih atas masukannya');
});
