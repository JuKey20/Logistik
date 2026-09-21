<?php

use App\Actions\UserManagement\ListKaryawanAction;
use App\Enums\UserRole;
use App\Models\OperationalFunction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

test('create page offers only active operational functions', function () {
    $admin = User::factory()->admin()->create();
    $activeFunction = OperationalFunction::factory()->active()->create(['name' => 'Sopir']);
    OperationalFunction::factory()->deactivated()->create(['name' => 'Nonaktif']);

    $this->actingAs($admin)
        ->get('/karyawan/create')
        ->assertInertia(fn (Assert $page) => $page
            ->has('operationalFunctions', 1)
            ->where('operationalFunctions.0.id', $activeFunction->id)
            ->where('operationalFunctions.0.name', 'Sopir'));
});

test('creating karyawan requires exactly one active operational function', function (mixed $value, string $message) {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post('/karyawan', [
            'name' => 'Karyawan Baru',
            'email' => 'baru@example.com',
            'password' => 'Initial-Password-2026!',
            'password_confirmation' => 'Initial-Password-2026!',
            'operational_function_id' => $value,
        ])
        ->assertSessionHasErrors(['operational_function_id' => $message]);
})->with([
    'missing' => [null, 'Fungsi operasional wajib dipilih.'],
    'multiple values' => [[1, 2], 'Fungsi operasional yang dipilih tidak valid.'],
    'unknown id' => [999999, 'Fungsi operasional yang dipilih tidak tersedia.'],
]);

test('creating karyawan accepts an active operational function and rejects an inactive one', function () {
    $admin = User::factory()->admin()->create();
    $activeFunction = OperationalFunction::factory()->active()->create();
    $inactiveFunction = OperationalFunction::factory()->deactivated()->create();

    $this->actingAs($admin)
        ->post('/karyawan', [
            'name' => 'Karyawan Aktif',
            'email' => 'aktif@example.com',
            'password' => 'Initial-Password-2026!',
            'password_confirmation' => 'Initial-Password-2026!',
            'operational_function_id' => $activeFunction->id,
            'role' => UserRole::Superadmin->value,
            'is_active' => false,
        ])
        ->assertRedirect('/karyawan');

    $this->assertDatabaseHas('users', [
        'email' => 'aktif@example.com',
        'role' => UserRole::Karyawan->value,
        'is_active' => true,
        'operational_function_id' => $activeFunction->id,
    ]);

    $this->actingAs($admin)
        ->post('/karyawan', [
            'name' => 'Karyawan Ditolak',
            'email' => 'ditolak@example.com',
            'password' => 'Initial-Password-2026!',
            'password_confirmation' => 'Initial-Password-2026!',
            'operational_function_id' => $inactiveFunction->id,
        ])
        ->assertSessionHasErrors([
            'operational_function_id' => 'Fungsi operasional yang dipilih tidak tersedia.',
        ]);

    $this->assertDatabaseMissing('users', ['email' => 'ditolak@example.com']);
});

test('edit can change karyawan to another active operational function', function () {
    $admin = User::factory()->admin()->create();
    $currentFunction = OperationalFunction::factory()->active()->create();
    $newFunction = OperationalFunction::factory()->active()->create();
    $karyawan = User::factory()->karyawan()->for($currentFunction)->create();

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => $karyawan->name,
            'email' => $karyawan->email,
            'operational_function_id' => $newFunction->id,
        ])
        ->assertRedirect('/karyawan');

    expect($karyawan->refresh()->operational_function_id)->toBe($newFunction->id);
});

test('edit rejects a newly selected inactive operational function', function () {
    $admin = User::factory()->admin()->create();
    $currentFunction = OperationalFunction::factory()->active()->create();
    $inactiveFunction = OperationalFunction::factory()->deactivated()->create();
    $karyawan = User::factory()->karyawan()->for($currentFunction)->create();

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => $karyawan->name,
            'email' => $karyawan->email,
            'operational_function_id' => $inactiveFunction->id,
        ])
        ->assertSessionHasErrors([
            'operational_function_id' => 'Fungsi operasional yang dipilih tidak tersedia.',
        ]);

    expect($karyawan->refresh()->operational_function_id)->toBe($currentFunction->id);
});

test('edit preserves an unchanged inactive operational function', function () {
    $admin = User::factory()->admin()->create();
    $inactiveFunction = OperationalFunction::factory()->deactivated()->create();
    $karyawan = User::factory()->karyawan()->for($inactiveFunction)->create();

    $this->actingAs($admin)
        ->get("/karyawan/{$karyawan->id}/edit")
        ->assertInertia(fn (Assert $page) => $page
            ->where('karyawan.operational_function.id', $inactiveFunction->id)
            ->where('karyawan.operational_function.is_active', false)
            ->where('operationalFunctions.0.id', $inactiveFunction->id));

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => 'Nama Tetap Valid',
            'email' => $karyawan->email,
            'operational_function_id' => $inactiveFunction->id,
        ])
        ->assertRedirect('/karyawan');

    $this->assertDatabaseHas('users', [
        'id' => $karyawan->id,
        'name' => 'Nama Tetap Valid',
        'operational_function_id' => $inactiveFunction->id,
    ]);
});

test('legacy karyawan without a function must select an active function when edited', function () {
    $admin = User::factory()->admin()->create();
    $karyawan = User::factory()->karyawan()->create(['operational_function_id' => null]);

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => $karyawan->name,
            'email' => $karyawan->email,
        ])
        ->assertSessionHasErrors(['operational_function_id' => 'Fungsi operasional wajib dipilih.']);
});

test('karyawan list serializes the operational function without an n plus one query', function () {
    $function = OperationalFunction::factory()->create(['name' => 'Sopir']);
    User::factory()->karyawan()->count(10)->for($function)->create();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $paginator = app(ListKaryawanAction::class)->handle(null);

    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    expect($queries)->toHaveCount(3);

    foreach ($paginator->items() as $karyawan) {
        expect($karyawan->relationLoaded('operationalFunction'))->toBeTrue()
            ->and($karyawan->operationalFunction?->name)->toBe('Sopir');
    }

    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/karyawan')
        ->assertInertia(fn (Assert $page) => $page
            ->where('karyawan.data.0.operational_function.name', 'Sopir')
            ->where('karyawan.data.0.operational_function.is_active', true));
});
