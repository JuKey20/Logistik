<?php

namespace App\Http\Controllers;

use App\Actions\OperationalFunction\ChangeOperationalFunctionActiveStateAction;
use App\Models\OperationalFunction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class OperationalFunctionStatusController extends Controller
{
    public function deactivate(
        OperationalFunction $operationalFunction,
        ChangeOperationalFunctionActiveStateAction $changeActiveState,
    ): RedirectResponse {
        Gate::authorize('deactivate', $operationalFunction);

        $changeActiveState->handle($operationalFunction, false);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Fungsi operasional berhasil dinonaktifkan.']);

        return to_route('operational-functions.index');
    }

    public function reactivate(
        OperationalFunction $operationalFunction,
        ChangeOperationalFunctionActiveStateAction $changeActiveState,
    ): RedirectResponse {
        Gate::authorize('reactivate', $operationalFunction);

        $changeActiveState->handle($operationalFunction, true);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Fungsi operasional berhasil diaktifkan kembali.']);

        return to_route('operational-functions.index');
    }
}
