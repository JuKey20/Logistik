# Decision Log

> Record why a decision was made. Changelog records what changed.

Projects that adopt this baseline start numbering at **DEC-001**. Do not reuse numbers from any other product.

---

# Purpose

* Capture the reason, alternatives, and consequences.
* Stop the team and the AI from re-arguing settled choices.
* Keep a traceable history when architecture or conventions change.

---

# Identifier

Use `DEC-XXX` for every significant decision: architecture, engineering, security, workflow, or product-technical trade-offs.

Do not use product-specific prefixes from other repositories.

Numbering is per project. The first decision written after adaptation is `DEC-001`.

---

# Status

| Status | Meaning |
| --- | --- |
| Proposed | Still under discussion |
| Accepted | In force |
| Deprecated | No longer used |
| Superseded | Replaced by a later DEC |
| Rejected | Considered and not chosen |

Do not delete old decisions. Supersede them.

---

# When to write a decision

Write a DEC when the team:

* Chooses or rejects a recommended baseline pattern (Action, Repository, Notification, HTTP client, …)
* Introduces a new dependency
* Changes authorization, persistence, or API contracts
* Accepts a trade-off that future readers will not see in the code

Do not write a DEC for a one-line bug fix.

---

# Template

```markdown
## DEC-XXX

Status: Proposed | Accepted | Deprecated | Superseded | Rejected

Category: Architecture | Backend | Frontend | Database | Security | Workflow | Other

Date: YYYY-MM-DD

Title: Short decision title

### Context

Why this needed a decision.

### Decision

What was chosen.

### Alternatives considered

- Alternative A
- Alternative B

### Reason

Why this option won.

### Consequences

Positive and negative effects.

### Related documents

- architecture/README.md
- TECH_STACK.md
```

---

# Decision index

| ID | Title | Category | Status |
| --- | --- | --- | --- |
| DEC-001 | Frontend: base stack + RHF/Zod, TanStack Table, cmdk, image compression | Frontend | Accepted |
| DEC-002 | Use `browser-image-compression` for POD photos | Frontend | Accepted |
| DEC-003 | Use MySQL on Laragon (not SQLite) | Database | Accepted |
| DEC-004 | Fortify login-only; no register/reset/verify/2FA/passkeys; superadmin seeder | Security | Accepted |
| DEC-005 | `users.role` string + Laravel Gates/Policies; no Spatie | Security | Accepted |
| DEC-006 | Inertia props + useHttp/Form; no TanStack Query | Frontend | Accepted |
| DEC-007 | HTTP client = Inertia useHttp/Form; no Axios | Frontend | Accepted |
| DEC-008 | Local runtime = Laragon on Windows, not Sail | Workflow | Accepted |
| DEC-009 | Lock MVP enums: UserRole, ShipmentStatus, PaymentStatus, PaymentMethod | Backend | Partially superseded by DEC-030 |
| DEC-010 | No automatic notifications in MVP; WhatsApp manual + UI pull | Other | Accepted |
| DEC-011 | Quality gate is only `composer ci:check`; hybrid PHP+Node; no CI split | Workflow | Accepted |
| DEC-012 | Baseline metadata `1.0.0` / `2026-09-01` is immutable | Workflow | Accepted |
| DEC-013 | MVP local/public disks; Storage facade only; no hardcoded disk names | Backend | Accepted |
| DEC-014 | POD files private-only; auth route or temporary signed URL | Security | Accepted |
| DEC-015 | Linear ShipmentStatus FSM; selesai/dibatalkan terminal; server-side only | Backend | Accepted |
| DEC-016 | PaymentStatus one-way to lunas; independent of cancel; paid requires extras | Backend | Accepted |
| DEC-017 | 1:1 shipment–payment; tip from actual vs agreed price | Backend | Accepted |
| DEC-018 | Paid only if actual >= agreed; tip is overpay; never negative | Backend | Accepted |
| DEC-019 | Money as whole IDR; BIGINT or DECIMAL(p,0); never float | Database | Accepted |
| DEC-020 | maatwebsite/excel sync download; no export queue | Backend | Accepted |
| DEC-021 | Fully sync MVP; no workers; keep jobs table | Workflow | Accepted |
| DEC-022 | Thin Controller; HTTP-agnostic Actions in app/Actions | Backend | Accepted |
| DEC-023 | No Repository; Eloquent in Actions; complex queries as local scopes | Backend | Accepted |
| DEC-024 | No domain events; audit written synchronously in Actions | Backend | Accepted |
| DEC-025 | Indonesian-only UI and framework locale (`id`) | Frontend | Accepted |
| DEC-026 | Asia/Jakarta is SST for app, DB timestamps, audit, export | Backend | Accepted |
| DEC-027 | APP_NAME is Logistik (for now) | Other | Accepted |
| DEC-028 | Close interview; mvp-lock.md is the implementation brief | Workflow | Accepted |
| DEC-029 | Pragmatic Modular Monolith + Progressive Architecture | Architecture | Accepted |
| DEC-030 | Separate authorization roles from operational workforce classification | Security | Accepted |
| DEC-031 | Lock User & Access lifecycle, authority, recovery, and workforce contract | Security | Accepted |

**Interview status:** baseline interview closed 2026-09-02 (DEC-028). DEC-030 revised the role contract and DEC-031 resolved its generic User & Access questions on 2026-09-20. Module-specific permissions and listed implementation-design details remain open. Application source still waits for a separate implementation instruction.

---

# Decisions

## DEC-001

Status: Accepted

Category: Frontend

Date: 2026-09-01

Title: Frontend: base stack + RHF/Zod, TanStack Table, cmdk, image compression

### Context

Starter kit already ships React 19, TypeScript, Inertia v3, Tailwind 4, Radix/shadcn, Vite. Shipping forms, reports, customer pickers, and POD photos need extra client libraries. Baseline Recommended also listed TanStack Query; that is not required here.

### Decision

Keep the installed base stack. **Require** these additions (install when implementation starts):

* React Hook Form + Zod for complex shipment forms
* TanStack Table for the reporting module
* cmdk for searchable customer dropdowns
* Frontend image compression before uploading proof-of-delivery photos

Do **not** add TanStack Query as part of this decision.

The specific image-compression package is chosen in **DEC-002**.

### Alternatives considered

- Stay on starter-only forms/tables (insufficient for shipment forms, reports, customer search, POD photos)
- Adopt the full baseline Recommended set including TanStack Query (not chosen)

