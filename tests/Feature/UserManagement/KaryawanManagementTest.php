<?php

use App\Enums\UserRole;
use App\Models\OperationalFunction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;

test('guest is redirected from karyawan management to login', function () {
    $this->get('/karyawan')->assertRedirect(route('login'));
});

test('admin and superadmin can view the karyawan index', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();

    $this->actingAs($actor)
        ->get('/karyawan')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('karyawan/index'));
})->with(['admin', 'superadmin']);

test('owner and karyawan are forbidden from the karyawan index', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();

    $this->actingAs($actor)->get('/karyawan')->assertForbidden();
})->with(['owner', 'karyawan']);

test('index contains only karyawan and exposes no authentication secrets', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->karyawan()->create(['name' => 'Budi Karyawan', 'email' => 'budi@example.com']);
    User::factory()->karyawan()->deactivated()->create(['name' => 'Citra Karyawan', 'email' => 'citra@example.com']);
    User::factory()->owner()->create();
    User::factory()->superadmin()->create();

    $this->actingAs($admin)
        ->get('/karyawan')
        ->assertInertia(fn (Assert $page) => $page
            ->component('karyawan/index')
            ->has('karyawan.data', 2)
            ->has('karyawan.data.0', fn (Assert $user) => $user
                ->hasAll(['id', 'name', 'email', 'is_active', 'operational_function_id', 'operational_function'])
                ->where('operational_function_id', null)
                ->where('operational_function', null)
                ->missingAll(['password', 'remember_token', 'two_factor_secret'])));
});

test('index searches karyawan by name and email', function (string $search) {
    $admin = User::factory()->admin()->create();
    User::factory()->karyawan()->create(['name' => 'Dewi Anggraini', 'email' => 'dewi@logistik.test']);
    User::factory()->karyawan()->create(['name' => 'Eko Saputra', 'email' => 'eko@logistik.test']);

    $this->actingAs($admin)
        ->get('/karyawan?search='.urlencode($search))
        ->assertInertia(fn (Assert $page) => $page
            ->has('karyawan.data', 1)
            ->where('karyawan.data.0.email', 'dewi@logistik.test')
            ->where('filters.search', $search));
})->with(['Dewi', 'dewi@logistik.test']);

test('index paginates and preserves the search query', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->karyawan()->count(16)->sequence(
        fn (Sequence $sequence): array => [
            'name' => sprintf('Pegawai %02d', $sequence->index),
            'email' => sprintf('pegawai%02d@example.com', $sequence->index),
        ],
    )->create();

    $this->actingAs($admin)
        ->get('/karyawan?search=Pegawai')
        ->assertInertia(fn (Assert $page) => $page
            ->has('karyawan.data', 15)
            ->where('karyawan.current_page', 1)
            ->where('karyawan.last_page', 2)
            ->where('karyawan.next_page_url', fn (?string $url): bool => $url !== null && str_contains($url, 'search=Pegawai')));
});

test('index rejects a non textual search value', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/karyawan?search[]=invalid')
        ->assertSessionHasErrors(['search' => 'Pencarian harus berupa teks.']);
});

test('admin can open the create karyawan page', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/karyawan/create')
        ->assertInertia(fn (Assert $page) => $page
            ->component('karyawan/create')
            ->has('passwordRules'));
});

test('owner and karyawan cannot open the create karyawan page', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();

    $this->actingAs($actor)->get('/karyawan/create')->assertForbidden();
})->with(['owner', 'karyawan']);

test('admin and superadmin can create only active karyawan', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();
    $operationalFunction = OperationalFunction::factory()->active()->create();

    $this->actingAs($actor)
        ->post('/karyawan', [
            'name' => 'Karyawan Baru',
            'email' => 'baru@example.com',
            'password' => 'Initial-Password-2026!',
            'password_confirmation' => 'Initial-Password-2026!',
            'operational_function_id' => $operationalFunction->id,
            'role' => UserRole::Superadmin->value,
            'is_active' => false,
        ])
        ->assertRedirect('/karyawan');

    $created = User::query()->where('email', 'baru@example.com')->firstOrFail();

    expect($created)
        ->role->toBe(UserRole::Karyawan)
        ->is_active->toBeTrue()
        ->and(Hash::check('Initial-Password-2026!', $created->password))->toBeTrue();
})->with(['admin', 'superadmin']);

