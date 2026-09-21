<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\OperationalFunction;
use App\Models\User;

class OperationalFunctionPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->canManageOperationalFunctions($user);
    }

    public function create(User $user): bool
    {
        return $this->canManageOperationalFunctions($user);
    }

    public function update(User $user, OperationalFunction $operationalFunction): bool
    {
        return $this->canManageOperationalFunctions($user);
    }

    public function deactivate(User $user, OperationalFunction $operationalFunction): bool
    {
        return $this->canManageOperationalFunctions($user);
    }

    public function reactivate(User $user, OperationalFunction $operationalFunction): bool
    {
        return $this->canManageOperationalFunctions($user);
    }

    public function delete(User $user, OperationalFunction $operationalFunction): bool
    {
        return false;
    }

    private function canManageOperationalFunctions(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }
}