### Reason

Oracle: use the existing base stack, but these four capabilities are mandatory for the product modules.

### Consequences

- New dependencies need installing before those UI modules are built; do not treat them as already present in `package.json`
- Form pattern: React Hook Form + Zod (`patterns/form.md`)
- Image compression library: see DEC-002
- Server remains the validation authority; Zod does not replace Form Request rules

### Related documents

- TECH_STACK.md
- PROJECT.md (modules, NFRs)
- patterns/form.md
- architecture/frontend.md

## DEC-002

Status: Accepted

Category: Frontend

Date: 2026-09-01

Title: Use `browser-image-compression` for POD photos

### Context

DEC-001 required frontend image compression before uploading proof-of-delivery photos, but did not name a package.

### Decision

Use **`browser-image-compression`**. Install when implementing Validasi & Bukti Kerja. Compress on the client before upload.

### Alternatives considered

- `compressorjs`
- Canvas API without a new package

### Reason

Oracle confirmed the guess: common, lightweight React usage for client-side compress-before-upload.

### Consequences

- Another package not yet in `package.json`
- Server must still validate file type, size, and content; client compression is not a security control

### Related documents

- TECH_STACK.md
- PROJECT.md
- DEC-001

## DEC-003

Status: Accepted

Category: Database

Date: 2026-09-01

Title: Use MySQL on Laragon (not SQLite)

### Context

Starter kit ships `DB_CONNECTION=sqlite`. The product will run on Laragon. SQLite is a poor fit for concurrent internal ops (orders, dispatch, billing, uploads).

### Decision

Use **MySQL** on Laragon. Do not treat SQLite as the project database.

`.env` is still sqlite until the application config is changed; that change is outside this `.ai/` adaptation pass.

### Alternatives considered

- Keep SQLite (current `.env`)
- PostgreSQL

### Reason

Oracle correction: MySQL on Laragon.

### Consequences

- Follow MySQL identifier length and explicit FK names (`conventions/database.md`)
- Agents must not add SQLite-only features as if they were the production engine
- Switching `.env` / migrations against MySQL is an implementation task, not done in this documentation pass

### Related documents

- TECH_STACK.md
- conventions/database.md

## DEC-004

Status: Accepted

Category: Security

Date: 2026-09-01

Title: Fortify login-only; no register/reset/verify/2FA/passkeys; superadmin seeder

### Context

The starter enables Fortify registration, password reset, email verification, 2FA, and passkeys. This app is internal-only. The first account should not come from a public register form.

### Decision

Keep **Laravel Fortify** for session login/logout. **Disable** registration, password reset, email verification, 2FA, and passkeys.

Bootstrap the first user with a **superadmin seeder** (the Oracle / that role). Later users are created through Manajemen Pengguna & Hak Akses.

Starter code still has those Fortify features and `verified` middleware on; changing that is an implementation task, not this `.ai/` pass.

### Alternatives considered

- Keep the full Fortify starter feature set
- Replace Fortify with a custom auth stack

### Reason

Oracle: those features stay off; first access is a seeder, not self-service auth.

### Consequences

- No public signup, no self-serve reset, no email-verification gate, no 2FA/passkeys in product scope
- Password recovery for locked-out staff was not specified here; DEC-031 later resolves `karyawan` recovery and leaves higher-role recovery design open
- `MustVerifyEmail` / `verified` middleware must not remain a product requirement once code is aligned
- User & RBAC module is how accounts are added after the seeder

### Related documents

- TECH_STACK.md
- architecture/security.md
- PROJECT.md
- GLOSSARY.md (Superadmin)

## DEC-005

Status: Accepted

Category: Security

Date: 2026-09-01

Title: `users.role` string + Laravel Gates/Policies; no Spatie

### Context

Authorization is RBAC for internal users (including a superadmin seeder). Spatie Permission was the interview guess.

### Decision

Do **not** use `spatie/laravel-permission`. Add one **string** column `role` on `users`. Enforce access with **Laravel Gates and Policies** only.

Known role value so far: `superadmin`. Other role strings are not locked.

### Alternatives considered

- `spatie/laravel-permission` (rejected)
- Roles/permissions tables without Spatie (not chosen)

### Reason

Oracle: one string column is enough; use framework Gates & Policies.

### Consequences

- No permission packages in Composer for this
- Policies/Gates branch on `user.role` (and resource ownership where needed)
- Migrating `users.role` is an implementation task; starter schema does not have it yet
- Frontend may hide actions by role; server Policy remains the authority

### Related documents

- TECH_STACK.md
- architecture/security.md
- patterns/policy.md
- PROJECT.md
- GLOSSARY.md

## DEC-006

Status: Accepted

Category: Frontend

Date: 2026-09-01

Title: Inertia props + useHttp/Form; no TanStack Query

### Context

The baseline recommends a dedicated HTTP client plus TanStack Query. This app already uses Inertia v3. DEC-001 rejected TanStack Query as a required add-on.

### Decision

* Page data: **Inertia props**
* Async JSON / uploads / modal CRUD: **Inertia `useHttp`** and **Inertia Form**
* Do not introduce TanStack Query

### Alternatives considered

- TanStack Query + separate HTTP client (rejected in DEC-001 and here)
- Axios (Inertia v3 removed Axios; not chosen)

### Reason

Oracle confirmed the installed Inertia pattern.

### Consequences

- Feature hooks wrap Inertia, not `useQuery`
- `patterns/query-hook.md` samples that show TanStack Query must not be copied into this codebase
- HTTP client: confirmed in DEC-007 (Inertia `useHttp` / Form)

### Related documents

- TECH_STACK.md
- architecture/frontend.md
- patterns/query-hook.md
- DEC-001

## DEC-007

Status: Accepted

Category: Frontend

Date: 2026-09-01

Title: HTTP client = Inertia useHttp/Form; no Axios

### Context

DEC-006 set Inertia for async data. The remaining field was whether a second HTTP library (Axios or custom fetch) sits under it.

### Decision

The HTTP client **is** Inertia: `useHttp` and Inertia Form. No Axios. No separate `fetch` wrapper. Wayfinder for typed URLs. `422` stays on the form.

### Alternatives considered

- Axios
- Shared `fetch` client under `resources/js/api/`

### Reason

Oracle confirmed the guess from the starter (no `axios` in `package.json`; `useHttp` already used).