test('owner and karyawan cannot create accounts', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();

    $this->actingAs($actor)
        ->post('/karyawan', [
            'name' => 'Tidak Diizinkan',
            'email' => 'forbidden@example.com',
            'password' => 'Initial-Password-2026!',
            'password_confirmation' => 'Initial-Password-2026!',
        ])
        ->assertForbidden();

    $this->assertDatabaseMissing('users', ['email' => 'forbidden@example.com']);
})->with(['owner', 'karyawan']);

test('create requires name email and password', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post('/karyawan', [])
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

test('create rejects an invalid email', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post('/karyawan', [
            'name' => 'Email Tidak Valid',
            'email' => 'bukan-email',
            'password' => 'Initial-Password-2026!',
            'password_confirmation' => 'Initial-Password-2026!',
        ])
        ->assertSessionHasErrors(['email' => 'Format email tidak valid.']);
});

test('create rejects a non textual email value', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post('/karyawan', [
            'name' => 'Email Array',
            'email' => ['invalid@example.com'],
            'password' => 'Initial-Password-2026!',
            'password_confirmation' => 'Initial-Password-2026!',
        ])
        ->assertSessionHasErrors(['email' => 'Email harus berupa teks.']);
});

test('create rejects an email already used by any role', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->owner()->create(['email' => 'used@example.com']);

    $this->actingAs($admin)
        ->post('/karyawan', [
            'name' => 'Email Duplikat',
            'email' => 'used@example.com',
            'password' => 'Initial-Password-2026!',
            'password_confirmation' => 'Initial-Password-2026!',
        ])
        ->assertSessionHasErrors(['email' => 'Email sudah digunakan.']);
});

test('create applies the application password requirements', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post('/karyawan', [
            'name' => 'Password Lemah',
            'email' => 'lemah@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])
        ->assertSessionHasErrors(['password' => 'Kata sandi minimal 8 karakter.']);
});

test('admin can edit a karyawan without changing protected fields', function () {
    $admin = User::factory()->admin()->create();
    $operationalFunction = OperationalFunction::factory()->active()->create();
    $karyawan = User::factory()->karyawan()->create([
        'email' => 'lama@example.com',
        'password' => 'Original-Password-2026!',
        'operational_function_id' => $operationalFunction->id,
    ]);

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => 'Nama Diperbarui',
            'email' => 'baru@example.com',
            'operational_function_id' => $operationalFunction->id,
            'password' => 'Changed-Password-2026!',
            'role' => UserRole::Superadmin->value,
            'is_active' => false,
        ])
        ->assertRedirect('/karyawan');

    $karyawan->refresh();

    expect($karyawan)
        ->name->toBe('Nama Diperbarui')
        ->email->toBe('baru@example.com')
        ->role->toBe(UserRole::Karyawan)
        ->is_active->toBeTrue()
        ->and(Hash::check('Original-Password-2026!', $karyawan->password))->toBeTrue()
        ->and(Hash::check('Changed-Password-2026!', $karyawan->password))->toBeFalse();
});

test('admin can open the edit page for a karyawan', function () {
    $admin = User::factory()->admin()->create();
    $karyawan = User::factory()->karyawan()->create();

    $this->actingAs($admin)
        ->get("/karyawan/{$karyawan->id}/edit")
        ->assertInertia(fn (Assert $page) => $page
            ->component('karyawan/edit')
            ->where('karyawan.id', $karyawan->id)
            ->where('karyawan.email', $karyawan->email)
            ->missing('karyawan.password'));
});

test('owner and karyawan cannot open a karyawan edit page', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();
    $karyawan = User::factory()->karyawan()->create();

    $this->actingAs($actor)
        ->get("/karyawan/{$karyawan->id}/edit")
        ->assertForbidden();
})->with(['owner', 'karyawan']);

test('superadmin can edit a karyawan through the ordinary flow', function () {
    $superadmin = User::factory()->superadmin()->create();
    $operationalFunction = OperationalFunction::factory()->active()->create();
    $karyawan = User::factory()->karyawan()->for($operationalFunction)->create();

    $this->actingAs($superadmin)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => 'Diperbarui Superadmin',
            'email' => $karyawan->email,
            'operational_function_id' => $operationalFunction->id,
        ])
        ->assertRedirect('/karyawan');

    expect($karyawan->refresh()->name)->toBe('Diperbarui Superadmin');
});

