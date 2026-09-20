<?php

namespace App\Actions\UserManagement;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ListKaryawanAction
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function handle(?string $search): LengthAwarePaginator
    {
        return User::query()
            ->matchingKaryawan($search)
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(15, ['id', 'name', 'email', 'is_active'])
            ->withQueryString();
    }
}
