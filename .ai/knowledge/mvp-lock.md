# MVP lock (canonical)

> **Panduan implementasi MVP.** Interogasi baseline ditutup 2026-09-02 (DEC-028), lalu kontrak role direvisi secara eksplisit pada 2026-09-20 (DEC-030). Pertanyaan user/access pada DEC-030 masih terbuka. Implementasi source menunggu instruksi berikutnya.

If this file conflicts with starter **code**, the intended product is this file + `PROJECT.md` + `DECISION_LOG.md` + `TECH_STACK.md`. Code is still the starter until implementation.

Full DEC text: [`../DECISION_LOG.md`](../DECISION_LOG.md) (DEC-001 … DEC-029).

---

## Product

* **Logistik** — jasa pengiriman B2B (perusahaan/cabang) dan B2C (mis. pindah rumah)
* Internal only; authorization roles: `superadmin`, `owner`, `admin`, `karyawan`
* `superadmin`: distinct highest role, full application access
* `owner`, `admin`, `karyawan`: distinct roles; exact permissions and data scopes are not yet locked
* `sopir` / `petugas_lapangan`: operational workforce classifications, **not** authorization roles; schema and cardinality unresolved
* No customer portal, no native Android/iOS
* Success: one shipment end-to-end (order → jadwal → bukti kerja → tagihan)

## Auth & access

* Fortify: login/logout/session only. No register, reset, email verify, 2FA, passkeys
* First user: seeder role **`superadmin`**; bootstrap credential/recovery flow unresolved
* `users.role` string + Laravel Gates/Policies — **no Spatie**
* No numeric role comparison or implicit inheritance; abilities are explicit business rules

## Money & payment

* 1:1 shipment ↔ payment; no cicilan
* `belum_dibayar` → `sudah_dibayar` (terminal). Independent of shipment cancel
* Paid requires: method, nominal aktual **>= harga kesepakatan**, actor
* Tip = overpay (never negative). Underpay **rejects** lunas
* Money: whole IDR; DB **BIGINT** or `DECIMAL(p,0)`; **never** FLOAT/DOUBLE

## Shipment status (linear, backend FSM)

`menunggu_persetujuan` → `menunggu_penjadwalan` → `terjadwal` → `dalam_perjalanan` → `proses_pemindahan` → `dalam_pengiriman` → `menunggu_validasi` → `selesai`

Non-terminal → `dibatalkan`. **`selesai` and `dibatalkan` are immutable.**

## Files

* Storage facade; disk names from **config/env** only
* MVP disks: `local` + `public` for truly public files; not S3/GCS
* **POD: private only.** MVP default view/download: auth+Policy+stream. Signed URL allowed later (DEC-014), not the default parallel design. No `/storage` public URL
* Compress POD photos with `browser-image-compression` before upload

## Architecture

* **DEC-029:** Pragmatic Modular Monolith + Progressive Architecture
* Default flow: Inertia / Controller → Form Request + Policy → Action → Eloquent → Database
* Product capabilities in `PROJECT.md` ≠ code packages. MVP code may group User, Customer, Vehicle, Shipment workflow
* Thin Controller; Actions in `app/Actions/` **HTTP-agnostic** (no `request()` / `response()` / `redirect()` / session)
* No Repository; Eloquent in Actions; **local scopes** first; query objects only when queries are unreadable
* No Domain Events/Listeners in MVP. **Audit in the Action**. Events/queues **deferred** until a real async side effect
* Fully **sync**: no `ShouldQueue`, no queue worker. Keep `jobs` table
* Export: `maatwebsite/excel`, **direct download**, no download center
* Do not create `app/Domain`, `app/Queries`, or `app/Modules` speculatively

## Frontend

* Keep starter: React 19, Inertia v3, Tailwind 4, Radix
* Add: RHF+Zod, TanStack Table, cmdk, `browser-image-compression`
* No TanStack Query; HTTP = Inertia `useHttp` / Form; no Axios

## Platform

* Laragon Windows + **MySQL** (`.env` still sqlite until impl)
* Quality gate: **only** `composer ci:check` (PHP+Node; do not split in CI)
* Locale **id** / fallback **id**; UI Indonesian only
* Timezone **Asia/Jakarta** SST (DB, schedule, audit, export, UI) — not UTC
* `APP_NAME=Logistik` (for now)

## Still starter in code (do on implementation)

Enums, `users.role`, Fortify feature flags, MySQL `.env`, `APP_LOCALE`, `timezone`, `APP_NAME`, Composer/npm packages above.

## Do not invent

Anything not in `PROJECT.md`, `GLOSSARY.md`, `TECH_STACK.md`, or an Accepted DEC. Ask Oracle.
