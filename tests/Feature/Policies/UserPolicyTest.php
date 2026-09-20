<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('only admin and superadmin can view the karyawan collection', function (string $state, bool $allowed) {
    $actor = User::factory()->{$state}()->create();

    expect(Gate::forUser($actor)->allows('viewAny', User::class))->toBe($allowed);
})->with([
    'superadmin' => ['superadmin', true],
    'admin' => ['admin', true],
    'owner' => ['owner', false],
    'karyawan' => ['karyawan', false],
]);

test('only admin and superadmin can create karyawan', function (string $state, bool $allowed) {
    $actor = User::factory()->{$state}()->create();

    expect(Gate::forUser($actor)->allows('create', User::class))->toBe($allowed);
})->with([
    'superadmin' => ['superadmin', true],
    'admin' => ['admin', true],
    'owner' => ['owner', false],
    'karyawan' => ['karyawan', false],
]);

test('admin can update only karyawan accounts', function (string $targetState, bool $allowed) {
    $admin = User::factory()->admin()->create();
    $target = User::factory()->{$targetState}()->create();

    expect(Gate::forUser($admin)->allows('update', $target))->toBe($allowed);
})->with([
    'superadmin' => ['superadmin', false],
    'owner' => ['owner', false],
    'admin' => ['admin', false],
    'karyawan' => ['karyawan', true],
]);

test('owner and karyawan cannot update karyawan accounts', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();
    $karyawan = User::factory()->karyawan()->create();

    expect(Gate::forUser($actor)->allows('update', $karyawan))->toBeFalse();
})->with(['owner', 'karyawan']);

test('admin can change active state only for karyawan accounts', function (string $ability, string $targetState, bool $allowed) {
    $admin = User::factory()->admin()->create();
    $target = User::factory()->{$targetState}()->create();

    expect(Gate::forUser($admin)->allows($ability, $target))->toBe($allowed);
})->with([
    'deactivate superadmin' => ['deactivate', 'superadmin', false],
    'deactivate owner' => ['deactivate', 'owner', false],
    'deactivate admin' => ['deactivate', 'admin', false],
    'deactivate karyawan' => ['deactivate', 'karyawan', true],
    'reactivate superadmin' => ['reactivate', 'superadmin', false],
    'reactivate owner' => ['reactivate', 'owner', false],
    'reactivate admin' => ['reactivate', 'admin', false],
    'reactivate karyawan' => ['reactivate', 'karyawan', true],
]);

test('ordinary user management never grants deletion', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();
    $karyawan = User::factory()->karyawan()->create();

    expect(Gate::forUser($actor)->allows('delete', $karyawan))->toBeFalse();
})->with(['admin', 'owner', 'karyawan']);

test('superadmin global bypass remains available for karyawan management abilities', function (string $ability) {
    $superadmin = User::factory()->superadmin()->create();
    $karyawan = User::factory()->karyawan()->create();

    expect(Gate::forUser($superadmin)->allows($ability, $karyawan))->toBeTrue();
})->with(['update', 'deactivate', 'reactivate']);

test('deactivated admin cannot manage karyawan', function (string $ability) {
    $admin = User::factory()->admin()->deactivated()->create();
    $karyawan = User::factory()->karyawan()->create();

    expect(Gate::forUser($admin)->allows($ability, $karyawan))->toBeFalse();
})->with(['update', 'deactivate', 'reactivate']);
