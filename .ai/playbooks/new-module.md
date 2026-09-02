# Playbook: New Module

> Build a product capability from agreement to done. Skip steps the complexity does not need.

A product capability in `PROJECT.md` is **not** automatically `app/Modules/{Name}` (DEC-029).

---

# Prerequisites

1. Read `AGENTS.md`, `RULES.md`, `architecture/README.md`, and `GLOSSARY.md`.
2. Confirm scope, who may use it, and business rules. If those are missing, stop.

---

# 1. Documentation first

Create `modules/{module}/` using the **recommended** structure in [`../modules/_template/README.md`](../modules/_template/README.md) when the capability is being implemented.

Write only the files this module needs. A tiny module may be a single `README.md`.

---

# 2. Persistence

1. Enum for fixed values, if any.
2. Migration (append-only; never edit committed history). Ask: is this safe on a database that already has data?
3. Model, relations, casts, local scopes as needed.
4. Factory.
5. Seeder only for reference data derived from enums, if needed.

Choose relations from real cardinality. Do not add a pivot “for flexibility.” Do not add an Address (or similar) entity without a product decision.

Do **not** start with a Repository contract.

---

# 3. Backend

Prefer this order (DEC-029):

1. Model + migration
2. Policy
3. Form Request (when there is a payload)
4. Action for the use case
5. Thin HTTP adapter (Inertia page and/or JSON)
6. Extract a dedicated invariant class only if justified
7. Serializer only if the response is not simple Inertia props

Starter-only CRUD without domain invariants may omit an Action. Product shipment/payment/assignment/POD/user-role mutations must not.

---

# 4. Routes

Register page and JSON routes according to `TECH_STACK.md`.

If JSON shares the web session, keep it on the web middleware stack. Do not assume a public API stack. Do not adopt the JSON envelope unless a DEC says so.

---

# 5. Frontend (when in scope)

1. Thin `pages/` entry
2. Feature folders only as needed
3. Types matching server props
4. RHF+Zod when the form is complex; backend remains authority
5. Wayfinder URLs

Regenerate typed routes (Wayfinder) before writing URL calls.

---

# 6. Tests

* Happy path and validation
* Positive and negative authorization (direct HTTP still 403)
* Illegal status/payment transitions when those machines apply
* Page contract the project uses — not status code alone

---

# 7. Quality gate and harvest

Run `composer ci:check`. Then documentation harvest (`RULES.md`).

---

# Related Documents

* [new-api.md](./new-api.md)
* [new-page.md](./new-page.md)
* [review.md](./review.md)
* [../architecture/backend.md](../architecture/backend.md)
