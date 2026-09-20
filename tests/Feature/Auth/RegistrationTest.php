<?php

use App\Models\User;

test('public registration screen is unavailable', function () {
    $this->get('/register')->assertNotFound();
});

test('public registration submission is unavailable', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertGuest();
    expect(User::query()->where('email', 'test@example.com')->exists())->toBeFalse();
});
