<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

test('existing users are backfilled as active karyawan', function () {
    Schema::table('users', function (Blueprint $table): void {
        $table->dropColumn(['role', 'is_active']);
    });

    DB::table('users')->insert([
        'name' => 'Existing User',
        'email' => 'existing@example.com',
        'password' => 'existing-password-hash',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $migration = require database_path('migrations/2026_09_20_132608_add_access_foundation_to_users_table.php');
    $migration->up();

    expect(DB::table('users')->where('email', 'existing@example.com')->value('role'))->toBe('karyawan')
        ->and(DB::table('users')->where('email', 'existing@example.com')->value('is_active'))->toBe(1);
});
