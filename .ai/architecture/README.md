# Architecture

> How the system is structured. Constraints stay in `RULES.md`. Technology choices stay in `TECH_STACK.md`. Style: **DEC-029**.

| File | Owns |
| --- | --- |
| [principles.md](./principles.md) | Philosophy, default flow, product vs code modules, extraction |
| [backend.md](./backend.md) | HTTP, Action, Eloquent, transactions principle, anti-patterns |
| [frontend.md](./frontend.md) | Feature-based UI (not full FSD), communication, visual principles |
| [data.md](./data.md) | Data flow, contracts, persistence |
| [security.md](./security.md) | Authentication, authorization, secrets, validation |

Read this index, then only the files that match the change.

Do not duplicate rules here that already exist in `RULES.md`.

---

# Style

**Pragmatic Modular Monolith + Progressive Architecture** is the default (DEC-029).

Default flow:

```text
Inertia Page / Controller → Form Request + Policy → Action → Eloquent → Database
```

Change the default flow only with a new `DEC-XXX` and an update to `TECH_STACK.md`. Extracting a cohesive invariant class inside an Action does not require a new DEC.

Operational details (concurrency, POD file/DB consistency, per-use-case transactions, mass assignment, testing) live in the matching architecture, convention, or rule files — not in DEC-029.

---

# Related Documents

* [../RULES.md](../RULES.md)
* [../TECH_STACK.md](../TECH_STACK.md)
* [../DECISION_LOG.md](../DECISION_LOG.md) (DEC-029)
* [../patterns/](../patterns/)
* [../knowledge/README.md](../knowledge/README.md)
