<?php

namespace App\Actions\OperationalFunction;

use App\Models\OperationalFunction;
use Illuminate\Support\Str;

final class UpdateOperationalFunctionAction
{
    public function handle(OperationalFunction $operationalFunction, string $name): OperationalFunction
    {
        $name = Str::squish($name);

        $operationalFunction->fill(['name' => $name]);
        $operationalFunction->forceFill([
            'normalized_name' => OperationalFunction::normalizeName($name),
        ])->save();

        return $operationalFunction;
    }
}
