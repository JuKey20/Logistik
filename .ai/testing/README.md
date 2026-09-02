# Testing

> How tests support the Definition of Done. Gate: **`composer ci:check`** (DEC-011).

Do not chase coverage numbers. Prioritize **business risk**.

---

# Strategy

| Kind | Use for |
| --- | --- |
| Unit | Extracted invariant classes, enum helpers, pure calculations (tip) that need no HTTP |
| Feature | HTTP + authorization + persistence of one use case |
| Browser / E2E | Only if the project later chooses a critical journey |

Prefer feature tests for authorization and validation. They prove the real boundary. UI hiding is not a test of security.

---

# Required coverage for a new protected endpoint

* Success
* Validation failure (`422`)
* Unauthorized / forbidden (direct HTTP still **403**, including roles that must not act)
* Missing resource
* Inertia page or JSON contract — not status code alone

Every new authorization ability needs a **negative** test.

A bug fix needs a regression test.

---

# Risk areas (when those capabilities exist)

### Authorization

* Role that may
* Role that must not
* Direct HTTP request still 403

### Shipment state

* Valid one-step transition
* Invalid skip / reverse
* Terminal `selesai` / `dibatalkan` immutable

### Assignment

* Valid assignment through the official use case
* Ineligible resource **when that invariant is locked**
* Concurrent/double assignment **when the race is in the design** (transaction + re-validation; not an idempotency-key product)

Do not invent overlap rules in tests that the PRD has not locked.

### Payment

* Valid lunas (actual >= agreed, method, actor, tip)
* Underpay rejected
* Illegal payment transition
* Independent of shipment cancel

### POD

* Authorized upload
* Invalid file rejected
* Unauthorized download
* No public `/storage` URL

### Transaction / files

* Failed DB write does not leave an accepted business state
* File store + DB failure: compensation documented; rollback does not undo disk

---

# Data

* Fixed domain values: set enum fields directly
* Dynamic records: factories
* Do not `migrate:fresh` on Production-like data

---

# Related Documents

* [../RULES.md](../RULES.md)
* [../playbooks/review.md](../playbooks/review.md)
* [../AGENTS.md](../AGENTS.md)
* [../conventions/transactions.md](../conventions/transactions.md)
