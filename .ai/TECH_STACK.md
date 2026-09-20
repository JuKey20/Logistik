# Technology Stack

> Two layers: portable engineering standard, and project-specific technology.

Fill **Project Technology** during adaptation. Do not treat Recommended items as Required.

---

# Engineering Standard

These are constraints and quality bars. They apply regardless of framework, unless a project decision supersedes them.

| Standard | Meaning |
| --- | --- |
| Security first | Authenticate, authorize server-side, validate input, never store secrets in source |
| Explicit boundaries | Controllers stay thin; business rules do not live in persistence or UI |
| Avoid over-engineering | Add a layer only when it earns its complexity |
| Strong typing | Prefer typed contracts over implicit arrays and `any` |
| Testability | Behavior that matters has tests |
| Living documentation | Docs stay aligned with code |
| Append-only production schema | See [RULES.md](./RULES.md) Database Policy |
| Stability over trend | New dependencies need a written reason |

Recommended architecture patterns (project default is **DEC-029**, not the portable list below):

* Action for product domain operations — [`patterns/action.md`](./patterns/action.md) (**Required** here, DEC-022)
* Feature-based frontend (not full FSD) — [`architecture/frontend.md`](./architecture/frontend.md)
* Repository — **not used** (DEC-023). File: [`patterns/repository.md`](./patterns/repository.md)
* Notification module — **not used** in MVP (DEC-010)

---

# Project Technology

Fill from the adopting project's lockfiles and runtime. Replace every placeholder.

## Frontend

**Terpasang** (`package.json`):

* React 19 + TypeScript
* Inertia.js v3 (`@inertiajs/react`, `@inertiajs/vite`)
* Tailwind CSS 4 + `@tailwindcss/vite`
* Radix UI primitives (pola shadcn: dialog, select, dropdown, dll.)
* Vite 8 (`vite-plus` / `vp`)
* lucide-react, sonner, class-variance-authority, tailwind-merge, clsx
* `@laravel/passkeys` (klien)

**Wajib ditambah** (belum ada di lockfile; lihat DEC-001):

* React Hook Form + Zod — form pengiriman yang kompleks
* TanStack Table (`@tanstack/react-table`) — modul laporan
* cmdk — searchable dropdown pelanggan
* `browser-image-compression` — kompresi gambar di frontend sebelum unggah foto bukti pengiriman (DEC-002)

**Tidak dipakai** (sengaja tidak diadopsi dari daftar Recommended baseline): TanStack Query. Inertia menangani page visits; strategi fetch async belum dipilih (lihat State / data fetching).

Recommended (not Required) when starting a Laravel + React product:

* React
* TypeScript
* Inertia.js for page navigation
* Tailwind CSS
* shadcn/ui primitives
* TanStack Query for server state
* TanStack Table for data tables
* React Hook Form + Zod for forms

## Backend

**Terpasang** (`composer.json`):

* PHP `^8.3`
* Laravel 13 (`laravel/framework` `^13.17`)
* Inertia Laravel v3
* Laravel Fortify
* Laravel Wayfinder
* Laravel Chisel
* Laravel Tinker

**Dev:** Pest, Pint, Larastan, Boost, Sail, Pail, Collision

**Sudah dipakai di kode:** Form Request pada settings (`app/Http/Requests/Settings/`).

**Belum ada:** PHP Enum domain pengiriman (`app/Enums/` kosong); API Resource belum dipakai.

**Arsitektur (DEC-022–024, DEC-029):** Pragmatic Modular Monolith. Thin Controller + Action. Eloquent + local scope; tanpa Repository. Tanpa Domain Event/Listener di MVP. Audit eksplisit di Action. Event/queue deferred.

Recommended (not Required) when starting a PHP product:

* Laravel
* PHP Enums for fixed domain values
* Form Request for input validation
* API Resource or equivalent explicit serializer

## Database

**Keputusan proyek (DEC-003):** **MySQL** di Laragon (dev dan selanjutnya, sampai ada keputusan environment lain).

**Perilaku kode saat ini:** `.env` / `.env.example` masih `DB_CONNECTION=sqlite` (sisa starter). Itu belum diganti di source; dokumentasi ini mencatat niat Oracle, bukan state runtime sekarang.

Engine: MySQL (Laragon). Gunakan foreign key, index, dan nama identifier eksplisit (batas 64 karakter — `conventions/database.md`).

Recommended: a relational database with migrations, foreign keys, and indexes. Choose MySQL or PostgreSQL from project needs.

## Authentication

**Keputusan proyek (DEC-004):** Laravel Fortify + sesi web (guard `web`, Inertia). **Login / logout / sesi saja.**

Fitur Fortify yang **dimatikan** (bukan bagian produk):

* Registrasi publik
* Reset password
* Verifikasi email
* Two-factor authentication (2FA)
* Passkeys

