import { Form, Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import OperationalFunctionController from '@/actions/App/Http/Controllers/OperationalFunctionController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { create, index } from '@/routes/operational-functions';

export default function CreateOperationalFunction() {
    return (
        <>
            <Head title="Tambah fungsi operasional" />

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
                        Tambah fungsi operasional
                    </h1>
                    <p className="text-muted-foreground mt-1 text-sm">
                        Fungsi baru langsung aktif dan dapat dipilih untuk
                        assignment karyawan.
                    </p>
                </div>

                <div className="bg-card max-w-2xl rounded-lg border p-4 sm:p-6">
                    <Form
                        {...OperationalFunctionController.store.form()}
                        resetOnSuccess
                        className="space-y-5"
                    >
                        {({ errors, processing }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Nama fungsi</Label>
                                    <Input
                                        id="name"
                                        name="name"
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
                                            : 'Simpan fungsi'}
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

CreateOperationalFunction.layout = {
    breadcrumbs: [
        { title: 'Fungsi operasional', href: index() },
        { title: 'Tambah', href: create() },
    ],
};
