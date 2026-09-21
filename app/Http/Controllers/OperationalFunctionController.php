<?php

namespace App\Http\Controllers;

use App\Actions\OperationalFunction\CreateOperationalFunctionAction;
use App\Actions\OperationalFunction\ListOperationalFunctionsAction;
use App\Actions\OperationalFunction\UpdateOperationalFunctionAction;
use App\Http\Requests\OperationalFunction\StoreOperationalFunctionRequest;
use App\Http\Requests\OperationalFunction\UpdateOperationalFunctionRequest;
use App\Models\OperationalFunction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class OperationalFunctionController extends Controller
{
    public function index(ListOperationalFunctionsAction $listOperationalFunctions): Response
    {
        Gate::authorize('viewAny', OperationalFunction::class);

        return Inertia::render('operational-functions/index', [
            'operationalFunctions' => $listOperationalFunctions->handle(),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', OperationalFunction::class);

        return Inertia::render('operational-functions/create');
    }

    public function store(
        StoreOperationalFunctionRequest $request,
        CreateOperationalFunctionAction $createOperationalFunction,
    ): RedirectResponse {
        $createOperationalFunction->handle($request->string('name')->toString());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Fungsi operasional berhasil dibuat.']);

        return to_route('operational-functions.index');
    }

    public function edit(OperationalFunction $operationalFunction): Response
    {
        Gate::authorize('update', $operationalFunction);

        return Inertia::render('operational-functions/edit', [
            'operationalFunction' => $operationalFunction->only(['id', 'name', 'is_active']),
        ]);
    }

    public function update(
        UpdateOperationalFunctionRequest $request,
        OperationalFunction $operationalFunction,
        UpdateOperationalFunctionAction $updateOperationalFunction,
    ): RedirectResponse {
        $updateOperationalFunction->handle(
            $operationalFunction,
            $request->string('name')->toString(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Fungsi operasional berhasil diperbarui.']);

        return to_route('operational-functions.index');
    }
}
