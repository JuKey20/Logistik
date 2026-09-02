# Backend Architecture

Canonical location for backend boundaries under **DEC-029**. This project does not use a Repository layer (DEC-023).

Examples assume Laravel 13 (lihat `TECH_STACK.md`).

---

# HTTP adapter

Controllers (or equivalent):

* Validate via Form Request (or equivalent)
* Trigger authorization (Policy/Gate)
* Pass **arrays or primitives** into an Action (DTO optional)
* Return a Response or Redirect

They must not query the database, encode business rules, or own business transactions.

**Required (DEC-022):** Thin Controller. Orchestration, state changes, and transaction *control* live in `app/Actions/`.

**Shipment status:** illegal transitions are business rules. Enforce in the use-case layer (DEC-015, [`../conventions/shipment-status.md`](../conventions/shipment-status.md)), not only in the UI. Do not set status through a generic model `update` from a controller or a catch-all payload.

**Payment status:** separate machine (DEC-016–018). Independent from shipment cancellation. `sudah_dibayar` requires method, actual nominal **>= agreed price**, actor, and auto tip for overpay. Underpayment is rejected. Frontend calculation is not authority.

**Assignment:** one official business path. Eligibility and conflict rules belong in that path (and in an extracted class if extraction is justified). The UI is UX only.

**Recommended:** separate page controllers from JSON controllers when the project uses both Inertia pages and asynchronous JSON.

---

# Use case layer — Action (**Required**, DEC-022)

An Action is one business operation. **HTTP-agnostic.**

**Must not** use `request()`, `response()`, `redirect()`, session helpers, or `Illuminate\Http\Request` / `Response` types.

Public method: `handle()` (domain Actions). Constructor injection. Input: array or primitives (DTO optional).

The Action **orchestrates** the use case. It is not a dogma that every line of invariant must live inside the Action forever. See progressive extraction in [`principles.md`](./principles.md).

Fortify classes under `app/Actions/Fortify/` follow Fortify contracts (exception).

Details: [`../patterns/action.md`](../patterns/action.md)

---

# Persistence — Eloquent in Actions (**MVP**, DEC-023)

**No Repository layer** in MVP. Actions may call Eloquent (and related models) directly.

**Local scopes on the Model** when a query would otherwise be a long chain: multi-condition filters, report aggregations, joins, or similar. The Action should read as `Shipment::query()->forDashboard($filters)->...`, not a stack of `where`/`join`/`selectRaw`.

Do **not** add `app/Repositories` + interfaces that wrap `create()`/`find()`.

If a query later becomes unreadable as a scope, a **query object** is an allowed escape hatch (DEC-029). Create it when that complexity exists. Do not create `app/Queries` speculatively. A query object is not a Repository.

The Repository pattern file remains for a later phase: [`../patterns/repository.md`](../patterns/repository.md)

---

# Supporting types

| Type | Role | Canonical pattern |
| --- | --- | --- |
| Form Request (or equivalent) | Authorize + validate | [`../patterns/request.md`](../patterns/request.md) |
| DTO | Optional typed data between layers | [`../patterns/dto.md`](../patterns/dto.md) |
| API Resource (or equivalent) | Explicit serialization when JSON is used | [`../patterns/resource.md`](../patterns/resource.md) |
| Policy | Server-side authorization | [`../patterns/policy.md`](../patterns/policy.md) |
| Enum | Fixed domain values | [`../patterns/enum.md`](../patterns/enum.md) |
| Domain event | **Deferred** until a proven async/non-critical side effect needs it. Audit is explicit in the Action (DEC-024) | [`../patterns/event.md`](../patterns/event.md) |

Do not require a DTO on every Action (DEC-022).

---

# Transactions

The use-case layer owns the transaction boundary. Persistence must not start a business transaction on its own.

**Rule:** use a database transaction when several database operations must be **one atomic business change**, or when consistency / concurrency needs a transaction or lock.

Table count is **not** the primary test. A single-table write may still need a transaction (for example locking and re-validation). Do not wrap every Action in a transaction by default.

Per-operation mapping, concurrency tactics, and POD file/DB compensation live in conventions (not in DEC-029).

**Audit trail (DEC-024):** persist activity history (status, price, team assignment, and other important changes) **in the same Action**, synchronously, typically in the same database transaction as the business write. Do not dispatch events, queued jobs, or model observers for this.

---

# Anti-patterns

* Fat controller
* Fat model that runs workflows (local **query** scopes are fine; status machines stay in the use-case path)
* Service class that becomes a second application with every method on one type
* Hidden container lookups instead of constructor injection
* Duplicating the same business rule in HTTP, use case, and UI
* Domain Events / Listeners / Observers for audit trail (DEC-024)
* Speculative `app/Domain`, `app/Modules`, or Repository trees
* Treating product capability names as required code packages

---

# Related Documents

* [principles.md](./principles.md)
* [data.md](./data.md)
* [../conventions/transactions.md](../conventions/transactions.md)
* [../RULES.md](../RULES.md)
* [../DECISION_LOG.md](../DECISION_LOG.md) (DEC-029)
