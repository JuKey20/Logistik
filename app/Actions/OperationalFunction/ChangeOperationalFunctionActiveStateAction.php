<?php

namespace App\Actions\OperationalFunction;

use App\Models\OperationalFunction;

final class ChangeOperationalFunctionActiveStateAction
{
    public function handle(OperationalFunction $operationalFunction, bool $isActive): OperationalFunction
    {
        $operationalFunction->forceFill(['is_active' => $isActive])->save();

        return $operationalFunction;
    }
}
