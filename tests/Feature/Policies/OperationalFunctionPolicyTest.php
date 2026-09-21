<?php

use App\Models\OperationalFunction;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('only admin and superadmin can access operational function management', function (string $state, bool $allowed) {
    $actor = User::factory()->{$state}()->create();

    expect(Gate::forUser($actor)->allows('viewAny', OperationalFunction::class))->toBe($allowed)
        ->and(Gate::forUser($actor)->allows('create', OperationalFunction::class))->toBe($allowed);
})->with([
    'superadmin' => ['superadmin', true],
    'admin' => ['admin', true],
    'owner' => ['owner', false],
    'karyawan' => ['karyawan', false],
]);

test('only admin and superadmin can modify an operational function', function (string $state, bool $allowed) {
    $actor = User::factory()->{$state}()->create();
    $operationalFunction = OperationalFunction::factory()->create();

    expect(Gate::forUser($actor)->allows('update', $operationalFunction))->toBe($allowed)
        ->and(Gate::forUser($actor)->allows('deactivate', $operationalFunction))->toBe($allowed)
        ->and(Gate::forUser($actor)->allows('reactivate', $operationalFunction))->toBe($allowed);
})->with([
    'superadmin' => ['superadmin', true],
    'admin' => ['admin', true],
    'owner' => ['owner', false],
    'karyawan' => ['karyawan', false],
]);

test('operational function management never grants deletion to ordinary roles', function (string $state) {
    $actor = User::factory()->{$state}()->create();
    $operationalFunction = OperationalFunction::factory()->create();

    expect(Gate::forUser($actor)->allows('delete', $operationalFunction))->toBeFalse();
})->with(['admin', 'owner', 'karyawan']);

test('deactivated administrators cannot manage operational functions', function (string $state) {
    $actor = User::factory()->{$state}()->deactivated()->create();

    expect(Gate::forUser($actor)->allows('viewAny', OperationalFunction::class))->toBeFalse();
})->with(['superadmin', 'admin']);
