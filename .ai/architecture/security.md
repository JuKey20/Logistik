# Security Architecture

Canonical location for authentication, authorization, secrets, and trust boundaries.

---

# Principles

* Deny by default
* Validate on the server
* Authorize on the server
* Least privilege
* Do not leak internals to clients
* UI permission is not a security boundary

---

# Authentication

Mechanism: Laravel Fortify + session web; login/logout/sesi only. Canonical: [`../TECH_STACK.md`](../TECH_STACK.md) (DEC-004).

Login identifier: **unique email**. Do not require email verification, 2FA, passkeys, public registration, or public/self-service forgot-password.

An authenticated user may change their own password with current-password verification and normal security validation. This is distinct from forgotten-password recovery.

Internal forgotten-password recovery is privileged: `admin` may reset access for `karyawan` only. An admin may not reset credentials for another `admin`, `owner`, or `superadmin`. Passwords are never readable or displayed. Recovery must be auditable when audit capability exists. Higher-role and emergency superadmin recovery require a secure privileged/operational mechanism whose exact implementation is not yet approved.

The first user is seeded with the distinct `superadmin` role. Bootstrap credentials come from required environment/deployment secrets; no committed default or predictable fallback is allowed. Exact environment variable names remain implementation design (DEC-031).

Protected routes require an authenticated session. Unauthenticated JSON calls must not receive stack traces or model dumps.

**Perilaku kode saat ini:** Fortify features and `verified` middleware are still on in the starter (`config/fortify.php`, `routes/web.php`). That is an implementation gap, not a product change.

---

# Authorization

**Required:** every protected endpoint is authorized on the server.

**Chosen (DEC-005):** `users.role` (string) + Laravel Gates & Policies. Not Spatie.

```text
Policy / Form Request authorize / Gate  →  may this actor do this?
Action                                  →  business mutation of allowed fields only
Frontend                                →  hide chrome; never the only control
```

Canonical field: [`../TECH_STACK.md`](../TECH_STACK.md). Authorization roles: `superadmin`, `owner`, `admin`, `karyawan` (DEC-030).

`superadmin` has full access to all application modules, data, and actions. Do not implement numeric hierarchy comparison or implicit permission inheritance.

| Role | Generic User & Access boundary |
| --- | --- |
| `superadmin` | Full application access. Not managed through ordinary UI; provisioning/management uses a secure operational mechanism. The last active superadmin cannot be deactivated, deleted, or demoted. |
| `owner` | Business oversight: relevant history, business/operational data, reports, analytics, and monitoring. No User Management authority. Not unrestricted access to security-sensitive/system data; exact read access belongs to each future module contract. |
| `admin` | May view, create, edit, deactivate, and reactivate `karyawan`; may perform internal password recovery for `karyawan`. Cannot manage or recover `superadmin`, `owner`, or other `admin` accounts. |
| `karyawan` | May manage own non-sensitive profile, change own password while authenticated, and manage own sessions when supported. Cannot change own role/login identity or delete own account. Business access is limited to resources explicitly given/assigned by future module rules. |

Detailed Shipment/Assignment actions and assignment scope are not defined here.

## Account lifecycle

Accounts have active/deactivated states. Deactivated accounts are not active application users and must not authenticate or exercise application authorization. An authorized manager may reactivate an account within the boundaries above. Hard delete and self-delete are not allowed; historical/audit attribution must remain intact.

## Employee and operational function boundary

Every employee represented by the MVP has exactly one User account; no non-login personnel model is required. This conceptual 1:1 contract does not by itself approve a separate Employee table/model.

Every employee has exactly one operational function. `sopir` and `petugas_lapangan` are initial business-managed master-data values; future functions may be added without redefining authorization roles. Operational function must never substitute for `UserRole` or server-side authorization. Exact employee persistence and operational-function schema/CRUD lifecycle remain implementation-design decisions.

Canonical enforcement pattern: [`../patterns/policy.md`](../patterns/policy.md)

---

# Validation

Untrusted input is validated on the backend before the use case runs.

Frontend schema validation (including React Hook Form + Zod) improves UX. It must not be the only check. Hidden fields, disabled buttons, and React state are not trusted.

Cross-field invariants belong next to validation (for example `after()` on a Form Request) or in the use-case SSOT — not scattered through UI code.

---

# Mutations and sensitive fields

Each use case accepts and persists **only the fields it owns**.

Do not `$model->fill($request->all())`. Do not treat `$request->validated()` as a complete write set if the request rules are broader than the operation.

These must not change through a generic payload:

* `role`
* shipment status
* payment status
* assigned user / crew
* assigned vehicle
* audit columns
* ownership / foreign keys the use case is not meant to retarget

Status and assignment changes go through the dedicated Action for that operation.

---

# Secrets and sensitive data

* No passwords, tokens, API keys, or connection strings in documentation or source
* Serializers omit secret columns (`password`, remember tokens, raw storage keys)
* Logs record identifiers, not credentials or file contents
* Bukti pengiriman: private disk only (DEC-014). MVP download path: authenticated route + Policy + stream. See [`../conventions/storage.md`](../conventions/storage.md)

---

# Trust boundary

The browser is untrusted. Knowing an ID or URL is not authorization.

---

# Related Documents

* [../RULES.md](../RULES.md)
* [../patterns/policy.md](../patterns/policy.md)
* [../patterns/request.md](../patterns/request.md)
* [../TECH_STACK.md](../TECH_STACK.md)
* [../conventions/storage.md](../conventions/storage.md)
