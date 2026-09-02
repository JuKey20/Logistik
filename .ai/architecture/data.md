# Data Architecture

Canonical location for data flow, contracts, and persistence. Migration policy stays in `RULES.md` and `conventions/database.md`.

---

# Principles

* One source of truth per fact
* Input is treated as untrusted until validated
* Stable contracts between backend and frontend
* Each mutation changes only the fields that use case owns (not “whatever was validated”)
* Do not invent entities to make the diagram tidier

---

# Lifecycle

```text
Input
  → Form Request validation + Policy
  → Action (array, primitives, or optional DTO)
  → Eloquent
  → Inertia props or explicit serializer
  → client types
```

DTO is **optional** (DEC-022). Validated arrays/primitives are allowed. Do not require `app/DTO` for every use case.

A Form Request `validated()` payload is **not** a license to mass-assign. The Action/use case sets only the attributes that belong to that operation. Critical fields (`role`, shipment status, payment status, assigned user, assigned vehicle, audit columns, ownership) must not change just because they appeared in a generic payload.

---

# Ownership

| Data | Owner |
| --- | --- |
| Fixed domain values (status, type, role, method) | Enum, not option tables |
| Dynamic records | Database + owning capability |
| Payment (MVP) | Exactly one per shipment (DEC-017) |
| Money | Whole IDR integers; DB `BIGINT` or `DECIMAL(p,0)`; never float (DEC-019) |
| Time | `Asia/Jakarta` only (DEC-026) |
| Inertia page data | Controller props |
| Async JSON (if used) | Explicit serializer; envelope in [`../conventions/response.md`](../conventions/response.md) is **deferred**, not adopted |
| Frontend types | Feature `types/` matching the server contract |

UI option lists for fixed values come from the enum (or the same source the backend validates), not from a sync table.

Product capabilities in `PROJECT.md` are not table packages. MVP code may persist payment, assignment, POD metadata, and audit next to the shipment workflow without creating a Repository per capability.

---

# Unresolved product data (Decision Required)

The locked PRD names customers (korporat/individu), cabang as a B2B destination, pickup/destination as part of a shipment, and does **not** lock a data model for:

* customer vs sender vs recipient
* one customer with many locations vs locations owned by the shipment
* a dedicated Address entity

Do **not** create an Address (or similar) abstraction without a product decision. Do **not** silently treat Customer as sender and destination.

---

# JSON contract

Inertia **pages** do not use a REST envelope.

A shared JSON envelope is **not** adopted for MVP. If a later endpoint needs a stable JSON shape, follow [`../conventions/response.md`](../conventions/response.md) only after a DEC. Do not scaffold `resources/js/api/` for an envelope client.

---

# Dates, files, lists

* Serialize timestamps in ISO-8601 (wall time is `Asia/Jakarta`, DEC-026).
* Do not put storage paths or unsigned public URLs in JSON for private files. [`../patterns/attachment.md`](../patterns/attachment.md)
* Paginate unbounded lists.
* Eager-load relations that the response will output.

Database and filesystem are **not** one atomic transaction. File/DB compensation: [`../conventions/storage.md`](../conventions/storage.md).

---

# Persistence

Follow the environment-aware database policy in [`../RULES.md`](../RULES.md).

Operational SOP: [`../conventions/database.md`](../conventions/database.md)

Transaction principle: [`../conventions/transactions.md`](../conventions/transactions.md)

---

# Related Documents

* [backend.md](./backend.md)
* [security.md](./security.md)
* [../patterns/dto.md](../patterns/dto.md)
* [../patterns/resource.md](../patterns/resource.md)
* [../conventions/transactions.md](../conventions/transactions.md)
