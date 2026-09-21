import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import type { PaginatedOperationalFunctions } from '@/types';

function localizeLabel(label: string): string {
    if (label.includes('Previous')) {
        return 'Sebelumnya';
    }

    if (label.includes('Next')) {
        return 'Berikutnya';
    }

    return label;
}

export function OperationalFunctionPagination({
    pagination,
}: {
    pagination: PaginatedOperationalFunctions;
}) {
    if (pagination.last_page <= 1) {
        return null;
    }

    return (
        <div className="flex flex-col gap-3 border-t px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p className="text-muted-foreground text-sm">
                Menampilkan {pagination.from ?? 0}–{pagination.to ?? 0} dari{' '}
                {pagination.total} fungsi
            </p>

            <nav
                aria-label="Halaman fungsi operasional"
                className="flex flex-wrap gap-1"
            >
                {pagination.links.map((link, index) => {
                    const label = localizeLabel(link.label);

                    if (link.url === null) {
                        return (
                            <Button
                                key={`${label}-${index}`}
                                variant="outline"
                                size="sm"
                                disabled
                            >
                                {label}
                            </Button>
                        );
                    }

                    return (
                        <Button
                            key={`${label}-${index}`}
                            asChild
                            variant={link.active ? 'default' : 'outline'}
                            size="sm"
                        >
                            <Link
                                href={link.url}
                                preserveScroll
                                aria-current={link.active ? 'page' : undefined}
                            >
                                {label}
                            </Link>
                        </Button>
                    );
                })}
            </nav>
        </div>
    );
}