### Consequences

- Do not install Axios for app API calls
- Feature `services/` call Inertia, not a custom client
- Session/CSRF follow Laravel + Inertia, not a handmade interceptor stack

### Related documents

- TECH_STACK.md
- architecture/frontend.md
- patterns/service.md
- DEC-006

## DEC-008

Status: Accepted

Category: Workflow

Date: 2026-09-01

Title: Local runtime = Laragon on Windows, not Sail

### Context

The starter includes Laravel Sail. The workspace lives under Laragon and the database decision is MySQL on Laragon (DEC-003).

### Decision

Daily development is **Laragon on Windows** (PHP, MySQL, Node/Vite). Do not treat Sail/Docker as the project runtime. If a generic Laravel guide says otherwise, this field wins.

### Alternatives considered

- Laravel Sail / Docker Compose (present as a Composer dev dependency; not used)

### Reason

Oracle confirmed the Laragon path.

### Consequences

- Agents should not assume `./vendor/bin/sail` for commands
- MySQL is the engine to target in examples and identifier-length rules
- Switching `.env` off sqlite remains an implementation task (DEC-003)

### Related documents

- TECH_STACK.md
- DEC-003

## DEC-009

Status: Partially superseded by DEC-030

Category: Backend

Date: 2026-09-01

Title: Lock MVP enums: UserRole, ShipmentStatus, PaymentStatus, PaymentMethod

### Context

DEC-005 stored role as a string column with only `superadmin` named. MVP needs closed sets for roles, shipment status, payment status, and payment method. PHP enums are not in `app/Enums/` yet (`.ai/` adaptation does not edit application source).

### Decision

Lock these backed string values (PHP case names TitleCase when implemented):

**UserRole:** `owner` (superadmin / seeder), `admin`, `sopir`, `petugas_lapangan`. This **replaces** the earlier working name `superadmin` as a stored value.

**ShipmentStatus:** `menunggu_persetujuan`, `menunggu_penjadwalan`, `terjadwal`, `dalam_perjalanan`, `proses_pemindahan`, `dalam_pengiriman`, `menunggu_validasi`, `selesai`, `dibatalkan`.

**PaymentStatus:** `belum_dibayar`, `sudah_dibayar`. UI label for `sudah_dibayar` is **Lunas** (confirmed). Stored value is never `lunas`.

**PaymentMethod:** `tunai`, `transfer`.

Create `app/Enums/{UserRole,ShipmentStatus,PaymentStatus,PaymentMethod}.php` during implementation, with `label()` / `options()`, model casts, and `Rule::enum()`.

Allowed status *transitions* are not locked.

### Alternatives considered

- Lookup tables / Spatie roles (rejected earlier)
- Stored value `superadmin` instead of `owner` (rejected)

### Reason

Oracle: MVP values are agreed; `owner` is the superadmin role.

### Consequences

- Seed the first user as `owner`, not `superadmin`
- Do not invent extra statuses or roles without a new DEC
- `sudah_dibayar` stores paid; `label()` returns "Lunas"

### Related documents

- TECH_STACK.md
- patterns/enum.md
- GLOSSARY.md
- architecture/data.md
- DEC-005

## DEC-010

Status: Accepted

Category: Other

Date: 2026-09-01

Title: No automatic notifications in MVP; WhatsApp manual + UI pull

### Context

The baseline has an optional multi-channel notification module. This product is internal ops plus customer shipping.

### Decision

**Do not** build automatic outbound notifications for MVP (no email, WhatsApp API, push, or in-app notification feed as a product module).

Outside the system: staff use **WhatsApp manually**. Inside: staff **pull** state from the application UI.

### Alternatives considered

- Email / WhatsApp API / push as product channels (deferred)

### Reason

Oracle: skip the notification module for this MVP.

### Consequences

- Do not add Notification classes, listeners-to-notify, or channel packages for this milestone
- Do not invent in-app toast-as-business-notification requirements beyond existing UI (sonner is UX, not a channel)
- Revisit only with a new DEC

### Related documents

- TECH_STACK.md
- PROJECT.md (out of scope)
- patterns/notification.md

## DEC-011

Status: Accepted

Category: Workflow

Date: 2026-09-01

Title: Quality gate is only `composer ci:check`; hybrid PHP+Node; no CI split

### Context

Composer already defines `ci:check` as npm check + npm types:check + `composer test`. Agents might otherwise list Pint/PHPStan/Pest/npm as separate CI steps, or run PHP-only jobs.

### Decision

**`composer ci:check` is the single quality gate.**

* Requires **PHP and Node.js** in the same environment.
* **CI/CD must not** call underlying scripts individually (`npm run check`, `composer test`, Pint, PHPStan, Pest, dll.).
* Narrow local `php artisan test --filter=...` is allowed for the change under work; it is not the gate.

### Alternatives considered

- Baseline Recommended multi-command list (`npm run lint:check` / `format:check` — those scripts do not exist)
- Split CI steps per tool

### Reason

Oracle: one absolute source of truth; hybrid runtime; no splitting in CI/CD.

### Consequences

- `.github/workflows/tests.yml` already matches (PHP 8.4 + Node 22 + `composer ci:check`); keep it
- Do not "simplify" CI by dropping Node or inlining npm/Pest
- Rule file: `.ai/rules/quality-gate.md`

### Related documents

- TECH_STACK.md
- testing/README.md
- playbooks/review.md
- .ai/rules/quality-gate.md

## DEC-012

Status: Accepted

Category: Workflow

Date: 2026-09-01

Title: Baseline metadata `1.0.0` / `2026-09-01` is immutable

### Context

First adoption of AI baseline v1.0.0. Future agents might bump “Adopted on” when editing `PROJECT.md`, or treat a newer baseline as an automatic overwrite.

### Decision

Record:

* Baseline version: **`1.0.0`**
* Adopted on: **`2026-09-01`** (ISO 8601 date)

These two fields are **immutable**. AI must not change them unless the Oracle gives an **explicit baseline-upgrade** instruction.

### Alternatives considered

- Treat adopted date as “last updated” (rejected)

### Reason

Oracle: lock ISO values; no automatic mutation.

### Consequences

- Documentation harvest and stack updates leave this table alone
- A newer baseline must be merged by review, not by rewriting these fields
- Rule: `.ai/rules/baseline-metadata.md`

### Related documents

