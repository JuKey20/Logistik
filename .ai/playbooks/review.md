# Playbook: Review

> Review from architecture downward. Architecture findings make lower detail review wasted work.

Label findings: must fix, should fix, note.

---

# 1. Architecture (DEC-029)

- [ ] HTTP adapters contain no queries or business rules
- [ ] Product mutations go through Actions (DEC-022)
- [ ] No Repository / no speculative `app/Domain`, `app/Queries`, `app/Modules`
- [ ] Extracted invariant class exists only if justified (not “used twice”)
- [ ] Features do not import other features
- [ ] Inertia `pages/` remain route entries; no full FSD layers

---

# 2. Authorization and validation

- [ ] Every protected endpoint is authorized on the server
- [ ] Frontend gating is not the only control
- [ ] Untrusted input is validated on the backend
- [ ] Fixed domain values are validated from the enum source
- [ ] The use case persists only the fields it owns (not generic `validated()` write)
- [ ] `role`, statuses, assignment, audit, ownership are not mass-assigned

---

# 3. Data and performance

- [ ] Serialized relations are eager-loaded
- [ ] Lists are paginated when they can grow
- [ ] Transactions used for atomic business change or proven concurrency need — not “two tables”
- [ ] No new N+1 on the changed path
- [ ] POD: private disk; default download is auth+Policy+stream; file/DB compensation considered

---

# 3b. Migration (if the change includes one)

Follow [`../conventions/database.md`](../conventions/database.md).

- [ ] No edits to recorded migration history (including starter files)
- [ ] Identifier names within engine limits
- [ ] Partial-failure plan if `up()` has multiple DDL statements
- [ ] Safe on a database that already has data
- [ ] migrate:fresh / wipe not used on Production or Production-like; agents do not fresh unless asked

---

# 4. Response

- [ ] Inertia pages are not forced through a REST envelope
- [ ] HTTP status matches meaning
- [ ] Models are not returned raw on JSON endpoints
- [ ] Errors do not leak internals

---

# 5. Frontend

- [ ] No Axios / TanStack Query
- [ ] URLs from Wayfinder when generated
- [ ] Backend remains authority for status, payment, assignment
- [ ] Loading / empty / error / retry exist where data is loaded
- [ ] Controls have accessible names

---

# 6. Tests and quality

- [ ] New behavior has tests (authorization, state machines, payment, POD as applicable)
- [ ] Each new ability has a negative test
- [ ] Regression test for a bug fix
- [ ] `composer ci:check` passes

---

# 7. Documentation harvest

- [ ] Module docs match the implementation
- [ ] New reusable shape extracted to `patterns/` only if reused
- [ ] `DEC-XXX` written when a decision was made
- [ ] Glossary updated for new terms

---

# Related Documents

* [../RULES.md](../RULES.md)
* [new-module.md](./new-module.md)
* [../conventions/database.md](../conventions/database.md)
* [migrations/README.md](./migrations/README.md)
