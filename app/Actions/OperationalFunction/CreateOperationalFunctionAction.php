<?php

namespace App\Actions\OperationalFunction;

use App\Models\OperationalFunction;
use Illuminate\Support\Str;

final class CreateOperationalFunctionAction
{
    public function handle(string $name): OperationalFunction
    {
        $name = Str::squish($name);

        $operationalFunction = new OperationalFunction;
        $operationalFunction->fill(['name' => $name]);
        $operationalFunction->forceFill([
            'normalized_name' => OperationalFunction::normalizeName($name),
            'is_active' => true,
        ])->save();

        return $operationalFunction;
    }
}