- PROJECT.md
- HOW_TO_ADAPT.md (upgrade is manual review)
- .ai/rules/baseline-metadata.md

## DEC-013

Status: Accepted

Category: Backend

Date: 2026-09-01

Title: MVP local/public disks; Storage facade only; no hardcoded disk names

### Context

Proof-of-delivery uploads need storage. Cloud (S3/GCS) was listed as a future option. Callers that hardcode `Storage::disk('local')` will not be cloud-ready.

### Decision

MVP: Laravel **`local`** disk, and **`public`** only for files that may be publicly reachable. Not S3/GCS as the running disk.

All application I/O uses the **Storage facade**. Disk names come from **config/env** (`FILESYSTEM_DISK`; public disk also env-backed). **Do not** hardcode disk names in controllers, services, actions, jobs, or listeners.

`config/filesystems.php` and `.env` are the places disk names may appear.

### Alternatives considered

- Hardcode `'local'` / `'s3'` in services (rejected)
- S3/GCS in MVP (rejected)

### Reason

Oracle: local/public for MVP; keep the app cloud-ready via abstraction.

### Consequences

- Future S3/GCS: change env/config, not callers
- Private files still follow `patterns/attachment.md` (no raw paths in JSON)
- Bukti pengiriman: private-only access locked in DEC-014

### Related documents

- TECH_STACK.md
- conventions/storage.md
- patterns/attachment.md
- .ai/rules/storage-facade.md
- PROJECT.md

## DEC-014

Status: Accepted

Category: Security

Date: 2026-09-01

Title: POD files private-only; auth route or temporary signed URL

### Context

DEC-013 allowed a `public` disk for files that may be public. Proof-of-delivery photos must not use that path. Guess: POD stays on the default private disk.

### Decision

**Strict isolation:** bukti pengiriman **must** be stored on the **private** disk. **Forbidden:** public disk, public URL, `/storage` links.

View or download **must** use:

* an **authenticated** route and a controller protected by **authorization (Gate/Policy)**, streaming via `Storage`, or
* a **temporary signed URL** (signed HTTP route; object `temporaryUrl` only when the configured disk supports it)

Permanent or unauthenticated public links are not allowed.

### Alternatives considered

- Serve POD from `public` disk / `storage:link` (rejected)
- Client-only filename in JSON without an authorized fetch (rejected)

### Reason

Oracle: private only; auth controller or temporary signed URLs.

### Consequences

- JSON may expose a route or short-lived signed URL, never a stable public file URL
- Frontend must not construct `/storage/...` for POD
- Local MVP: prefer signed **routes** + Policy; S3 `temporaryUrl` becomes available when the env disk supports it

### Related documents

- conventions/storage.md
- patterns/attachment.md
- architecture/security.md
- .ai/rules/private-pod-files.md
- DEC-013

## DEC-015

Status: Accepted

Category: Backend

Date: 2026-09-01

Title: Linear ShipmentStatus FSM; selesai/dibatalkan terminal; server-side only

### Context

DEC-009 locked status values but not the graph. A client could POST `selesai` from `menunggu_persetujuan` if the server stored the payload as-is.

### Decision

Forward transitions are **linear, one step**, in enum order through `selesai`. Any **non-terminal** status may move to `dibatalkan`.

`selesai` and `dibatalkan` are **terminal and immutable** (no reverse, no reopen).

The **backend** must enforce this (finite state machine or equivalently strict validation in the service/Action layer). UI is not a security boundary.

### Alternatives considered

- Client-only disabled buttons (rejected)
- Skip-forward to `selesai` (rejected)
- Reopen `selesai` / un-cancel (rejected)

### Reason

Oracle confirmed the linear guess and required terminal immutability plus strict backend validation.

### Consequences

- Do not persist a requested status without an allow-list from the current status
- No FSM Composer package is mandated; a small domain function/Action is enough
- Tests must cover illegal transitions (forbidden / 422)

### Related documents

- TECH_STACK.md
- GLOSSARY.md
- conventions/shipment-status.md
- architecture/backend.md
- .ai/rules/shipment-status-machine.md
- DEC-009

## DEC-016

Status: Accepted

Category: Backend

Date: 2026-09-01

Title: PaymentStatus one-way to lunas; independent of cancel; paid requires extras

### Context

Billing has `belum_dibayar` / `sudah_dibayar`. Shipment cancel is a different machine (DEC-015). Marking paid without method, amount, or actor would make audit and Harga & Pembayaran unreliable.

### Decision

`PaymentStatus` moves **only** `belum_dibayar` → `sudah_dibayar`. `sudah_dibayar` is **terminal** (label Lunas). Enforce on the **backend**.

**Independent from shipment cancellation:** cancelling a shipment must not change payment status; payment transitions are not driven by `dibatalkan`.

Transition to `sudah_dibayar` **requires**:

* payment method (`tunai` / `transfer`)
* actual nominal
* the user/admin who performed the update (history)

Reject if any of those are missing.

### Alternatives considered

- Sync payment to shipment cancel (rejected)
- Allow reverse from lunas (rejected)
- Status-only paid flag without method/nominal/actor (rejected)

### Reason

Oracle: one-way paid, independent of cancel, complementary data mandatory.

### Consequences

- Persist actor id (and enough audit fields) with the payment update
- Modul Log Aktivitas should be able to show who marked paid
- Currency/storage type for nominal is not locked (Decision Required if needed)

### Related documents

- TECH_STACK.md
- GLOSSARY.md
- conventions/payment-status.md
- architecture/backend.md
- .ai/rules/payment-status-machine.md
- DEC-009
- DEC-015

## DEC-017

Status: Accepted

Category: Backend

Date: 2026-09-01

Title: 1:1 shipment–payment; tip from actual vs agreed price

### Context

MVP billing must stay simple. Paid transitions already require method, actual amount, and actor (DEC-016). Field teams may collect more than the company price; that difference is a team bonus/tip.

### Decision

**One payment per shipment.** No installments, no partial payments, no extra payment rows.

The payment structure **must** hold:

* method (`tunai` / `transfer`)
* nominal aktual (what the customer paid)
* validating actor/admin
* **bonus/tip tim** — dedicated field **or** calculation from (nominal aktual vs **harga kesepakatan perusahaan**)

### Alternatives considered

- Multiple payments / cicilan (rejected for MVP)
- Status-only payment without amount/method/actor/tip (rejected)