Pengguna pertama dibuat lewat **seeder** dengan role `superadmin`. Pengguna berikutnya dibuat lewat modul Manajemen Pengguna — bukan self-signup; role mana yang boleh membuat role lain dan alur bootstrap credential/recovery belum dikunci (DEC-030).

**Perilaku kode saat ini:** `config/fortify.php` masih mengaktifkan registration, resetPasswords, emailVerification, twoFactorAuthentication, passkeys. `routes/web.php` masih `auth` + `verified`. Itu sisa starter, belum disesuaikan di source.

## Authorization model

**Keputusan proyek (DEC-005):** Satu kolom `users.role` bertipe **string**. Tidak memakai `spatie/laravel-permission` atau tabel roles/permissions terpisah.

Pembatasan hak akses: **Laravel Gates & Policies** (bawaan). Enforcement wajib di server. UI hanya menyembunyikan tombol; bukan batas keamanan.

Nilai role dikunci ulang oleh DEC-030: `superadmin`, `owner`, `admin`, `karyawan`. `superadmin` adalah role tersendiri dengan full application access; permission `owner`, `admin`, dan `karyawan` belum dikunci dan tidak boleh diinferensikan dari urutan role.

`sopir` dan `petugas_lapangan` adalah klasifikasi operasional tenaga kerja, bukan nilai `UserRole`. Bentuk penyimpanan dan kardinalitas klasifikasi belum dipilih.

**Perilaku kode saat ini:** tabel `users` starter belum punya kolom `role`.

## State / data fetching

**Keputusan proyek (DEC-006):**

* Navigasi / first paint / flash: **Inertia page props**
* Data async (filter, modal CRUD, unggahan): **Inertia `useHttp`** dan **Inertia Form** (`useForm` / `<Form>`), dibungkus hook/service fitur
* **Bukan** TanStack Query (DEC-001)

Cache server-state library terpisah tidak dipakai. UI state tetap lokal. Error `422` tetap di form.

Recommended for React SPA-style screens: dedicated HTTP client + server-state library. Do not lock a specific HTTP library.

## HTTP client

**Keputusan proyek (DEC-007):** Klien HTTP = **Inertia bawaan** (`useHttp`, Inertia Form / `useForm`). **Bukan** Axios. **Bukan** wrapper `fetch` terpisah.

Sesi, CSRF, dan error transport mengikuti Inertia. Validasi `422` tetap di form. URL typed: Wayfinder (`@/actions/`, `@/routes/`).

Recommended: one shared client with interceptors for session, authorization, and server errors. Validation errors (`422`) stay in the form.

## Local runtime

**Keputusan proyek (DEC-008):** **Laragon di Windows** — PHP, MySQL (DEC-003), Node/Vite. Bukan Sail/Docker sebagai runtime harian.

Path kerja: `D:\laragon\www\Jukey\Logistik`.

Perintah yang ada di repo:

* App: `composer run dev` (`php artisan dev`)
* Frontend: `npm run dev` / `npm run build` (`vp`)
* Tes PHP: `php artisan test` / `composer test`
* Types PHP: `composer types:check` (phpstan)
* Types JS: `npm run types:check`

`laravel/sail` ada di `require-dev` tetapi **bukan** cara kerja proyek ini. Jika panduan generik menyebut Docker/Sail, field ini yang menang.

## Locale / copy

**Keputusan proyek (DEC-025):** Bahasa Indonesia **tunggal** untuk seluruh copy UI dan pesan sistem. **Bukan** dwibahasa.

Wajib saat implementasi (bukan pass `.ai/` ini):

* `.env`: `APP_LOCALE=id`, `APP_FALLBACK_LOCALE=id`
* `config/app.php`: locale dan fallback default **`id`** (bukan `en`)
* Pesan kerangka (validasi Form Request, auth Fortify, pagination, format tanggal/waktu Carbon) memakai lokalisasi **`id`**

**Perilaku kode saat ini:** `APP_LOCALE=en`, `APP_FALLBACK_LOCALE=en`; `config/app.php` default `'en'`; halaman starter masih Inggris.

## Application display name

**Keputusan proyek (DEC-027):** `APP_NAME` = **Logistik** (untuk sekarang). Bukan `Laravel`.

Wajib di `.env` / `config/app.php` `name` saat implementasi. **Perilaku kode saat ini:** `APP_NAME=Laravel`.

## Timezone

**Keputusan proyek (DEC-026):** **`Asia/Jakarta`** adalah **Single Source of Truth** untuk seluruh sistem. Bukan UTC, bukan timezone OS mesin.

Berlaku untuk: `created_at` / `updated_at`, logika **penjadwalan**, audit trail, ekspor `.xlsx`/`.csv`, tampilan UI, dan perbandingan waktu di Action.