test('owner and karyawan cannot edit a karyawan', function (string $actorState) {
    $actor = User::factory()->{$actorState}()->create();
    $karyawan = User::factory()->karyawan()->create(['name' => 'Nama Awal']);

    $this->actingAs($actor)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => 'Tidak Diizinkan',
            'email' => $karyawan->email,
        ])
        ->assertForbidden();

    expect($karyawan->refresh()->name)->toBe('Nama Awal');
})->with(['owner', 'karyawan']);

test('ordinary edit route does not resolve non-karyawan targets', function (string $targetState) {
    $admin = User::factory()->admin()->create();
    $target = User::factory()->{$targetState}()->create();

    $this->actingAs($admin)
        ->patch("/karyawan/{$target->id}", [
            'name' => 'Tidak Boleh Diubah',
            'email' => $target->email,
        ])
        ->assertNotFound();
})->with(['superadmin', 'owner', 'admin']);

test('update validates email uniqueness while ignoring the target karyawan', function () {
    $admin = User::factory()->admin()->create();
    $operationalFunction = OperationalFunction::factory()->active()->create();
    $karyawan = User::factory()->karyawan()->for($operationalFunction)->create(['email' => 'sendiri@example.com']);
    User::factory()->karyawan()->create(['email' => 'lain@example.com']);

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => $karyawan->name,
            'email' => 'lain@example.com',
            'operational_function_id' => $operationalFunction->id,
        ])
        ->assertSessionHasErrors(['email' => 'Email sudah digunakan.']);

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}", [
            'name' => $karyawan->name,
            'email' => 'sendiri@example.com',
            'operational_function_id' => $operationalFunction->id,
        ])
        ->assertSessionHasNoErrors();
});

test('admin can deactivate and reactivate a karyawan', function () {
    $admin = User::factory()->admin()->create();
    $karyawan = User::factory()->karyawan()->active()->create([
        'password' => 'Karyawan-Password-2026!',
    ]);

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}/deactivate")
        ->assertRedirect('/karyawan');

    expect($karyawan->refresh()->is_active)->toBeFalse();

    $this->actingAs($admin)
        ->patch("/karyawan/{$karyawan->id}/reactivate")
        ->assertRedirect('/karyawan');

    expect($karyawan->refresh()->is_active)->toBeTrue();

    $this->post('/logout')->assertRedirect('/');

    $this->post('/login', [
        'email' => $karyawan->email,
        'password' => 'Karyawan-Password-2026!',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($karyawan);
});

test('superadmin can change a karyawan active state through the ordinary flow', function () {
    $superadmin = User::factory()->superadmin()->create();
    $karyawan = User::factory()->karyawan()->active()->create();

    $this->actingAs($superadmin)
        ->patch("/karyawan/{$karyawan->id}/deactivate")
        ->assertRedirect('/karyawan');

    expect($karyawan->refresh()->is_active)->toBeFalse();
});

test('owner and karyawan cannot change a karyawan active state', function (string $actorState, string $action) {
    $actor = User::factory()->{$actorState}()->create();
    $karyawan = User::factory()->karyawan()->create();

    $this->actingAs($actor)
        ->patch("/karyawan/{$karyawan->id}/{$action}")
        ->assertForbidden();
})->with([
    'owner deactivate' => ['owner', 'deactivate'],
    'owner reactivate' => ['owner', 'reactivate'],
    'karyawan deactivate' => ['karyawan', 'deactivate'],
    'karyawan reactivate' => ['karyawan', 'reactivate'],
]);

test('status routes do not resolve non-karyawan targets', function (string $targetState, string $action) {
    $admin = User::factory()->admin()->create();
    $target = User::factory()->{$targetState}()->create();

    $this->actingAs($admin)
        ->patch("/karyawan/{$target->id}/{$action}")
        ->assertNotFound();
})->with([
    'deactivate superadmin' => ['superadmin', 'deactivate'],
    'deactivate owner' => ['owner', 'deactivate'],
    'deactivate admin' => ['admin', 'deactivate'],
    'reactivate superadmin' => ['superadmin', 'reactivate'],
    'reactivate owner' => ['owner', 'reactivate'],
    'reactivate admin' => ['admin', 'reactivate'],
]);

test('karyawan management exposes no delete route', function () {
    $admin = User::factory()->admin()->create();
    $karyawan = User::factory()->karyawan()->create();

    $this->actingAs($admin)
        ->delete("/karyawan/{$karyawan->id}")
        ->assertMethodNotAllowed();

    $this->assertModelExists($karyawan);
});
