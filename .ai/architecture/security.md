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

Do not require email verification, 2FA, passkeys, public registration, or self-serve password reset. First user: seeder with role `owner` (superadmin).

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

Canonical field: [`../TECH_STACK.md`](../TECH_STACK.md). Roles: `owner`, `admin`, `sopir`, `petugas_lapangan` (DEC-009).

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
