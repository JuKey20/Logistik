import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import OperationalFunctionController from '@/actions/App/Http/Controllers/OperationalFunctionController';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/operational-functions';
import type { OperationalFunction } from '@/types';

export default function EditOperationalFunction({
    operationalFunction,
}: {
    operationalFunction: OperationalFunction;
}) {
    return (
        <>
            <Head title={`Edit ${operationalFunction.name}`} />

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
                            Edit fungsi operasional
                        </h1>
                        <Badge
                            variant={
                                operationalFunction.is_active
                                    ? 'secondary'
                                    : 'outline'
                            }
                        >
                            {operationalFunction.is_active
                                ? 'Aktif'
                                : 'Nonaktif'}
                        </Badge>
                    </div>
                    <p className="text-muted-foreground mt-1 text-sm">
                        Mengubah nama tidak mengubah role atau hak akses
                        pengguna.
                    </p>
                </div>

                <div className="bg-card max-w-2xl rounded-lg border p-4 sm:p-6">
                    <Form
                        {...OperationalFunctionController.update.form.patch(
                            operationalFunction.id,
                        )}
                        options={{ preserveScroll: true }}
                        className="space-y-5"
                    >
                        {({ errors, processing }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Nama fungsi</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        defaultValue={operationalFunction.name}
                                        required
                                        maxLength={100}
                                        autoFocus
                                    />
                                    <InputError message={errors.name} />
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

EditOperationalFunction.layout = {
    breadcrumbs: [
        { title: 'Fungsi operasional', href: index() },
        { title: 'Edit', href: index() },
    ],
};
