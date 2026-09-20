<?php

use App\Models\User;

test('email verification routes are unavailable', function (string $method, string $uri) {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->call($method, $uri)->assertNotFound();
})->with([
    ['GET', '/email/verify'],
    ['GET', '/email/verify/1/example-hash'],
]);

test('users do not need a verified email to access authenticated pages', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get('/dashboard')->assertOk();
    $this->actingAs($user)->get('/settings/security')->assertOk();
});
