<?php

use App\Models\User;
use App\Models\Kategori;
use App\Models\Pengaduan;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->petugas = User::factory()->create(['role' => 'petugas']);
    $this->user = User::factory()->create(['role' => 'masyarakat']);
});

test('admin can view users list', function () {
    $this->actingAs($this->admin);
    $this->get('/admin/users')->assertOk();
});

test('admin can create user', function () {
    $this->actingAs($this->admin);

    $this->get('/admin/users/create')->assertOk();

    $this->post('/admin/users', [
        'name' => 'New User',
        'username' => 'newuser',
        'email' => 'new@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'masyarakat',
    ])->assertSessionHas('success');

    expect(User::where('username', 'newuser')->exists())->toBeTrue();
});

test('admin can edit user', function () {
    $this->actingAs($this->admin);

    $user = User::factory()->create(['role' => 'masyarakat']);

    $this->get("/admin/users/{$user->id}/edit")->assertOk();

    $this->put("/admin/users/{$user->id}", [
        'name' => 'Updated Name',
        'username' => $user->username,
        'email' => $user->email,
        'role' => 'masyarakat',
    ])->assertSessionHas('success');

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
});

test('admin can manage categories', function () {
    $this->actingAs($this->admin);

    $this->get('/admin/kategoris')->assertOk();

    $this->post('/admin/kategoris', [
        'nama_kategori' => 'Test Kategori',
        'slug' => 'test-kategori',
    ])->assertSessionHas('success');

    $kategori = Kategori::first();
    expect($kategori)->not->toBeNull();
    expect($kategori->nama_kategori)->toBe('Test Kategori');

    $this->post("/admin/kategoris/{$kategori->id}", [
        'nama_kategori' => 'Updated Kategori',
        'slug' => 'test-kategori',
    ])->assertSessionHas('success');

    $this->delete("/admin/kategoris/{$kategori->id}")->assertSessionHas('success');
    expect(Kategori::count())->toBe(0);
});

test('admin can view and delete pengaduan', function () {
    $kategori = Kategori::create(['nama_kategori' => 'Infrastruktur', 'slug' => 'infrastruktur']);

    $pengaduan = Pengaduan::create([
        'id_user' => $this->user->id,
        'id_kategori' => $kategori->id,
        'judul' => 'Admin Report',
        'isi_laporan' => 'Isi laporan',
        'lokasi' => 'Jl. Admin',
        'status' => 'menunggu',
        'kode_tracking' => 'SPK-ADM01',
    ]);

    $this->actingAs($this->admin);
    $this->get('/admin/pengaduan')->assertOk();
    $this->get("/admin/pengaduan/{$pengaduan->id}")->assertOk();

    $this->delete("/admin/pengaduan/{$pengaduan->id}")->assertSessionHas('success');
    expect(Pengaduan::count())->toBe(0);
});
