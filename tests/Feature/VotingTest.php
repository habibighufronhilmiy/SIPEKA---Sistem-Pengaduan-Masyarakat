<?php

use App\Models\User;
use App\Models\Voting;
use App\Models\PilihanVoting;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->user = User::factory()->create(['role' => 'masyarakat']);
});

test('admin can create voting with options', function () {
    $this->actingAs($this->admin);

    $this->get('/voting/create')->assertOk();

    $this->post('/voting', [
        'pertanyaan' => 'Test Voting?',
        'deskripsi' => 'Voting test deskripsi',
        'tanggal_mulai' => now()->format('Y-m-d H:i'),
        'tanggal_selesai' => now()->addDays(7)->format('Y-m-d H:i'),
        'pilihans' => ['Opsi A', 'Opsi B', 'Opsi C'],
    ])->assertSessionHas('success');

    $voting = Voting::first();
    expect($voting)->not->toBeNull();
    expect($voting->pertanyaan)->toBe('Test Voting?');
    expect($voting->pilihans)->toHaveCount(3);
});

test('masyarakat can view voting and vote', function () {
    $voting = Voting::create([
        'id_user' => $this->admin->id,
        'pertanyaan' => 'Pertanyaan Voting?',
        'deskripsi' => 'Deskripsi',
        'tanggal_mulai' => now(),
        'tanggal_selesai' => now()->addDays(7),
        'status' => 'aktif',
    ]);

    $pilihan = PilihanVoting::create(['id_voting' => $voting->id, 'pilihan' => 'Opsi A']);
    PilihanVoting::create(['id_voting' => $voting->id, 'pilihan' => 'Opsi B']);

    $this->actingAs($this->user);
    $this->get("/voting/{$voting->id}")->assertOk();

    $this->post("/voting/{$voting->id}/vote", [
        'id_pilihan' => $pilihan->id,
    ])->assertSessionHas('success');
});