**Dilarang** mencampur UTC dan WIB (risiko selisih 7 jam). Jangan simpan UTC di DB lalu tampilkan Jakarta tanpa aturan yang sama di semua lapisan — satu zona, `Asia/Jakarta`, di `config/app.php`.

**Perilaku kode saat ini:** `config/app.php` `'timezone' => 'UTC'`. Belum diubah (sandbox).

## Queue / background jobs

**Keputusan proyek (DEC-021):** seluruh fitur MVP **sinkron**. **Tidak ada** `ShouldQueue`, Job aplikasi, atau Queue Worker.

Deploy/DevOps **tidak** memerlukan Supervisor, `php artisan queue:work`, atau Horizon.

Tabel `jobs` (dan migrasi starter) **tetap** di database untuk fase berikutnya. Jangan hapus.

`QUEUE_CONNECTION=database` di `.env` starter boleh ada; **jangan** mengandalkannya. Untuk MVP, `sync` adalah driver yang selaras (pekerjaan tidak tertahan). Mengganti `.env` adalah tugas implementasi, bukan pass `.ai/` ini.

## File storage

**Keputusan proyek (DEC-013):** MVP memakai disk Laravel **`local`** (dan **`public`** hanya untuk berkas yang memang boleh diakses publik). Bukan S3/GCS di MVP. Cloud-ready: ganti env, bukan ganti kode pemanggil.

**Wajib:**

* Semua unggah/baca/hapus berkas lewat **`Illuminate\Support\Facades\Storage`**
* Nama disk **hanya** dari config/env (`FILESYSTEM_DISK`, dan disk publik dari config yang diikat env — misalnya `FILESYSTEM_PUBLIC_DISK` default `public`)
* **Dilarang** hardcode `'local'`, `'public'`, `'s3'`, `'gcs'` (atau nama disk lain) di controller, service, action, job, atau listener

Tempat nama disk boleh muncul: `config/filesystems.php` dan `.env`. Layer aplikasi memakai `Storage::disk(config(...))` atau disk default `Storage::`.

**Bukti pengiriman (DEC-014):** hanya disk private. View/download: route auth + Policy, atau temporary signed URL. Bukan URL publik.

SOP: [`conventions/storage.md`](./conventions/storage.md). Aturan agen: [`.ai/rules/storage-facade.md`](./rules/storage-facade.md), [`.ai/rules/private-pod-files.md`](./rules/private-pod-files.md).

## Fixed domain values

**Keputusan proyek (DEC-009, role direvisi DEC-030).** PHP Enum **belum** ada di `app/Enums/` (adaptasi `.ai/` tidak menyentuh source). Saat implementasi, buat backed string enum berikut setelah seluruh prerequisite bisnisnya disetujui.

### `UserRole` → `app/Enums/UserRole.php`

| Case | Value |
| --- | --- |
| Superadmin | `superadmin` (full application access; akun seeder) |
| Owner | `owner` |
| Admin | `admin` |
| Karyawan | `karyawan` |

Kolom: `users.role` (DEC-005). Cast ke `UserRole`. Jangan implementasikan inheritance/numeric comparison berdasarkan urutan enum. `sopir` dan `petugas_lapangan` tidak termasuk enum ini (DEC-030).

### `ShipmentStatus` → `app/Enums/ShipmentStatus.php`

| Case | Value |
| --- | --- |
| MenungguPersetujuan | `menunggu_persetujuan` |
| MenungguPenjadwalan | `menunggu_penjadwalan` |
| Terjadwal | `terjadwal` |
| DalamPerjalanan | `dalam_perjalanan` |
| ProsesPemindahan | `proses_pemindahan` |
| DalamPengiriman | `dalam_pengiriman` |
| MenungguValidasi | `menunggu_validasi` |
| Selesai | `selesai` |
| Dibatalkan | `dibatalkan` |

**Transisi (DEC-015):** linier, satu langkah. Terminal **immutable:** `selesai`, `dibatalkan`.

```text
menunggu_persetujuan → menunggu_penjadwalan
menunggu_penjadwalan → terjadwal
terjadwal → dalam_perjalanan
dalam_perjalanan → proses_pemindahan
proses_pemindahan → dalam_pengiriman
dalam_pengiriman → menunggu_validasi
menunggu_validasi → selesai

(non-terminal) → dibatalkan
```

`selesai` dan `dibatalkan` tidak punya transisi keluar (termasuk tidak saling ganti). Dilarang loncat urutan. Validasi **wajib di backend** (FSM atau aturan ketat di service/Action) — bukan hanya UI.

### `PaymentStatus` → `app/Enums/PaymentStatus.php`

| Case | Value |
| --- | --- |
| BelumDibayar | `belum_dibayar` |
| SudahDibayar | `sudah_dibayar` (`label()` = **Lunas**) |

