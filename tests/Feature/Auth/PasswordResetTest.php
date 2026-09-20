<?php

use Illuminate\Support\Facades\Notification;

test('forgot password screen is unavailable', function () {
    $this->get('/forgot-password')->assertNotFound();
});

test('password reset link cannot be requested', function () {
    Notification::fake();

    $this->post('/forgot-password', ['email' => 'user@example.com'])
        ->assertNotFound();

    Notification::assertNothingSent();
});

test('password reset screen is unavailable', function () {
    $this->get('/reset-password/example-token')->assertNotFound();
});

test('password reset submission is unavailable', function () {
    $this->post('/reset-password', [
        'token' => 'example-token',
        'email' => 'user@example.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ])->assertNotFound();
});
