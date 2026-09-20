<?php

use App\Enums\UserRole;
use App\Models\User;

test('role enum exposes exactly the approved values', function () {
    expect(array_column(UserRole::cases(), 'value'))->toBe([
        'superadmin',
        'owner',
        'admin',
        'karyawan',
    ]);
});

test('role and active state are persisted with native casts', function () {
    $user = User::factory()->owner()->deactivated()->create();

    expect($user->refresh())
        ->role->toBe(UserRole::Owner)
        ->is_active->toBeFalse();
});

test('factory provides every role state', function (string $state, UserRole $role) {
    $user = User::factory()->{$state}()->create();

    expect($user->role)->toBe($role);
})->with([
    'superadmin' => ['superadmin', UserRole::Superadmin],
    'owner' => ['owner', UserRole::Owner],
    'admin' => ['admin', UserRole::Admin],
    'karyawan' => ['karyawan', UserRole::Karyawan],
]);

test('factory defaults to an active karyawan', function () {
    $user = User::factory()->create();

    expect($user)
        ->role->toBe(UserRole::Karyawan)
        ->is_active->toBeTrue();
});

test('factory can explicitly create an active or deactivated user', function () {
    expect(User::factory()->active()->create()->is_active)->toBeTrue()
        ->and(User::factory()->deactivated()->create()->is_active)->toBeFalse();
});

test('invalid role values are rejected by the enum cast', function () {
    User::factory()->create(['role' => 'manager']);
})->throws(ValueError::class);

test('role and active state cannot be mass assigned', function () {
    $user = new User;

    $user->fill([
        'name' => 'Unsafe User',
        'email' => 'unsafe@example.com',
        'password' => 'password',
        'role' => UserRole::Superadmin,
        'is_active' => false,
    ]);

    expect($user->role)->toBeNull()
        ->and($user->is_active)->toBeNull();
});
