# Overview (actual vs planned)

Map of this repository versus Oracle decisions. Code wins for current behavior; `PROJECT.md` / `TECH_STACK.md` / accepted DECs win for intended product.

## Implemented (starter)

* Laravel 13 + Inertia React + Fortify (full starter features still on)
* Pages: welcome, dashboard, auth, settings
* Model: `User` only — no `role` column yet
* Database `.env`: sqlite (intended: MySQL on Laragon, DEC-003)
* `FILESYSTEM_DISK=local` (intended: Storage facade + env disks, DEC-013)
* No `resources/js/features/` yet (starter uses `pages/` + `components/`)

## Planned, not in source

* Architecture style DEC-029 (Pragmatic Modular Monolith + Progressive Architecture)
* `app/Enums/UserRole.php`, `ShipmentStatus.php`, `PaymentStatus.php`, `PaymentMethod.php` (DEC-009)
* Fortify login-only (DEC-004)
* `users.role` string + Policies (DEC-005)
* Product capabilities in `PROJECT.md` — implemented as User / Customer / Vehicle / Shipment workflow grouping, not 11 packages
* Automatic notifications (DEC-010 — skip)
* POD files: private disk; MVP download = auth+Policy+stream (DEC-014)
* ShipmentStatus graph + backend SSOT (DEC-015)
* PaymentStatus one-way + required paid fields (DEC-016–018)
* Money BIGINT / DECIMAL(p,0); never float (DEC-019)
* maatwebsite/excel sync export; no job (DEC-020)
* No ShouldQueue / no queue worker; keep jobs table (DEC-021)
* Thin Controller + HTTP-agnostic Actions (DEC-022)
* No Repository; Eloquent + local scopes (DEC-023)
* No domain events in MVP; audit in Actions (DEC-024)
* UI + framework locale `id` only (DEC-025)
* Timezone Asia/Jakarta SST (DEC-026)
* APP_NAME Logistik for now (DEC-027)
* Interview closed; see mvp-lock.md (DEC-028)

Do **not** create `app/Domain`, `app/Queries`, `app/Modules`, or `app/Repositories` while implementing.

Do not search the repo for shipment/payment/fleet code. It is not there yet.
