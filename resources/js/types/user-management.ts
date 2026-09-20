export type Karyawan = {
    id: number;
    name: string;
    email: string;
    is_active: boolean;
};

export type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

export type PaginatedKaryawan = {
    data: Karyawan[];
    current_page: number;
    from: number | null;
    last_page: number;
    links: PaginationLink[];
    next_page_url: string | null;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
};
