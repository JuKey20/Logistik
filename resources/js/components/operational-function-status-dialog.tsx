import { Form } from '@inertiajs/react';
import { Power, PowerOff } from 'lucide-react';
import { useState } from 'react';
import OperationalFunctionStatusController from '@/actions/App/Http/Controllers/OperationalFunctionStatusController';
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
import type { OperationalFunction } from '@/types';

export function OperationalFunctionStatusDialog({
    operationalFunction,
}: {
    operationalFunction: OperationalFunction;
}) {
    const [open, setOpen] = useState(false);
    const isDeactivating = operationalFunction.is_active;
    const form = isDeactivating
        ? OperationalFunctionStatusController.deactivate.form.patch(
              operationalFunction.id,
          )
        : OperationalFunctionStatusController.reactivate.form.patch(
              operationalFunction.id,
          );

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
                        fungsi?
                    </DialogTitle>
                    <DialogDescription>
                        {isDeactivating
                            ? `${operationalFunction.name} tidak dapat dipilih untuk assignment baru. Assignment yang sudah ada tetap dipertahankan.`
                            : `${operationalFunction.name} dapat kembali dipilih untuk assignment karyawan.`}
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
                                      ? 'Nonaktifkan fungsi'
                                      : 'Aktifkan fungsi'}
                            </Button>
                        </DialogFooter>
                    )}
                </Form>
            </DialogContent>
        </Dialog>
    );
}
