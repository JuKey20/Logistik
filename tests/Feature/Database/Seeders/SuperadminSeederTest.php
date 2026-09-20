<?php

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\SuperadminSeeder;
use Illuminate\Support\Facades\Hash;

test('deployment configuration bootstraps the initial superadmin', function () {
    config()->set('auth.bootstrap_superadmin', [
        'name' => 'Initial Superadmin',
        'email' => 'superadmin@example.com',
        'password' => 'Secure-Password-2026!',
    ]);

    $this->seed(SuperadminSeeder::class);

    $superadmin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

    expect($superadmin)
        ->name->toBe('Initial Superadmin')
        ->role->toBe(UserRole::Superadmin)
        ->is_active->toBeTrue()
        ->and(Hash::check('Secure-Password-2026!', $superadmin->password))->toBeTrue();
});

test('bootstrap fails explicitly when required configuration is missing', function () {
    config()->set('auth.bootstrap_superadmin', [
        'name' => null,
        'email' => null,
        'password' => null,
    ]);

    $this->seed(SuperadminSeeder::class);
})->throws(InvalidArgumentException::class);

test('bootstrap is idempotent for the configured identity', function () {
    config()->set('auth.bootstrap_superadmin', [
        'name' => 'Initial Superadmin',
        'email' => 'superadmin@example.com',
        'password' => 'Secure-Password-2026!',
    ]);

    $this->seed(SuperadminSeeder::class);
    $this->seed(SuperadminSeeder::class);

    expect(User::query()->where('role', UserRole::Superadmin->value)->count())->toBe(1);
});

test('bootstrap never silently promotes an existing ordinary user', function () {
    $user = User::factory()->karyawan()->create(['email' => 'superadmin@example.com']);

    config()->set('auth.bootstrap_superadmin', [
        'name' => 'Initial Superadmin',
        'email' => $user->email,
        'password' => 'Secure-Password-2026!',
    ]);

    expect(fn () => $this->seed(SuperadminSeeder::class))->toThrow(LogicException::class);
    expect($user->refresh()->role)->toBe(UserRole::Karyawan);
});

test('bootstrap rejects a different identity after the initial superadmin exists', function () {
    User::factory()->superadmin()->create(['email' => 'existing-superadmin@example.com']);

    config()->set('auth.bootstrap_superadmin', [
        'name' => 'Another Superadmin',
        'email' => 'another-superadmin@example.com',
        'password' => 'Secure-Password-2026!',
    ]);

    expect(fn () => $this->seed(SuperadminSeeder::class))->toThrow(LogicException::class);
    expect(User::query()->where('role', UserRole::Superadmin->value)->count())->toBe(1);
});
