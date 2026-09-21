<?php

use App\Models\OperationalFunction;
use App\Models\User;

test('operational function and karyawan have a one to many relationship', function () {
    $operationalFunction = OperationalFunction::factory()->create();
    $karyawan = User::factory()->karyawan()->for($operationalFunction)->create();

    expect($karyawan->operationalFunction->is($operationalFunction))->toBeTrue()
        ->and($operationalFunction->karyawan()->sole()->is($karyawan))->toBeTrue();
});

test('operational function active state is cast to boolean', function () {
    $operationalFunction = OperationalFunction::factory()->create(['is_active' => 1]);

    expect($operationalFunction->is_active)->toBeTrue();
});
