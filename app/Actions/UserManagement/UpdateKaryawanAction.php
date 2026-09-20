<?php

namespace App\Actions\UserManagement;

use App\Models\User;

final class UpdateKaryawanAction
{
    /**
     * @param  array{name: string, email: string}  $data
     */
    public function handle(User $karyawan, array $data): User
    {
        $karyawan->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ])->save();

        return $karyawan;
    }
}
