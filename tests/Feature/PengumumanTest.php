<?php

use App\Models\User;
use App\Models\Pengumuman;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

test('admin can view announcements list', function () {
    $this->actingAs($this->admin);
    $this->get('/pengumuman')->assertOk();
});

test('admin can create announcement', function () {
    $this->actingAs($this->admin);

    $this->post('/pengumuman', [
        'judul' => 'Pengumuman Baru',
        'isi' => 'Isi pengumuman baru',
        'tipe' => 'pengumuman',
        'status' => 'publish',
        'tanggal_mulai' => now()->format('Y-m-d H:i'),
        'tanggal_selesai' => now()->addDays(7)->format('Y-m-d H:i'),
    ])->assertSessionHas('success');

    expect(Pengumuman::where('judul', 'Pengumuman Baru')->exists())->toBeTrue();
});

test('admin can edit announcement', function () {
    $this->actingAs($this->admin);

    $pengumuman = Pengumuman::create([
        'judul' => 'Pengumuman Lama',
        'isi' => 'Isi lama',
        'tipe' => 'pengumuman',
        'status' => 'publish',
        'tanggal_mulai' => now(),
        'tanggal_selesai' => now()->addDays(7),
    ]);

    $this->put("/pengumuman/{$pengumuman->id}", [
        'judul' => 'Pengumuman Diupdate',
        'isi' => 'Isi diupdate',
        'tipe' => 'pengumuman',
        'status' => 'publish',
        'tanggal_mulai' => now()->format('Y-m-d H:i'),
        'tanggal_selesai' => now()->addDays(7)->format('Y-m-d H:i'),
    ])->assertSessionHas('success');

    $pengumuman->refresh();
    expect($pengumuman->judul)->toBe('Pengumuman Diupdate');
});

test('admin can delete announcement', function () {
    $this->actingAs($this->admin);

    $pengumuman = Pengumuman::create([
        'judul' => 'To Delete',
        'isi' => 'Isi',
        'tipe' => 'pengumuman',
        'status' => 'publish',
        'tanggal_mulai' => now(),
        'tanggal_selesai' => now()->addDays(7),
    ]);

    $this->delete("/pengumuman/{$pengumuman->id}")->assertSessionHas('success');
    expect(Pengumuman::count())->toBe(0);
});