### Reason

Oracle: 1:1 for MVP scope; tip from the actual vs agreed-price gap must exist in the data.

### Consequences

- Schema/migrations must not introduce payment history rows per installment
- If tip is stored, keep it consistent with the two source amounts
- Underpayment vs tip sign: **DEC-018**
- Money type: **DEC-019**

### Related documents

- conventions/payment.md
- conventions/payment-status.md
- architecture/data.md
- PROJECT.md
- GLOSSARY.md
- .ai/rules/payment-record.md
- DEC-016

## DEC-018

Status: Accepted

Category: Backend

Date: 2026-09-02

Title: Paid only if actual >= agreed; tip is overpay; never negative

### Context

DEC-017 left the underpayment case open. 1:1 payment means `sudah_dibayar` is full settlement (lunas mutlak), not a partial receipt.

### Decision

**Reject** the transition to `sudah_dibayar` if nominal aktual **<** harga kesepakatan perusahaan.

If actual **>** agreed, the difference is **automatically** recorded as bonus/tip tim. Tip **must not be negative**.

If actual **=** agreed, tip is **0**.

### Alternatives considered

- Allow paid with tip `0` on underpay (rejected)
- Allow negative tip (rejected)
- Treat underpay as cicilan (rejected; no cicilan)

### Reason

Oracle correction: lunas mutlak requires actual >= agreed; overpay is tip.

### Consequences

- Backend must compare actual vs agreed before flipping status
- Tests: underpay → rejected; equal → paid, tip 0; overpay → paid, tip = difference
- Money columns: **DEC-019**

### Related documents

- conventions/payment-status.md
- conventions/payment.md
- GLOSSARY.md
- .ai/rules/payment-status-machine.md
- .ai/rules/payment-record.md
- DEC-016
- DEC-017

## DEC-019

Status: Accepted

Category: Database

Date: 2026-09-02

Title: Money as whole IDR; BIGINT or DECIMAL(p,0); never float

### Context

Amounts are whole Rupiah. PHP/MySQL `FLOAT`/`DOUBLE` and `int` 32-bit columns are unsafe for large B2B totals.

### Decision

Store harga kesepakatan, nominal aktual, tip, and any later money fields as **integers without sen**.

**Schema:** `BIGINT` (preferred) or `DECIMAL(p, 0)` only.

**Forbidden:** `FLOAT`, `DOUBLE`, and `DECIMAL` with scale ≠ 0 for these columns.

PHP must use integer types/casts, not `float`.

### Alternatives considered

- `DECIMAL(12,2)` sen (rejected)
- `FLOAT`/`DOUBLE` (rejected)
- 32-bit `INT` only (rejected as the column type for overflow risk)

### Reason

Oracle: whole Rupiah; BIGINT or scale-0 DECIMAL; never float.

### Consequences

- Migrations for money must use `$table->unsignedBigInteger` / `bigInteger` or `decimal(..., 0)`
- Do not JSON-encode money as JS floats for large values without a string/bigint strategy later if needed
- Rule: `.ai/rules/money-columns.md`

### Related documents

- conventions/money.md
- conventions/database.md
- architecture/data.md
- TECH_STACK.md
- .ai/rules/money-columns.md
- DEC-018

## DEC-020

Status: Accepted

Category: Backend

Date: 2026-09-02

Title: maatwebsite/excel sync download; no export queue

### Context

The supporting module “Ekspor Data” needs `.xlsx`/`.csv`. Queued exports would need a download center or completion notification, which MVP is not building (DEC-010).

### Decision

Use **`maatwebsite/excel`**. Generate the file **entirely on the backend**. For MVP, run the export **synchronously** and return a **direct download**.

**Do not** use Queue or Background Jobs for this export. Do not add export-ready notifications or a download-center UI.

Keep export size/query cost suitable for one HTTP request.

The package is not in `composer.json` yet; install at implementation time.

### Alternatives considered

- Queued export + download center (rejected for MVP)
- Browser-side CSV only (rejected; backend generation required)
- PhpSpreadsheet alone without Laravel Excel (not chosen)

### Reason

Oracle: Laravel Excel, backend generate, sync download, no queue — keep MVP simple.

### Consequences

- `ShouldQueue` export classes are forbidden for this feature in MVP
- Timeout risk is accepted as a scope trade-off; constrain filters/limits rather than adding jobs
- Starter `jobs` table / `QUEUE_CONNECTION` may remain for Laravel; do not use them for data export

### Related documents

- TECH_STACK.md
- conventions/export.md
- patterns/job.md
- PROJECT.md
- .ai/rules/export-sync.md
- DEC-010

## DEC-021

Status: Accepted

Category: Workflow

Date: 2026-09-02

Title: Fully sync MVP; no workers; keep jobs table

### Context

DEC-020 forbids queued exports. There is no product notification channel (DEC-010). A worker (Supervisor / `queue:work`) would be empty DevOps cost and invite `ShouldQueue` by accident.

### Decision

**All MVP features run synchronously.** Do not implement `ShouldQueue` or application background jobs.

**Deployment must not** require a Queue Worker (Supervisor daemon, `php artisan queue:work`, Horizon).

Keep Laravel’s **`jobs` table** (starter migration) for later scalability. Do not drop it.

`.env` may still say `QUEUE_CONNECTION=database`; that is leftover. Prefer `sync` at implementation so stray dispatches still run inline.

### Alternatives considered

- Run a worker “just in case” (rejected)
- Delete `jobs` table (rejected; keep for later)

### Reason

Oracle: fully sync, no worker setup, keep the table.

### Consequences

- CI/deploy docs and agents must not add queue worker steps
- Fortify/mail/queue internals should not be treated as a product job pipeline
- Enabling real queues later needs a new DEC

### Related documents

- TECH_STACK.md
- conventions/queue.md
- patterns/job.md
- patterns/listener.md
- .ai/rules/sync-mvp.md
- PROJECT.md
- DEC-020
- DEC-010

## DEC-022

Status: Accepted

Category: Backend

Date: 2026-09-02

Title: Thin Controller; HTTP-agnostic Actions in app/Actions

### Context

Shipment/payment state machines and transactions must not live in controllers. Fortify already uses `app/Actions/Fortify`. The baseline Action pattern was Recommended; this project **requires** it.

### Decision

**Thin Controller:** validate the Request (Form Request), authorize, pass **arrays or primitives** into an Action, return Response or Redirect.

