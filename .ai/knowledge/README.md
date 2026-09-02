# Knowledge Base

> Map of **this project's actual code** vs locked MVP intent.

**Canonical lock (interview closed 2026-09-02):** [`mvp-lock.md`](./mvp-lock.md)

Also: [`overview.md`](./overview.md), [`where-to-edit.md`](./where-to-edit.md), [`../DECISION_LOG.md`](../DECISION_LOG.md), [`../PROJECT.md`](../PROJECT.md), [`../TECH_STACK.md`](../TECH_STACK.md).

---

# What belongs here

* What is implemented vs only planned
* Where to edit for a given change
* Request flow as it exists in code
* Absences (no queue yet, no observer yet) stated explicitly

---

# What does not belong here

* Long business rules (those live in `modules/`)
* Architecture philosophy (that lives in `architecture/`)
* File-by-file commentary of obvious code

---

# Suggested files after adaptation

Create a file only when it has real content:

```text
knowledge/
├── README.md              ← this index
├── overview.md            ← implemented modules and stack
├── architecture-flow.md   ← actual request path
├── where-to-edit.md
└── search-index.md
```

Add backend/frontend/infrastructure maps when they help navigation.

---

# Maintenance

1. Follow the code. If the code changed, update this folder.
2. Link to `modules/{module}/` for business behavior.
3. Keep it short.

---

# Related Documents

* [mvp-lock.md](./mvp-lock.md) — **panduan mutlak MVP** (interogasi ditutup)
* [overview.md](./overview.md) — implemented vs planned
* [where-to-edit.md](./where-to-edit.md)
* [../modules/README.md](../modules/README.md)
* [../PROJECT.md](../PROJECT.md)
* [../DECISION_LOG.md](../DECISION_LOG.md)
* [../TECH_STACK.md](../TECH_STACK.md)
* [../HOW_TO_ADAPT.md](../HOW_TO_ADAPT.md)
* [../architecture/README.md](../architecture/README.md)
* [../modules/_template/README.md](../modules/_template/README.md)
