import { Form } from '@inertiajs/react';
import { Power, PowerOff } from 'lucide-react';
import { useState } from 'react';
import KaryawanStatusController from '@/actions/App/Http/Controllers/KaryawanStatusController';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import type { Karyawan } from '@/types';

export function KaryawanStatusDialog({ karyawan }: { karyawan: Karyawan }) {
    const [open, setOpen] = useState(false);
    const isDeactivating = karyawan.is_active;
    const form = isDeactivating
        ? KaryawanStatusController.deactivate.form.patch(karyawan.id)
        : KaryawanStatusController.reactivate.form.patch(karyawan.id);

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button variant="outline" size="sm">
                    {isDeactivating ? <PowerOff /> : <Power />}
                    {isDeactivating ? 'Nonaktifkan' : 'Aktifkan'}
                </Button>
            </DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>
                        {isDeactivating ? 'Nonaktifkan' : 'Aktifkan kembali'}{' '}
                        akun?
                    </DialogTitle>
                    <DialogDescription>
                        {isDeactivating
                            ? `${karyawan.name} tidak dapat masuk atau menggunakan aplikasi sampai akunnya diaktifkan kembali.`
                            : `${karyawan.name} dapat kembali masuk menggunakan kata sandi yang masih berlaku.`}
                    </DialogDescription>
                </DialogHeader>

                <Form {...form} onSuccess={() => setOpen(false)}>
                    {({ processing }) => (
                        <DialogFooter>
                            <DialogClose asChild>
                                <Button
                                    type="button"
                                    variant="outline"
                                    disabled={processing}
                                >
                                    Batal
                                </Button>
                            </DialogClose>
                            <Button
                                type="submit"
                                variant={
                                    isDeactivating ? 'destructive' : 'default'
                                }
                                disabled={processing}
                            >
                                {processing
                                    ? 'Memproses...'
                                    : isDeactivating
                                      ? 'Nonaktifkan akun'
                                      : 'Aktifkan akun'}
                            </Button>
                        </DialogFooter>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
