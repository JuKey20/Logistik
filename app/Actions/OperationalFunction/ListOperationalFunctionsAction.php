<?php

namespace App\Actions\OperationalFunction;

use App\Models\OperationalFunction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ListOperationalFunctionsAction
{
    /**
     * @return LengthAwarePaginator<int, OperationalFunction>
     */
    public function handle(): LengthAwarePaginator
    {
        return OperationalFunction::query()
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(15, ['id', 'name', 'is_active']);
    }
}
