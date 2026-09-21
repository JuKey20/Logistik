<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('migration keeps a pre-existing karyawan operational function unassigned', function () {
    Schema::table('users', function (Blueprint $table): void {
        $table->dropForeign(['operational_function_id']);
        $table->dropIndex('users_operational_function_idx');
        $table->dropColumn('operational_function_id');
    });
    Schema::drop('operational_functions');

    $userId = DB::table('users')->insertGetId([
        'name' => 'Karyawan Lama',
        'email' => 'karyawan.lama@example.com',
        'password' => 'existing-password-hash',
        'role' => 'karyawan',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $createOperationalFunctions = require database_path('migrations/2026_09_20_143811_create_operational_functions_table.php');
    $addOperationalFunctionToUsers = require database_path('migrations/2026_09_20_143812_add_operational_function_id_to_users_table.php');

    $createOperationalFunctions->up();
    $addOperationalFunctionToUsers->up();

    expect(DB::table('users')->where('id', $userId)->value('operational_function_id'))->toBeNull()
        ->and(DB::table('operational_functions')->count())->toBe(0);
});
