<?php

use App\Models\User;
use App\Models\Notifikasi;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'masyarakat']);
});

test('user can view notifications page', function () {
    $this->actingAs($this->user);
    $this->get('/notifikasi')->assertOk();
});

test('user can mark notification as read', function () {
    $this->actingAs($this->user);

    $notif = Notifikasi::create([
        'id_user' => $this->user->id,
        'judul' => 'Test Notif',
        'pesan' => 'Pesan notifikasi',
        'tipe' => 'info',
        'is_read' => false,
    ]);

    $this->post("/notifikasi/read/{$notif->id}")->assertRedirect();

    $notif->refresh();
    expect($notif->is_read)->toBeTrue();
});

test('user can mark all notifications as read', function () {
    $this->actingAs($this->user);

    Notifikasi::create(['id_user' => $this->user->id, 'judul' => 'Notif 1', 'pesan' => 'Pesan 1', 'tipe' => 'info', 'is_read' => false]);
    Notifikasi::create(['id_user' => $this->user->id, 'judul' => 'Notif 2', 'pesan' => 'Pesan 2', 'tipe' => 'info', 'is_read' => false]);

    $this->post('/notifikasi/read-all')->assertRedirect();

    expect(Notifikasi::where('is_read', false)->count())->toBe(0);
});
