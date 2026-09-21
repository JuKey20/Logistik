import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import KaryawanController from '@/actions/App/Http/Controllers/KaryawanController';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { create, index } from '@/routes/karyawan';
import { index as operationalFunctionsIndex } from '@/routes/operational-functions';
import type { OperationalFunction } from '@/types';

export default function CreateKaryawan({
    passwordRules,
    operationalFunctions,
}: {
    passwordRules: string;
    operationalFunctions: OperationalFunction[];
}) {
    const hasOperationalFunctions = operationalFunctions.length > 0;

    return (
        <>
            <Head title="Tambah karyawan" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4 md:p-6">
                <div>
                    <Button
                        asChild
                        variant="ghost"
                        size="sm"
                        className="mb-3 -ml-3"
                    >
                        <Link href={index()}>
                            <ArrowLeft />
                            Kembali ke daftar
                        </Link>
                    </Button>
                    <h1 className="text-2xl font-semibold tracking-tight">
                        Tambah karyawan
                    </h1>
                    <p className="text-muted-foreground mt-1 text-sm">
                        Buat akun aktif baru dengan akses operasional karyawan.
                    </p>
                </div>

                <div className="bg-card max-w-2xl rounded-lg border p-4 sm:p-6">
                    <Form
                        {...KaryawanController.store.form()}
                        resetOnSuccess
                        className="space-y-5"
                    >
                        {({ errors, processing }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Nama lengkap</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        required
                                        autoComplete="name"
                                        autoFocus
                                    />
                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="email">Email</Label>
                                    <Input
                                        id="email"
                                        name="email"
                                        type="email"
                                        required
                                        autoComplete="username"
                                    />
                                    <InputError message={errors.email} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="operational_function_id">
                                        Fungsi operasional
                                    </Label>
                                    <Select
                                        name="operational_function_id"
                                        required
                                        disabled={!hasOperationalFunctions}
                                    >
                                        <SelectTrigger
                                            id="operational_function_id"
                                            className="w-full"
                                            aria-invalid={
                                                errors.operational_function_id
                                                    ? true
                                                    : undefined
                                            }
                                        >
                                            <SelectValue placeholder="Pilih fungsi operasional" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            {operationalFunctions.map(
                                                (operationalFunction) => (
                                                    <SelectItem
                                                        key={
                                                            operationalFunction.id
                                                        }
                                                        value={String(
                                                            operationalFunction.id,
                                                        )}
                                                    >
                                                        {
                                                            operationalFunction.name
                                                        }
                                                    </SelectItem>
                                                ),
                                            )}
                                        </SelectContent>
                                    </Select>
                                    <InputError
                                        message={errors.operational_function_id}
                                    />
                                    {!hasOperationalFunctions && (
                                        <p className="text-muted-foreground text-sm">
                                            Belum ada fungsi operasional aktif.{' '}
                                            <Link
                                                href={operationalFunctionsIndex()}
                                                className="text-foreground underline underline-offset-4"
                                            >
                                                Kelola fungsi operasional
                                            </Link>{' '}
                                            terlebih dahulu.
                                        </p>
                                    )}
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="password">
                                        Kata sandi awal
                                    </Label>
                                    <PasswordInput
                                        id="password"
                                        name="password"
                                        required
                                        autoComplete="new-password"
                                        passwordrules={passwordRules}
                                    />
                                    <InputError message={errors.password} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="password_confirmation">
                                        Konfirmasi kata sandi
                                    </Label>
                                    <PasswordInput
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                        autoComplete="new-password"
                                        passwordrules={passwordRules}
                                    />
                                    <InputError
                                        message={errors.password_confirmation}
                                    />
                                </div>

                                <div className="flex flex-col-reverse gap-2 border-t pt-5 sm:flex-row sm:justify-end">
                                    <Button asChild variant="outline">
                                        <Link href={index()}>Batal</Link>
                                    </Button>
                                    <Button
                                        type="submit"
                                        disabled={
                                            processing ||
                                            !hasOperationalFunctions
                                        }
                                    >
                                        {processing
                                            ? 'Menyimpan...'
                                            : 'Simpan karyawan'}
                                    </Button>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </>
    );
}

CreateKaryawan.layout = {
    breadcrumbs: [
        { title: 'Karyawan', href: index() },
        { title: 'Tambah', href: create() },
    ],
};
