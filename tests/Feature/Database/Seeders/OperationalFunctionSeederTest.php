<?php

use Database\Seeders\OperationalFunctionSeeder;

test('seeder creates the initial active operational functions', function () {
    $this->seed(OperationalFunctionSeeder::class);

    $this->assertDatabaseHas('operational_functions', [
        'name' => 'Sopir',
        'normalized_name' => 'sopir',
        'is_active' => true,
    ]);
    $this->assertDatabaseHas('operational_functions', [
        'name' => 'Petugas Lapangan',
        'normalized_name' => 'petugas lapangan',
        'is_active' => true,
    ]);
});

test('repeated seeding does not duplicate or overwrite managed functions', function () {
    $this->seed(OperationalFunctionSeeder::class);

    $functionId = (int) $this->app['db']->table('operational_functions')
        ->where('normalized_name', 'sopir')
        ->value('id');

    $this->app['db']->table('operational_functions')
        ->where('id', $functionId)
        ->update(['is_active' => false]);

    $this->seed(OperationalFunctionSeeder::class);

    $this->assertDatabaseCount('operational_functions', 2);
    $this->assertDatabaseHas('operational_functions', [
        'id' => $functionId,
        'normalized_name' => 'sopir',
        'is_active' => false,
    ]);
});
