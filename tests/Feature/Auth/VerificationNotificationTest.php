<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;

test('email verification notification endpoint is unavailable', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->post('/email/verification-notification')
        ->assertNotFound();

    Notification::assertNothingSent();
});
