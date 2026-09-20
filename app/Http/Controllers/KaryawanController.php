<?php

namespace App\Http\Controllers;

use App\Actions\UserManagement\CreateKaryawanAction;
use App\Actions\UserManagement\ListKaryawanAction;
use App\Actions\UserManagement\UpdateKaryawanAction;
use App\Http\Requests\UserManagement\IndexKaryawanRequest;
use App\Http\Requests\UserManagement\StoreKaryawanRequest;
use App\Http\Requests\UserManagement\UpdateKaryawanRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class KaryawanController extends Controller
{
    public function index(IndexKaryawanRequest $request, ListKaryawanAction $listKaryawan): Response
    {
        $search = $request->filled('search')
            ? $request->string('search')->toString()
            : null;

        return Inertia::render('karyawan/index', [
            'karyawan' => $listKaryawan->handle($search),
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('karyawan/create', [
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
        ]);
    }

    public function store(StoreKaryawanRequest $request, CreateKaryawanAction $createKaryawan): RedirectResponse
    {
        $createKaryawan->handle([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Karyawan berhasil dibuat.']);

        return to_route('karyawan.index');
    }

    public function edit(User $karyawan): Response
    {
        Gate::authorize('update', $karyawan);

        return Inertia::render('karyawan/edit', [
            'karyawan' => $karyawan->only(['id', 'name', 'email', 'is_active']),
        ]);
    }

    public function update(
        UpdateKaryawanRequest $request,
        User $karyawan,
        UpdateKaryawanAction $updateKaryawan,
    ): RedirectResponse {
        $updateKaryawan->handle($karyawan, [
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Data karyawan berhasil diperbarui.']);

        return to_route('karyawan.index');
    }
}
