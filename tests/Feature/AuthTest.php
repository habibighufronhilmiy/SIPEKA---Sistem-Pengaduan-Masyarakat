<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('public routes load successfully', function () {
    $this->get('/')->assertOk();
    $this->get('/login')->assertOk();
    $this->get('/register')->assertOk();
    $this->get('/forgot-password')->assertOk();
    $this->get('/faq')->assertOk();
    $this->get('/tentang')->assertOk();
});

test('user can register', function () {
    $this->post('/register', [
        'name' => 'New User',
        'username' => 'newuser',
        'email' => 'newuser@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'telepon' => '08123456789',
    ])->assertRedirect('/dashboard');

    expect(User::where('email', 'newuser@test.com')->exists())->toBeTrue();
    $this->assertAuthenticated();
});

test('user can login and access dashboard', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
        'password' => bcrypt('password123'),
    ]);

    $this->post('/login', [
        'login' => 'testuser',
        'password' => 'password123',
    ])->assertSessionMissing('errors');

    $this->assertAuthenticated();
    $this->get('/dashboard')->assertOk();
});

test('unauthenticated user cannot access dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('user can logout', function () {
    $user = User::factory()->create(['role' => 'masyarakat']);
    $this->actingAs($user)->post('/logout')->assertRedirect('/');
    $this->assertGuest();
});