**Action** (`app/Actions/`): all business logic, state changes, and database transactions.

Actions are **HTTP-agnostic**. **Forbidden** inside Actions: `request()`, `response()`, `redirect()`, session manipulation, and HTTP Request/Response types.

Fortify contract Actions remain under `app/Actions/Fortify/` as framework adapters.

### Alternatives considered

- Fat controllers (rejected)
- HTTP-aware “service” using `request()` (rejected)
- Require DTOs on every Action (not mandated; arrays/primitives allowed)

### Reason

Oracle: thin controller; Actions isolated from the HTTP cycle.

### Consequences

- Domain folders like `app/Actions/Shipment/` for product operations
- Feature tests still go through HTTP; Actions stay unit-testable without a Request
- Repository: **DEC-023** (none in MVP)

### Related documents

- architecture/backend.md
- patterns/action.md
- patterns/request.md
- .ai/rules/thin-controller-actions.md
- TECH_STACK.md

## DEC-023

Status: Accepted

Category: Backend

Date: 2026-09-02

Title: No Repository; Eloquent in Actions; complex queries as local scopes

### Context

DEC-022 puts work in Actions. A Repository layer would be extra altitude for MVP. Long query-builder chains in Actions would still make them unreadable.

### Decision

**Do not** implement Repositories in MVP. Actions **may call Eloquent directly**.

When a query is long or complex (multi-condition filters, report aggregations, joins), **extract Eloquent local scopes** on the related Model. Actions stay short and descriptive.

Do not move shipment/payment **state machines** into the Model; those stay in Actions (DEC-015–018). Scopes are for **queries**.

### Alternatives considered

- Repository + interface for every model (rejected)
- Unbounded `where`/`join` chains in Actions (rejected)

### Reason

Oracle: skip Repository; use local scopes when queries get heavy.

### Consequences

- No `app/Repositories` in MVP
- `patterns/repository.md` is not to be copied into this codebase yet
- Report modules should grow scopes such as `scopeForReport($query, ...)` rather than a ReportRepository

### Related documents

- architecture/backend.md
- patterns/action.md
- patterns/repository.md
- .ai/rules/eloquent-scopes.md
- DEC-022

## DEC-024

Status: Accepted

Category: Backend

Date: 2026-09-02

Title: No domain events; audit written synchronously in Actions

### Context

MVP is synchronous (DEC-021) and has no product notifications (DEC-010). Domain Events would split audit into an implicit side channel. The Log Aktivitas module still needs a durable history of status, price, and assignment changes.

### Decision

Do **not** add application Domain Events or Listeners.

**Audit trail** for important changes (status, price, team assignment, and similar) is written **explicitly and synchronously inside the Action** that mutates the data — typically in the same DB transaction. Not event-driven, not observers, not queued.

### Alternatives considered

- Domain Event + Listener audit (rejected)
- Model Observer audit (rejected)
- Spatie activitylog via events (not chosen)

### Reason

Oracle: keep the flow in Actions; audit explicit in those Actions.

### Consequences

- An Action that changes shipment status/price/assignment without an audit write is incomplete
- `patterns/event.md` / `listener.md` must not be copied into `app/Events` / `app/Listeners` for this MVP
- Schema for the audit table is not fully specified (columns beyond “who/when/what” still Decision Required if needed)

### Related documents

- conventions/audit.md
- architecture/backend.md
- patterns/action.md
- patterns/event.md
- patterns/listener.md
- PROJECT.md
- GLOSSARY.md
- .ai/rules/audit-in-actions.md
- DEC-022

## DEC-025

Status: Accepted

Category: Frontend

Date: 2026-09-02

Title: Indonesian-only UI and framework locale (`id`)

### Context

Product language is Indonesian. The starter ships `APP_LOCALE=en` and English Fortify/Inertia copy. A bilingual switcher is out of MVP scope.

### Decision

**Bahasa Indonesia only** for all application UI copy. No bilingual feature.

Implementation **must** set:

* `.env`: `APP_LOCALE=id`, `APP_FALLBACK_LOCALE=id`
* `config/app.php`: `locale` and `fallback_locale` defaults to `id`

Framework messages (Form Request validation, Fortify/auth, pagination, Carbon date/time) **must** use Indonesian localization (`lang` files for `id`).

This `.ai/` pass does **not** edit `.env` or `config/app.php` (sandbox). Current code remains `en` until implementation.

### Alternatives considered

- Keep English starter strings (rejected)
- User-selectable `en`/`id` (rejected)

### Reason

Oracle: Indonesian is the only MVP language, including backend locale and framework strings.

### Consequences

- Publish/add Laravel `id` translations; do not leave English `validation.php` as the user-facing language
- Inertia pages in Indonesian
- `APP_FAKER_LOCALE` not mandated here

### Related documents

- TECH_STACK.md
- conventions/locale.md
- PROJECT.md
- .ai/rules/locale-id.md

## DEC-026

Status: Accepted

Category: Backend

Date: 2026-09-02

Title: Asia/Jakarta is SST for app, DB timestamps, audit, export

### Context

`config/app.php` ships `'timezone' => 'UTC'`. Mixing UTC storage with Jakarta display (or OS timezone) causes a **7-hour** error. Scheduling, audit, and Excel exports must not each pick a different zone.

### Decision

**`Asia/Jakarta`** in `config/app.php` is the **single source of truth** for the whole system: Eloquent `created_at`/`updated_at`, scheduling logic, audit trail timestamps, data export, UI, and Action time comparisons.

Do not mix UTC and WIB. Do not use the machine/OS timezone as a second source.

`config/app.php` is still `UTC` until implementation (`.ai/` sandbox does not edit it).

### Alternatives considered

- Laravel default UTC-in-database + convert on display (rejected — Oracle wants one zone everywhere, including DB timestamps)
- Per-user timezones (rejected)

### Reason

Oracle: Jakarta as SST to avoid a 7-hour skew.

### Consequences

- Carbon/Excel/MySQL datetime columns are Jakarta wall time as configured by the app timezone
- Tests for scheduling must not assume UTC
- Agents must not call `->utc()` or `Carbon::now('UTC')` for product timestamps

### Related documents

- TECH_STACK.md
- conventions/timezone.md
- conventions/audit.md
- conventions/export.md
- architecture/data.md
- .ai/rules/timezone-jakarta.md
- PROJECT.md

