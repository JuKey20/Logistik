<?php

use App\Models\User;

test('two factor authentication endpoints are unavailable', function (string $method, string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->call($method, $uri)->assertNotFound();
})->with([
    ['GET', '/two-factor-challenge'],
    ['POST', '/two-factor-challenge'],
    ['POST', '/user/two-factor-authentication'],
    ['POST', '/user/confirmed-two-factor-authentication'],
    ['DELETE', '/user/two-factor-authentication'],
    ['GET', '/user/two-factor-qr-code'],
    ['GET', '/user/two-factor-recovery-codes'],
    ['POST', '/user/two-factor-recovery-codes'],
    ['GET', '/user/two-factor-secret-key'],
]);
