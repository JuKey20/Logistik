<?php

namespace App\Actions\UserManagement;

use App\Models\User;

final class ChangeKaryawanActiveStateAction
{
    public function handle(User $karyawan, bool $isActive): User
    {
        $karyawan->forceFill(['is_active' => $isActive])->save();

        return $karyawan;
    }
}