## DEC-027

Status: Accepted

Category: Other

Date: 2026-09-02

Title: APP_NAME is Logistik (for now)

### Context

`.env` still has `APP_NAME=Laravel`. The project name in `PROJECT.md` is Logistik. Oracle: use Logistik **for now** (may change later).

### Decision

Set application display name to **Logistik** (`APP_NAME` / `config('app.name')`). Not the starter `Laravel`.

This is **not** immutable baseline metadata (unlike DEC-012). A later Oracle instruction may rename it.

`.env` is not edited in this `.ai/` pass.

### Alternatives considered

- Keep `Laravel` (rejected)

### Reason

Oracle confirmed Logistik as the current name.

### Consequences

- Titles, mail from-name, and UI that use `config('app.name')` show Logistik after env is updated
- Do not treat this as frozen the way baseline version/date are

### Related documents

- PROJECT.md
- TECH_STACK.md
- DEC-012

## DEC-028

Status: Accepted

Category: Workflow

Date: 2026-09-02

Title: Close interview; mvp-lock.md is the implementation brief

### Context

Canonical `PROJECT.md` / `TECH_STACK.md` placeholders are filled. Oracle stopped the interrogation loop and asked for a harvested knowledge base before source implementation.

### Decision

**Close** the adaptation interview. Canonical MVP lock lives in [`knowledge/mvp-lock.md`](./knowledge/mvp-lock.md), with DEC-001–027 as the trace.

The next Oracle message may authorize **source** changes (currently still starter-only). Until then, do not invent modules or reopen locked DECs.

### Alternatives considered

- Keep interviewing leftover details (audit column list, faker locale) — deferred

### Reason

Oracle: MVP core is complete; harvest and wait for implementation.

### Consequences

- Agents read `mvp-lock.md` + this log before coding
- `.ai/HOW_TO_ADAPT.md`, `README.md`, `AGENTS.md`, `RULES.md` were not rewritten in this pass

### Related documents

- knowledge/mvp-lock.md
- knowledge/overview.md
- knowledge/where-to-edit.md
- PROJECT.md
- TECH_STACK.md

## DEC-029

Status: Accepted

Category: Architecture

Date: 2026-09-02

Title: Pragmatic Modular Monolith + Progressive Architecture

### Context

The baseline recommended a layered modular monolith (presentation → application → domain → persistence → infrastructure), with Repository and Domain Events as default communication. DEC-022–024 already require thin controllers, HTTP-agnostic Actions, Eloquent without a Repository, and explicit audit in Actions. Product capabilities in `PROJECT.md` must stay visible to stakeholders, but they are not eleven isolated code packages. The team needed one architecture style that is simple for MVP, enforces today’s invariants, and leaves escape hatches without prescribing Clean Architecture / DDD folders.

### Decision

This product uses **Pragmatic Modular Monolith + Progressive Architecture**.

**Default request flow:**

```text
Inertia Page / Controller
        ↓
Form Request + Policy
        ↓
Action / Use Case
        ↓
Eloquent Model
        ↓
Database
```

Do not introduce extra layers by default.

**Intentionally avoided as MVP defaults** (not a lifetime ban; a later DEC may enable a specific hatch):

* Repository interfaces and Eloquent repository wrappers
* Five-layer Clean Architecture / tactical DDD folder trees
* `app/Modules/*` or per-domain package providers
* Domain Events / Listeners as the default for core state changes
* Generic catch-all Service classes
* CQRS, event sourcing, microservices
* Full Feature-Sliced Design (`widgets` / `entities` / `processes`) on the frontend
* Speculative directories such as `app/Domain` or `app/Queries` created “for later”

**Progressive extraction:** an Action orchestrates a use case. A reusable or complex business invariant may be extracted into a cohesive dedicated class when justified by complexity, criticality, readability, duplication, reuse, testability, or the risk that the invariant will scatter. Duplication across Actions is one signal, not a mechanical rule. A complex rule used by one Action may be extracted; a trivial rule used twice need not be. The class location follows responsibility and existing conventions when the need appears (for example `app/Support/…` or `app/Rules/…`). Do not treat `app/Domain` as the default future home.

**Allowed future escape hatches** (build only when the problem is real, then record a DEC if the default flow changes):

* Dedicated invariant classes as above
* Query objects when list/report/availability queries become unreadable as local scopes
* Events / queues for proven asynchronous, non-critical side effects (notifications, large export, webhooks)
* Stronger module boundaries if a capability gains a real lifecycle of its own

Product capabilities in `PROJECT.md` remain the business vocabulary. They are **not** equivalent to architectural bounded contexts or code packages. MVP implementation grouping may collapse workflow around Shipment without deleting those capabilities.

DEC-021–024 stay Accepted. This decision clarifies how to grow; it does not reopen Repository, Domain Events, or queue workers as MVP defaults.

### Alternatives considered

- Keep the baseline five-layer diagram and Repository/Event defaults (rejected: fights DEC-022–024 and over-structures a single internal-ops workflow)
- Hard modular monolith (`app/Modules/*`, events between packages) (rejected: Shipment is the workflow hub; isolation would force premature events)
- Full Feature-Sliced Design on frontend and backend (rejected: FSD is a frontend methodology; it fights Inertia `pages/` and Laravel `app/` conventions)
- Fat controllers or “Action + Eloquent” as dogma with no extraction path (rejected: invariants would duplicate or hide in unreadable Actions)

### Reason

Oracle approved this style after architecture audit: simplest structure that enforces today’s rules, with explicit hatches for proven complexity, without locking the repo into DDD folder dogma.

### Consequences

- Agents follow the default flow; they do not scaffold Repository, Domain, Modules, or Events “to be ready”
- `architecture/principles.md`, `architecture/backend.md`, `AGENTS.md`, and `RULES.md` must match this decision
- Operational details (transactions, concurrency, POD file/DB consistency, mass assignment, tests) live in architecture/conventions/rules, not in this DEC
- Enabling a hatch that changes the default flow requires a new DEC; using an extracted class inside an Action does not

### Related documents

- architecture/README.md
- architecture/principles.md
- architecture/backend.md
- AGENTS.md
- RULES.md
- TECH_STACK.md
- DEC-021
- DEC-022
- DEC-023
- DEC-024
- PROJECT.md

## DEC-030

Status: Accepted

Category: Security

Date: 2026-09-20

