# RULES

> Mandatory constraints. Recommended patterns live in `architecture/` and `patterns/`. Do not copy recommendations into this file as if they were Required.

---

# Core Principles

* Business first
* Consistency over cleverness
* Simplicity first
* Readability first
* Documentation first
* Security by default
* Testability
* Maintainability
* Avoid over-engineering

---

# Golden Rules

1. Do not assume. Ask when the requirement is unclear.
2. Do not change business rules without approval.
3. Do not remove existing behavior without a clear instruction.
4. Do not perform a large refactor while fixing a small bug.
5. Every change needs a reason.
6. Update documentation when behavior changes.
7. Consistency beats introducing a new pattern for one screen.

---

# Architecture Constraints

Default shape (**Required**, DEC-029):

```text
Inertia Page / Controller → Form Request + Policy → Action → Eloquent → Database
```

Controllers (or equivalent HTTP adapters) must not contain:

* Business rules
* Database queries
* Ad-hoc validation that belongs in the project validation layer
* Complex data mapping

Business invariants live in the use-case (Action) or in a cohesive dedicated class extracted when justified (DEC-029). Persistence stores and queries; it does not own the business process.

Models remain data representations (relations, casts, query scopes). They must not become a second workflow layer (no status machines on the model).

Do not add layers or folders because they are “best practice.” Each abstraction must solve a present problem.

---

# Action Pattern (**Required** for product domain mutations, DEC-022)

An Action orchestrates one business operation. HTTP-agnostic. Required for shipment, payment, assignment, POD, and user/role mutations.

Fortify contract Actions under `app/Actions/Fortify/` are framework adapters.

Starter settings CRUD may stay thinner until it gains domain invariants.

Details: [`patterns/action.md`](./patterns/action.md), [`architecture/backend.md`](./architecture/backend.md)

---

# Persistence (DEC-023)

**Do not** implement Repositories in this project unless a later DEC says otherwise.

Actions may call Eloquent directly. Complex reads start as **local scopes** on the model.

If a query later becomes unreadable as a scope, a query object is an allowed escape hatch (DEC-029). That is not a Repository layer. Do not create `app/Queries` speculatively.

Details: [`patterns/repository.md`](./patterns/repository.md) (deferred; do not copy into the codebase)

---

# Validation

Backend validation is **Required** for every untrusted input.

Frontend validation is UX only. It does not replace backend validation.

---

# Authorization

Server-side authorization is **Required** on every protected endpoint.

Implementation model: `users.role` string + Laravel Gates & Policies (DEC-005). Canonical: [`TECH_STACK.md`](./TECH_STACK.md).

Frontend checks may hide buttons and menus. They are not a security boundary.

---

# Security

Required:

* Authentication on protected routes
* Authorization on the server
* Validation and sanitization of input
* No secrets in source control
* No `env()` (or equivalent) calls from application runtime code when the framework provides config

Do not return stack traces to clients.

---

# Database Policy

Classify the environment **before** any destructive database operation:

| Environment | Destructive reset (`migrate:fresh`, `db:wipe`, truncate-all, drop database) |
| --- | --- |
| Production | **Forbidden** |
| Production-like / Staging | **Forbidden** |
| Local development | Allowed when needed for development |
| CI / Test | Allowed when needed for tests |

Production and Production-like schema history is **append-only**:

* Do not edit, delete, rename, or reorder committed migrations that already ran.
* Schema change = a new migration.
* Prefer additive changes over destructive ones.
* Transform existing data with a dedicated migration, command, or job — do not require wiping data.
* Destructive schema changes need an explicit impact, risk, rollback, and approval.

Before creating a migration: confirm the change is necessary and does not duplicate existing schema.

Details: [`conventions/database.md`](./conventions/database.md)

---

# API

If the project exposes JSON endpoints, use one explicit response contract. Do not return ORM models directly.

Recommended envelope (not Required unless the project adopts it): see [`conventions/response.md`](./conventions/response.md)

---

# Performance

Watch for N+1 queries, missing eager loads, unbounded lists, and premature cache. Optimize from evidence.

---

# Git

* One branch, one purpose
* Small, focused commits
* Prefer Conventional Commits
* Pull requests must be reviewable

---

# AI Behaviour

Before coding, read `PROJECT.md`, `TECH_STACK.md`, `architecture/`, `RULES.md`, and the relevant module.

The AI must not:

* Invent business rules
* Change architecture without approval
* Delete features without instruction
* Add dependencies without a reason

---

# Definition of Done

* Requirement met
* Business rules respected
* Architecture still consistent with project decisions
* Backend and frontend done when in scope
* Tests pass
* Project quality gate passes (`composer ci:check`, DEC-011)
* Documentation harvest done
* Code review complete

---

# Forbidden

* Business rules in HTTP adapters
* Queries in HTTP adapters
* Hardcoded secrets
* Debug leftovers in production paths (`dd`, `dump`, leftover `console.log`)
* Changing documentation without matching implementation, or the reverse
* Editing committed production migration history
* Running forbidden destructive database commands on Production or Production-like environments

---

# Related Documents

* [AGENTS.md](./AGENTS.md)
* [architecture/README.md](./architecture/README.md)
* [TECH_STACK.md](./TECH_STACK.md)
* [DECISION_LOG.md](./DECISION_LOG.md)
* [conventions/database.md](./conventions/database.md)
