import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import KaryawanController from '@/actions/App/Http/Controllers/KaryawanController';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { create, index } from '@/routes/karyawan';

export default function CreateKaryawan({
    passwordRules,
}: {
    passwordRules: string;
}) {
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
                                    <Button type="submit" disabled={processing}>
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
