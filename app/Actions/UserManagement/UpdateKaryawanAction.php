<?php

namespace App\Actions\UserManagement;

use App\Models\User;

final class UpdateKaryawanAction
{
    /**
     * @param  array{name: string, email: string, operational_function_id: int}  $data
     */
    public function handle(User $karyawan, array $data): User
    {
        $karyawan->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        $karyawan->forceFill([
            'operational_function_id' => $data['operational_function_id'],
        ])->save();

        return $karyawan;
    }
}
