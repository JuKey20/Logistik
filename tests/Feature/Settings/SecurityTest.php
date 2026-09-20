<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

test('security page is displayed', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('security.edit'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('settings/security')
            ->has('passwordRules')
            ->missing('canManagePasskeys')
            ->missing('passkeys')
            ->missing('canManageTwoFactor')
            ->missing('twoFactorEnabled')
            ->missing('requiresConfirmation'),
        );
});

test('guests cannot view security settings', function () {
    $this->get('/settings/security')->assertRedirect(route('login'));
});

test('password can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('security.edit'))
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('security.edit'));

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('security.edit'))
        ->put(route('user-password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect(route('security.edit'));
});

test('guests cannot update a password', function () {
    $this->put('/settings/password', [
        'current_password' => 'password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ])->assertRedirect(route('login'));
});

test('passkey endpoints are unavailable', function (string $method, string $uri) {
    $user = User::factory()->create();

    $this->actingAs($user)->call($method, $uri)->assertNotFound();
})->with([
    ['GET', '/passkeys/login/options'],
    ['POST', '/passkeys/login'],
    ['GET', '/passkeys/confirm/options'],
    ['POST', '/passkeys/confirm'],
    ['GET', '/user/passkeys/options'],
    ['POST', '/user/passkeys'],
    ['DELETE', '/user/passkeys/1'],
    ['GET', '/.well-known/passkey-endpoints'],
]);
