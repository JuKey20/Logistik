<?php

namespace App\Http\Controllers;

use App\Actions\UserManagement\ChangeKaryawanActiveStateAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class KaryawanStatusController extends Controller
{
    public function deactivate(
        User $karyawan,
        ChangeKaryawanActiveStateAction $changeActiveState,
    ): RedirectResponse {
        Gate::authorize('deactivate', $karyawan);

        $changeActiveState->handle($karyawan, false);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Akun karyawan berhasil dinonaktifkan.']);

        return to_route('karyawan.index');
    }

    public function reactivate(
        User $karyawan,
        ChangeKaryawanActiveStateAction $changeActiveState,
    ): RedirectResponse {
        Gate::authorize('reactivate', $karyawan);

        $changeActiveState->handle($karyawan, true);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Akun karyawan berhasil diaktifkan kembali.']);

        return to_route('karyawan.index');
    }
}
