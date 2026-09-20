<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, User $model): bool
    {
        return $this->canManageKaryawan($user, $model);
    }

    public function deactivate(User $user, User $model): bool
    {
        return $this->canManageKaryawan($user, $model);
    }

    public function reactivate(User $user, User $model): bool
    {
        return $this->canManageKaryawan($user, $model);
    }

    public function delete(User $user, User $model): bool
    {
        return false;
    }

    private function canManageKaryawan(User $user, User $model): bool
    {
        return $user->role === UserRole::Admin
            && $model->role === UserRole::Karyawan;
    }
}
