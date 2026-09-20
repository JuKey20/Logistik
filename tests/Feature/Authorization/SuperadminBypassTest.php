<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('an active superadmin bypasses ability checks', function () {
    Gate::define('restricted-test-ability', fn (User $user): bool => false);

    $superadmin = User::factory()->superadmin()->active()->create();

    expect(Gate::forUser($superadmin)->allows('restricted-test-ability'))->toBeTrue();
});

test('ordinary roles do not bypass ability checks', function (string $state) {
    Gate::define('restricted-test-ability', fn (User $user): bool => false);

    $user = User::factory()->{$state}()->active()->create();

    expect(Gate::forUser($user)->allows('restricted-test-ability'))->toBeFalse();
})->with(['owner', 'admin', 'karyawan']);

test('a deactivated superadmin cannot bypass ability checks', function () {
    Gate::define('restricted-test-ability', fn (User $user): bool => true);

    $superadmin = User::factory()->superadmin()->deactivated()->create();

    expect(Gate::forUser($superadmin)->allows('restricted-test-ability'))->toBeFalse();
});