Title: Separate authorization roles from operational workforce classification

### Context

DEC-009 replaced the earlier stored value `superadmin` with `owner` and treated `sopir` and `petugas_lapangan` as authorization roles. The owner/client has now explicitly superseded that role model. The application still has no role column, enum, Policies, Gates, or workforce-classification schema, so this decision changes the intended contract only and does not describe implemented behavior.

### Decision

The canonical stored authorization roles are:

* `superadmin` — distinct highest-privilege role with full access to every application module, data set, and action
* `owner` — distinct role below `superadmin`; exact abilities and data scope are not yet defined
* `admin` — distinct role; exact abilities and data scope are not yet defined
* `karyawan` — base internal-employee role; exact abilities and data scope are not yet defined

`superadmin` is not an alias for `owner`. The sequence above expresses business meaning only; implementation must not derive permissions through numeric role comparison or implicit role inheritance. Except for approved full access by `superadmin`, abilities must be defined explicitly and enforced server-side through Laravel Gates/Policies after business approval.

`sopir` and `petugas_lapangan` are no longer authorization roles. They are operational/workforce classifications associated with employees. No storage shape is selected: do not assume a column, enum, master table, position/category table, pivot, cardinality, or separate employee entity.

DEC-004 remains accepted: the first account is bootstrapped through a seeder rather than public registration. Under this revised role model, that highest-access bootstrap account uses `superadmin`; credential delivery and recovery mechanics remain unresolved.

### Supersedes

This decision supersedes only the **UserRole portion** of DEC-009, including:

* `owner` as the stored superadmin value
* `sopir` and `petugas_lapangan` as roles
* the consequence that the first seeded user stores `owner`

DEC-009 remains accepted for `ShipmentStatus`, `PaymentStatus`, and `PaymentMethod`. DEC-005 remains accepted for a string `users.role` plus Laravel Gates/Policies and no Spatie.

### Questions open at the time (resolved or narrowed by DEC-031)

1. Can one employee have only one operational classification, or multiple classifications simultaneously?
2. Is every operational employee required to have a login account?
3. Can `owner` manage or create another `owner`?
4. Can `owner` create or manage `admin` and `karyawan`?
5. What exact permissions does `owner` have below `superadmin`?
6. What exact permissions does `admin` have?
7. What can `karyawan` access by default, and with what data scope?
8. How does operational classification affect shipment, assignment, and task permissions?
9. What account deactivate/delete rules apply?
10. What `owner`/`superadmin` protection rules are required?
11. What password recovery and bootstrap-credential flow is required?

DEC-031 records the approved answers. Module-specific resource permissions and implementation-design details remain open as stated there.

### Consequences

* Current documentation uses only `superadmin`, `owner`, `admin`, and `karyawan` as authorization roles.
* `sopir` and `petugas_lapangan` may appear only as operational classifications or in clearly historical text.
* No `UserRole`, migration, classification schema, Policy, Gate, or Fortify change may be inferred from this documentation-only decision.
* DEC-031 now defines generic User & Access boundaries; future module contracts still define module-specific `owner` reads and `karyawan` actions/scopes.

### Related documents

* PROJECT.md
* GLOSSARY.md
* TECH_STACK.md
* architecture/security.md
* knowledge/mvp-lock.md
* DEC-004
* DEC-005
* DEC-009

## DEC-031

Status: Accepted

Category: Security

Date: 2026-09-20

Title: Lock User & Access lifecycle, authority, recovery, and workforce contract

### Context

DEC-030 established the four authorization roles and separated operational function from authorization, but intentionally left lifecycle, generic abilities, recovery, bootstrap security, and workforce cardinality open. The owner/client approved D-01 through D-11 before implementation.

### Decision

**Superadmin:** full-access semantics from DEC-030 remain. Superadmin is not managed in ordinary application UI; provisioning/management uses a secure operational mechanism. The last active superadmin cannot be deactivated, deleted, or demoted.

**Owner:** business oversight only—relevant history, business/operational data, reports, analytics, and monitoring. Owner has no User Management authority and no unrestricted read access to security-sensitive/system data. Exact reads are decided per future module.

**Admin:** may view, create, edit, deactivate, and reactivate `karyawan`. Admin cannot manage `superadmin`, `owner`, or another `admin`.

**Karyawan:** may manage their own non-sensitive profile, change their password while authenticated with current-password verification, and manage their own sessions when an approved UI supports it. They cannot change their role/login identity or delete their account. Business access is limited to explicitly given/assigned resources under future module rules.

**Account lifecycle:** active/deactivated with authorized reactivation. Deactivated accounts cannot act as active application users. No hard delete and no self-delete; preserve historical/audit attribution.

**Authentication and recovery:** login identifier is unique email. There is no public/self-service forgot-password. Normal authenticated password change remains available. Admin may perform internal recovery/reset for `karyawan` only; old passwords are never readable, and recovery is auditable once audit capability exists. Higher-role and emergency superadmin recovery require a secure privileged/operational mechanism whose implementation is not yet approved.

**Bootstrap:** initial `superadmin` uses the approved seeder approach with required environment/deployment secrets. No committed production default or predictable fallback. Exact environment variable names are implementation design.

**Employee and operational function:** every employee represented in the MVP has exactly one User account; no non-login personnel representation is required. This does not automatically approve a separate Employee model/table. Every employee has exactly one operational function. Operational functions are business-managed master data, initially including `sopir` and `petugas_lapangan`, and may grow without changing `UserRole`. Operational function is never an authorization role.

### Consequences

* `owner` cannot manage users; `admin` User Management and recovery stop at `karyawan`.
* Policies express explicit abilities; no numeric inheritance and no authorization by operational function.
* Existing starter self-delete, hard-delete behavior, public reset, and unrestricted active-user assumptions are implementation gaps.
* Operational-function schema is not a fixed enum decision; schema and CRUD lifecycle remain for implementation design.

### Still unresolved

* Exact `owner` read access per future business module
* Exact `karyawan` Shipment/Assignment actions and resource-assignment rules
* Employee persistence structure and operational-function schema/table/fields/lifecycle/UI
* Higher-role forgotten-password recovery and emergency superadmin recovery implementation
* Detailed audit schema

### Related documents

* PROJECT.md
* GLOSSARY.md
* TECH_STACK.md
* architecture/security.md
* knowledge/mvp-lock.md
* DEC-004
* DEC-005
* DEC-030
