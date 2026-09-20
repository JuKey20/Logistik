<?php

namespace App\Actions\UserManagement;

use App\Enums\UserRole;
use App\Models\User;

final class CreateKaryawanAction
{
    /**
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function handle(array $data): User
    {
        $karyawan = new User;
        $karyawan->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $karyawan->forceFill([
            'role' => UserRole::Karyawan,
            'is_active' => true,
        ]);
        $karyawan->save();

        return $karyawan;
    }
}
