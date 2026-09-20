import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import KaryawanController from '@/actions/App/Http/Controllers/KaryawanController';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/karyawan';
import type { Karyawan } from '@/types';

export default function EditKaryawan({ karyawan }: { karyawan: Karyawan }) {
    return (
        <>
            <Head title={`Edit ${karyawan.name}`} />

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
                    <div className="flex flex-wrap items-center gap-3">
                        <h1 className="text-2xl font-semibold tracking-tight">
                            Edit karyawan
                        </h1>
                        <Badge
                            variant={
                                karyawan.is_active ? 'secondary' : 'outline'
                            }
                        >
                            {karyawan.is_active ? 'Aktif' : 'Nonaktif'}
                        </Badge>
                    </div>
                    <p className="text-muted-foreground mt-1 text-sm">
                        Perbarui nama dan email. Kata sandi serta role tidak
                        berubah di halaman ini.
                    </p>
                </div>

                <div className="bg-card max-w-2xl rounded-lg border p-4 sm:p-6">
                    <Form
                        {...KaryawanController.update.form.patch(karyawan.id)}
                        options={{ preserveScroll: true }}
                        className="space-y-5"
                    >
                        {({ errors, processing }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Nama lengkap</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        defaultValue={karyawan.name}
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
                                        defaultValue={karyawan.email}
                                        required
                                        autoComplete="username"
                                    />
                                    <InputError message={errors.email} />
                                </div>

                                <div className="flex flex-col-reverse gap-2 border-t pt-5 sm:flex-row sm:justify-end">
                                    <Button asChild variant="outline">
                                        <Link href={index()}>Batal</Link>
                                    </Button>
                                    <Button type="submit" disabled={processing}>
                                        {processing
                                            ? 'Menyimpan...'
                                            : 'Simpan perubahan'}
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

EditKaryawan.layout = {
    breadcrumbs: [
        { title: 'Karyawan', href: index() },
        { title: 'Edit', href: index() },
    ],
};
