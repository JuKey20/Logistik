import { Form, Head, Link } from '@inertiajs/react';
import { Pencil, Plus, Search, Users } from 'lucide-react';
import KaryawanController from '@/actions/App/Http/Controllers/KaryawanController';
import InputError from '@/components/input-error';
import { KaryawanPagination } from '@/components/karyawan-pagination';
import { KaryawanStatusDialog } from '@/components/karyawan-status-dialog';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { create, edit, index } from '@/routes/karyawan';
import type { PaginatedKaryawan } from '@/types';

type Props = {
    karyawan: PaginatedKaryawan;
    filters: {
        search: string | null;
    };
};

export default function KaryawanIndex({ karyawan, filters }: Props) {
    return (
        <>
            <Head title="Karyawan" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4 md:p-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">
                            Karyawan
                        </h1>
                        <p className="text-muted-foreground mt-1 text-sm">
                            Kelola akun karyawan yang menggunakan aplikasi
                            operasional.
                        </p>
                    </div>
                    <Button asChild>
                        <Link href={create()}>
                            <Plus />
                            Tambah karyawan
                        </Link>
                    </Button>
                </div>

                <div className="bg-card overflow-hidden rounded-lg border">
                    <div className="border-b p-4">
                        <Form
                            {...KaryawanController.index.form()}
                            options={{ preserveState: true, replace: true }}
                            className="flex flex-col gap-2 sm:flex-row"
                        >
                            {({ errors, processing }) => (
                                <>
                                    <div className="max-w-md flex-1 space-y-2">
                                        <div className="relative">
                                            <Search className="text-muted-foreground pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2" />
                                            <Input
                                                name="search"
                                                type="search"
                                                defaultValue={
                                                    filters.search ?? ''
                                                }
                                                placeholder="Cari nama atau email"
                                                aria-label="Cari karyawan berdasarkan nama atau email"
                                                className="pl-9"
                                                maxLength={100}
                                            />
                                        </div>
                                        <InputError message={errors.search} />
                                    </div>
                                    <Button
                                        type="submit"
                                        variant="secondary"
                                        disabled={processing}
                                    >
                                        {processing ? 'Mencari...' : 'Cari'}
                                    </Button>
                                    {filters.search && (
                                        <Button asChild variant="ghost">
                                            <Link href={index()} preserveState>
                                                Hapus pencarian
                                            </Link>
                                        </Button>
                                    )}
                                </>
                            )}
                        </Form>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead className="bg-muted/50 border-b text-left">
                                <tr>
                                    <th
                                        scope="col"
                                        className="px-4 py-3 font-medium"
                                    >
                                        Nama
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-4 py-3 font-medium"
                                    >
                                        Email
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-4 py-3 font-medium"
                                    >
                                        Fungsi operasional
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-4 py-3 font-medium"
                                    >
                                        Status
                                    </th>
                                    <th
                                        scope="col"
                                        className="px-4 py-3 text-right font-medium"
                                    >
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y">
                                {karyawan.data.map((user) => (
                                    <tr
                                        key={user.id}
                                        className="hover:bg-muted/30"
                                    >
                                        <td className="px-4 py-3 font-medium">
                                            {user.name}
                                        </td>
                                        <td className="text-muted-foreground px-4 py-3">
                                            {user.email}
                                        </td>
                                        <td className="px-4 py-3">
                                            {user.operational_function ? (
                                                <div className="flex flex-wrap items-center gap-2">
                                                    <span>
                                                        {
                                                            user
                                                                .operational_function
                                                                .name
                                                        }
                                                    </span>
                                                    {!user.operational_function
                                                        .is_active && (
                                                        <Badge variant="outline">
                                                            Nonaktif
                                                        </Badge>
                                                    )}
                                                </div>
                                            ) : (
                                                <span className="text-muted-foreground">
                                                    Belum ditentukan
                                                </span>
                                            )}
                                        </td>
                                        <td className="px-4 py-3">
                                            <Badge
                                                variant={
                                                    user.is_active
                                                        ? 'secondary'
                                                        : 'outline'
                                                }
                                            >
                                                {user.is_active
                                                    ? 'Aktif'
                                                    : 'Nonaktif'}
                                            </Badge>
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex justify-end gap-2">
                                                <Button
                                                    asChild
                                                    variant="outline"
                                                    size="sm"
                                                >
                                                    <Link href={edit(user.id)}>
                                                        <Pencil />
                                                        Edit
                                                    </Link>
                                                </Button>
                                                <KaryawanStatusDialog
                                                    karyawan={user}
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                {karyawan.data.length === 0 && (
                                    <tr>
                                        <td
                                            colSpan={5}
                                            className="px-4 py-12 text-center"
                                        >
                                            <Users className="text-muted-foreground mx-auto size-10" />
                                            <p className="mt-3 font-medium">
                                                {filters.search
                                                    ? 'Karyawan tidak ditemukan'
                                                    : 'Belum ada karyawan'}
                                            </p>
                                            <p className="text-muted-foreground mt-1 text-sm">
                                                {filters.search
                                                    ? 'Coba gunakan nama atau email yang berbeda.'
                                                    : 'Tambahkan akun karyawan pertama untuk memulai.'}
                                            </p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    <KaryawanPagination pagination={karyawan} />
                </div>
            </div>
        </>
    );
}

KaryawanIndex.layout = {
    breadcrumbs: [
        {
            title: 'Karyawan',
            href: index(),
        },
    ],
};
