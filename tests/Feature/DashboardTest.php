<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('a deactivated user with an existing session is logged out', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $user->forceFill(['is_active' => false])->saveQuietly();

    $this->get(route('dashboard'))->assertRedirect(route('login'));

    $this->assertGuest();
});
