# Transactions, concurrency, duplicate submission

Operational rules for database transactions. Architecture principle: [`../architecture/backend.md`](../architecture/backend.md). Style: DEC-029.

This file does **not** invent product rules (for example driver overlap). When an invariant is unlocked, implement it with the mechanisms below.

---

# When to use a transaction

Use `DB::transaction()` when:

* several database operations must succeed or fail as **one atomic business change**, or
* consistency / concurrency needs a transaction or lock (including some **single-table** writes).

Do **not** use “writes more than one table” as the primary rule. Do not wrap every Action in a transaction by default.

The Action owns the boundary. Models and repositories (none in MVP) must not start the business transaction.

Audit rows for a mutation belong in the same database transaction as that mutation when the audit is part of the business change (DEC-024).

---

# Typical MVP operations (guidance)

Atomic as one business change when implemented (database side only):

| Operation | Why atomicity matters |
| --- | --- |
| Create shipment (and related payment placeholder / audit if those rows exist) | No shipment without the paired records the schema requires |
| Assign crew / vehicle (and status / audit if part of the same use case) | No half-applied dispatch |
| Status transition + audit | No unaudited or illegal status |
| Record payment (method, actual, tip, actor, status) + audit | No lunas without complete payment facts |
| Cancel + audit | Terminal status and history together |
| POD **metadata** + related status/audit | Database facts stay together |

Filesystem work is **outside** this atomicity. See [`storage.md`](./storage.md).

---

# Concurrency

Assignment of driver/crew/vehicle is a **read-check-write** risk if two admins can dispatch the same resource.

Do **not** use pessimistic locking (`lockForUpdate` and similar) as the default on every write.

Use a database constraint, a transaction plus **re-validation inside the transaction**, locking, or another concurrency mechanism **when a race can be shown** from:

* a locked business invariant,
* a read-check-write pattern in the use case,
* a concurrency test, or
* a clear operational need.

Do not wait for a production incident if the race is already visible in the design. Do not add speculative locking without a clear invariant.

Frontend “available” flags and `if ($driver->isAvailable())` **outside** a transaction are not sufficient by themselves.

---

# Duplicate submission protection

For this MVP (internal Inertia, no payment gateway / webhook / offline client):

* backend state validation
* legal status / payment transitions only
* database constraints where they match a real unique business fact
* UI submit protection as **UX**
* transaction / concurrency protection as above

Do **not** add an idempotency-key table, request fingerprint store, distributed-cache idempotency, or generic idempotency middleware unless a later requirement introduces external retries (API, webhook, gateway, offline client).

---

# Related Documents

* [../architecture/backend.md](../architecture/backend.md)
* [audit.md](./audit.md)
* [storage.md](./storage.md)
* [shipment-status.md](./shipment-status.md)
* [payment-status.md](./payment-status.md)
