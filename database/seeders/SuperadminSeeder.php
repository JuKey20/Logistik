<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use InvalidArgumentException;
use LogicException;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $credentials = config('auth.bootstrap_superadmin');

        if (! is_array($credentials) || collect(['name', 'email', 'password'])
            ->contains(fn (string $key): bool => ! is_string($credentials[$key] ?? null) || trim($credentials[$key]) === '')) {
            throw new InvalidArgumentException('Konfigurasi bootstrap superadmin wajib diisi lengkap.');
        }

        $validated = Validator::make($credentials, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', Password::min(12)->mixedCase()->letters()->numbers()->symbols()],
        ])->validate();

        $email = mb_strtolower(trim($validated['email']));

        DB::transaction(function () use ($validated, $email): void {
            $hasAnotherSuperadmin = User::query()
                ->where('role', UserRole::Superadmin->value)
                ->where('email', '!=', $email)
                ->lockForUpdate()
                ->exists();

            if ($hasAnotherSuperadmin) {
                throw new LogicException('Superadmin awal sudah tersedia dengan identitas berbeda.');
            }

            $user = User::query()->where('email', $email)->lockForUpdate()->first();

            if ($user !== null && $user->role !== UserRole::Superadmin) {
                throw new LogicException('Email bootstrap sudah digunakan oleh pengguna non-superadmin.');
            }

            $user ??= new User;
            $user->forceFill([
                'name' => trim($validated['name']),
                'email' => $email,
                'password' => $validated['password'],
                'role' => UserRole::Superadmin,
                'is_active' => true,
            ])->save();
        });
    }
}