Backing value **bukan** `lunas`. "Lunas" hanya teks UI.

**Transisi (DEC-016):** hanya `belum_dibayar` → `sudah_dibayar`. `sudah_dibayar` **terminal immutable**. Validasi ketat di backend.

Mesin ini **independen** dari pembatalan pengiriman: `ShipmentStatus::dibatalkan` tidak mengubah `PaymentStatus`, dan sebaliknya.

Transisi ke `sudah_dibayar` **wajib** menyertakan (reject jika kosong):

* `PaymentMethod` (`tunai` | `transfer`)
* nominal aktual **>= harga kesepakatan perusahaan** (jika lebih kecil: **tolak** `sudah_dibayar`)
* identitas user/admin yang mengubah status (jejak audit)

Bonus/tip tim: jika aktual **>** kesepakatan, selisih **otomatis** dicatat; **tidak boleh negatif**. Jika aktual **<** kesepakatan: **tolak** lunas (DEC-018).

**Kardinalitas (DEC-017):** satu pembayaran per pengiriman. Bukan cicilan/parsial.

### `PaymentMethod` → `app/Enums/PaymentMethod.php`

| Case | Value |
| --- | --- |
| Tunai | `tunai` |
| Transfer | `transfer` |

Ikuti [`patterns/enum.md`](./patterns/enum.md): `label()`, `options()`, `Rule::enum()`, jangan tabel lookup.

**Uang (DEC-019):** harga kesepakatan, nominal aktual, tip = **Rupiah bilangan bulat** (tanpa sen). Skema: **`BIGINT`** atau **`DECIMAL(p, 0)`**. **Dilarang** `FLOAT` / `DOUBLE`. SOP: [`conventions/money.md`](./conventions/money.md).

## Notification channels

**Keputusan proyek (DEC-010):** Pola notifikasi otomatis **tidak dipakai di MVP**. Tidak ada kanal produk (email, WhatsApp API, push, in-app feed).

Komunikasi ke pelanggan/pihak luar: **WhatsApp manual** (di luar sistem). Operasional internal: **pull** — cek langsung di UI aplikasi, bukan push.

Laravel `mail` / `BROADCAST` di `.env` starter bukan kanal bisnis. Jangan bangun `app/Notifications` domain untuk MVP.

## Quality gate

**Single source of truth (DEC-011):** `composer ci:check`

```bash
composer ci:check
```

It already runs (in that Composer script): `npm run check`, `npm run types:check`, then `composer test` (Pint `--test`, PHPStan, Pest).

**Locked rules for agents and CI/CD:**

1. Quality gate **is** `composer ci:check`. Do not substitute a homemade list of Pint/PHPStan/Pest/npm steps.
2. Execution **requires a hybrid environment: PHP and Node.js** (Composer + npm). Do not run the gate in a PHP-only or Node-only job and call that complete.
3. In **CI/CD configuration**, do **not** split or invoke the underlying scripts individually (`npm run check`, `npm run types:check`, `composer test`, `vendor/bin/pint`, `phpstan`, `php artisan test`, `npm run types:check`, dll.). One step: `composer ci:check`.
4. Locally, a **narrow** `php artisan test --filter=...` for the change under work is allowed. That is not the quality gate and must not replace `composer ci:check` in CI.

**Perilaku kode saat ini:** `.github/workflows/tests.yml` already sets up PHP 8.4 + Node 22 and runs `composer ci:check`. Keep it that way.

The Recommended baseline command list below is **not** this project's gate (`npm run lint:check` / `format:check` do not exist).

## Data export

**Keputusan proyek (DEC-020):** paket **`maatwebsite/excel`** (belum di `composer.json`; pasang saat implementasi). Generate **sepenuhnya di backend**.

MVP: eksekusi **sinkron** — HTTP request menghasilkan **direct download**. **Dilarang** Queue, Background Job, notifikasi selesai, atau download center di UI.

Scope: jaga query/ekspor cukup kecil untuk satu request. Jangan menambah job hanya untuk menghindari timeout.

Recommended sequence when using Laravel + Node:

```bash
vendor/bin/pint --dirty
vendor/bin/phpstan analyse
php artisan test
npm run types:check
npm run lint:check
npm run format:check
npm run build
```

Replace with the commands this project actually runs.

---

# Dependency Rules

Before adding a package:

1. Explain why it is needed.
2. Check whether an existing package already covers it.
3. Explain production impact.
4. Ask if it is not part of the requested work.

Do not install a package only because it is considered "best practice".

Record accepted new dependencies in [`DECISION_LOG.md`](./DECISION_LOG.md).

---

# Related Documents

* [PROJECT.md](./PROJECT.md)
* [architecture/README.md](./architecture/README.md)
* [RULES.md](./RULES.md)
* [conventions/](./conventions/)
* [patterns/](./patterns/)
