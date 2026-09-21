import { Head, Link } from '@inertiajs/react';
import { Pencil, Plus, Wrench } from 'lucide-react';
import { OperationalFunctionPagination } from '@/components/operational-function-pagination';
import { OperationalFunctionStatusDialog } from '@/components/operational-function-status-dialog';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { create, edit, index } from '@/routes/operational-functions';
import type { PaginatedOperationalFunctions } from '@/types';

export default function OperationalFunctionIndex({
    operationalFunctions,
}: {
    operationalFunctions: PaginatedOperationalFunctions;
}) {
    return (
        <>
            <Head title="Fungsi operasional" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4 md:p-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">
                            Fungsi operasional
                        </h1>
                        <p className="text-muted-foreground mt-1 text-sm">
                            Kelola klasifikasi kerja karyawan tanpa mengubah hak
                            akses aplikasi.
                        </p>
                    </div>
                    <Button asChild>
                        <Link href={create()}>
                            <Plus />
                            Tambah fungsi
                        </Link>
                    </Button>
                </div>

                <div className="bg-card overflow-hidden rounded-lg border">
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
                                {operationalFunctions.data.map((item) => (
                                    <tr
                                        key={item.id}
                                        className="hover:bg-muted/30"
                                    >
                                        <td className="px-4 py-3 font-medium">
                                            {item.name}
                                        </td>
                                        <td className="px-4 py-3">
                                            <Badge
                                                variant={
                                                    item.is_active
                                                        ? 'secondary'
                                                        : 'outline'
                                                }
                                            >
                                                {item.is_active
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
                                                    <Link href={edit(item.id)}>
                                                        <Pencil />
                                                        Edit
                                                    </Link>
                                                </Button>
                                                <OperationalFunctionStatusDialog
                                                    operationalFunction={item}
                                                />
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                                {operationalFunctions.data.length === 0 && (
                                    <tr>
                                        <td
                                            colSpan={3}
                                            className="px-4 py-12 text-center"
                                        >
                                            <Wrench className="text-muted-foreground mx-auto size-10" />
                                            <p className="mt-3 font-medium">
                                                Belum ada fungsi operasional
                                            </p>
                                            <p className="text-muted-foreground mt-1 text-sm">
                                                Tambahkan fungsi pertama agar
                                                dapat dipilih saat mengelola
                                                karyawan.
                                            </p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>

                    <OperationalFunctionPagination
                        pagination={operationalFunctions}
                    />
                </div>
            </div>
        </>
    );
}

OperationalFunctionIndex.layout = {
    breadcrumbs: [{ title: 'Fungsi operasional', href: index() }],
};
