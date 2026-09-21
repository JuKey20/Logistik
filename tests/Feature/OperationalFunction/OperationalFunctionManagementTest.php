<?php

use App\Models\OperationalFunction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Inertia\Testing\AssertableInertia as Assert;

test('guest is redirected from operational function management to login', function () {
    $this->get('/operational-functions')->assertRedirect(route('login'));
});

test('admin and superadmin can view operational functions', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();

    $this->actingAs($actor)
        ->get('/operational-functions')
        ->assertInertia(fn (Assert $page) => $page
            ->component('operational-functions/index'));
})->with(['admin', 'superadmin']);

test('owner and karyawan cannot view operational functions', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();

    $this->actingAs($actor)->get('/operational-functions')->assertForbidden();
})->with(['owner', 'karyawan']);

test('index is paginated and exposes only master data fields', function () {
    $admin = User::factory()->admin()->create();
    OperationalFunction::factory()->count(16)->sequence(
        fn (Sequence $sequence): array => [
            'name' => sprintf('Fungsi %02d', $sequence->index),
            'normalized_name' => sprintf('fungsi %02d', $sequence->index),
        ],
    )->create();

    $this->actingAs($admin)
        ->get('/operational-functions')
        ->assertInertia(fn (Assert $page) => $page
            ->has('operationalFunctions.data', 15)
            ->where('operationalFunctions.last_page', 2)
            ->has('operationalFunctions.data.0', fn (Assert $function) => $function
                ->hasAll(['id', 'name', 'is_active'])
                ->missingAll(['normalized_name', 'created_at', 'updated_at'])));
});

test('admin and superadmin can open the create page', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();

    $this->actingAs($actor)
        ->get('/operational-functions/create')
        ->assertInertia(fn (Assert $page) => $page
            ->component('operational-functions/create'));
})->with(['admin', 'superadmin']);

test('admin can create an active operational function', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post('/operational-functions', [
            'name' => '  Koordinator   Lapangan  ',
            'is_active' => false,
        ])
        ->assertRedirect('/operational-functions');

    $this->assertDatabaseHas('operational_functions', [
        'name' => 'Koordinator Lapangan',
        'normalized_name' => 'koordinator lapangan',
        'is_active' => true,
    ]);
});

test('owner and karyawan cannot create operational functions', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();

    $this->actingAs($actor)
        ->post('/operational-functions', ['name' => 'Tidak Diizinkan'])
        ->assertForbidden();

    $this->assertDatabaseMissing('operational_functions', [
        'normalized_name' => 'tidak diizinkan',
    ]);
})->with(['owner', 'karyawan']);

test('create rejects missing invalid and duplicate normalized names', function (array $payload, string $message) {
    $admin = User::factory()->admin()->create();
    OperationalFunction::factory()->create([
        'name' => 'Petugas Lapangan',
        'normalized_name' => 'petugas lapangan',
    ]);

    $this->actingAs($admin)
        ->post('/operational-functions', $payload)
        ->assertSessionHasErrors(['name' => $message]);
})->with([
    'missing name' => [[], 'Nama fungsi operasional wajib diisi.'],
    'non textual name' => [['name' => ['Sopir']], 'Nama fungsi operasional harus berupa teks.'],
    'duplicate name with different case' => [['name' => '  PETUGAS lapangan '], 'Nama fungsi operasional sudah digunakan.'],
]);

test('admin can open and update an operational function', function () {
    $admin = User::factory()->admin()->create();
    $operationalFunction = OperationalFunction::factory()->deactivated()->create([
        'name' => 'Helper',
        'normalized_name' => 'helper',
    ]);

    $this->actingAs($admin)
        ->get("/operational-functions/{$operationalFunction->id}/edit")
        ->assertInertia(fn (Assert $page) => $page
            ->component('operational-functions/edit')
            ->where('operationalFunction.id', $operationalFunction->id)
            ->where('operationalFunction.is_active', false)
            ->missing('operationalFunction.normalized_name'));

    $this->actingAs($admin)
        ->patch("/operational-functions/{$operationalFunction->id}", [
            'name' => '  Teknisi   Lapangan ',
            'is_active' => true,
        ])
        ->assertRedirect('/operational-functions');

    $this->assertDatabaseHas('operational_functions', [
        'id' => $operationalFunction->id,
        'name' => 'Teknisi Lapangan',
        'normalized_name' => 'teknisi lapangan',
        'is_active' => false,
    ]);
});

test('update rejects another operational function normalized name', function () {
    $admin = User::factory()->admin()->create();
    OperationalFunction::factory()->create([
        'name' => 'Sopir',
        'normalized_name' => 'sopir',
    ]);
    $operationalFunction = OperationalFunction::factory()->create();

    $this->actingAs($admin)
        ->patch("/operational-functions/{$operationalFunction->id}", [
            'name' => ' SOPIR ',
        ])
        ->assertSessionHasErrors(['name' => 'Nama fungsi operasional sudah digunakan.']);
});

test('owner and karyawan cannot update operational functions', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();
    $operationalFunction = OperationalFunction::factory()->create();

    $this->actingAs($actor)
        ->patch("/operational-functions/{$operationalFunction->id}", [
            'name' => 'Tidak Diizinkan',
        ])
        ->assertForbidden();
})->with(['owner', 'karyawan']);

test('admin and superadmin can deactivate and reactivate operational functions', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();
    $operationalFunction = OperationalFunction::factory()->active()->create();
    $assignedKaryawan = User::factory()->karyawan()->create([
        'operational_function_id' => $operationalFunction->id,
    ]);

    $this->actingAs($actor)
        ->patch("/operational-functions/{$operationalFunction->id}/deactivate")
        ->assertRedirect('/operational-functions');

    expect($operationalFunction->refresh()->is_active)->toBeFalse()
        ->and($assignedKaryawan->refresh()->operational_function_id)
        ->toBe($operationalFunction->id);

    $this->actingAs($actor)
        ->patch("/operational-functions/{$operationalFunction->id}/reactivate")
        ->assertRedirect('/operational-functions');

    expect($operationalFunction->refresh()->is_active)->toBeTrue()
        ->and($assignedKaryawan->refresh()->operational_function_id)
        ->toBe($operationalFunction->id);
})->with(['admin', 'superadmin']);

test('owner and karyawan cannot change operational function active state', function (string $actorState, string $action) {
    $actor = User::factory()->{$actorState}()->create();
    $operationalFunction = OperationalFunction::factory()->create();

    $this->actingAs($actor)
        ->patch("/operational-functions/{$operationalFunction->id}/{$action}")
        ->assertForbidden();
})->with([
    'owner deactivate' => ['owner', 'deactivate'],
    'owner reactivate' => ['owner', 'reactivate'],
    'karyawan deactivate' => ['karyawan', 'deactivate'],
    'karyawan reactivate' => ['karyawan', 'reactivate'],
]);

test('operational function management exposes no delete route', function () {
    $admin = User::factory()->admin()->create();
    $operationalFunction = OperationalFunction::factory()->create();

    $this->actingAs($admin)
        ->delete("/operational-functions/{$operationalFunction->id}")
        ->assertMethodNotAllowed();

    $this->assertModelExists($operationalFunction);
});
