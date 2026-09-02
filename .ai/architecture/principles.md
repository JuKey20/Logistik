# Architecture Principles

Canonical location for philosophy, default flow, dependency direction, and the difference between product capabilities and code packages.

Style: **DEC-029**.

---

# Philosophy

* Separation of concerns
* Single responsibility
* Business first
* Modularity without premature packages
* Low coupling, high cohesion
* Convention over configuration
* Explicit over implicit
* Documentation first
* Simplicity before new layers
* Progressive extraction when complexity is real

Guiding sentence: build the simplest architecture that safely enforces today's business rules, while leaving clear escape hatches for tomorrow's proven complexity.

---

# Default flow (Required)

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

HTTP adapters must not import as a place to hide business rules. Actions must not import HTTP types (`request()`, `response()`, `redirect()`, session).

This is **not** a five-layer Clean Architecture stack. There is no default Persistence layer of repositories, no default Domain folder, and no default Infrastructure ports.

---

# Product capabilities vs architectural modules

`PROJECT.md` lists **product capabilities** (menus / business areas) such as User, Customer, Vehicle, Shipment, Scheduling, Assignment, Payment, POD, Dashboard/Report, Activity Log, and My Tasks.

Those names are for stakeholders and navigation. They are **not** equivalent to architectural bounded contexts, Composer packages, or `app/Modules/*`.

For MVP implementation, code may group around:

```text
User
Customer
Vehicle
Shipment workflow
```

Payment, assignment, POD, scheduling, audit, reporting, and My Tasks may live in the Shipment workflow in code without deleting the product capabilities.

On the current MVP scope, Customer and Vehicle are treated as reference data because they do not yet have a lifecycle or invariants complex enough to justify their own domain boundary. That wording is **scope**, not a permanent classification. If Vehicle later gains maintenance, availability, capacity, document validity, or operational status — or Customer gains multiple locations, corporate PIC, pricing agreements, credit terms, or billing — extract a stronger boundary then. Do not implement those features now. Do not invent Address or similar entities without a product decision.

---

# Progressive extraction

An Action orchestrates the use case. Business invariants must not scatter or silently duplicate.

Extract a cohesive dedicated class when justified by some combination of:

* complexity
* criticality
* readability
* duplication
* reuse
* testability
* risk that the invariant will spread

Duplication across Actions is one signal, not a quota. A complex rule used by a single Action may be extracted. A trivial rule used twice need not become a class.

Place the class where responsibility and existing conventions fit at the time of need. Do **not** prescribe `app/Domain` as the future default (that path pulls the project toward DDD folder dogma). Do not create extraction folders speculatively.

---

# Frontend / backend split

* Backend owns authorization, validation, persistence, and business invariants.
* Frontend owns presentation, interaction, and client-side UX state.
* Server state is not duplicated as global UI state without a reason.
* Frontend structure: Inertia `pages/` plus `features/` as needed — not full Feature-Sliced Design. See [`frontend.md`](./frontend.md).

---

# When architecture may change

Stop and write a `DEC-XXX` before:

* Changing the default request flow
* Allowing Actions to depend on HTTP
* Introducing Repositories, module packages, or Events as the default spine
* Sharing a table across capabilities without an owner
* Creating speculative top-level `app/` trees “for later”

---

# Related Documents

* [backend.md](./backend.md)
* [frontend.md](./frontend.md)
* [../DECISION_LOG.md](../DECISION_LOG.md) (DEC-029)
* [../RULES.md](../RULES.md)
