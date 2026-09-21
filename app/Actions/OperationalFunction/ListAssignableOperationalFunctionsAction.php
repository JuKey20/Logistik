<?php

namespace App\Actions\OperationalFunction;

use App\Models\OperationalFunction;
use Illuminate\Database\Eloquent\Collection;

final class ListAssignableOperationalFunctionsAction
{
    /**
     * @return Collection<int, OperationalFunction>
     */
    public function handle(?int $currentOperationalFunctionId = null): Collection
    {
        return OperationalFunction::query()
            ->where(function ($query) use ($currentOperationalFunctionId): void {
                $query->where('is_active', true)
                    ->when(
                        $currentOperationalFunctionId !== null,
                        fn ($query) => $query->orWhereKey($currentOperationalFunctionId),
                    );
            })
            ->orderBy('name')
            ->orderBy('id')
            ->get(['id', 'name', 'is_active']);
    }
}
