# Playbook: New Feature on an Existing Module

> Add one vertical slice at a time. Do not stack unconnected layers.

Use [new-module.md](./new-module.md) if the capability docs do not exist yet.

---

# Steps

1. Write the contract in the module docs (who may call it, payload, status).
2. Schema change only if needed — new migration, never edit recorded history.
3. Model/scopes, then Action, then Policy and Form Request — not Repository-first.
4. Expose HTTP. Keep adapters thin. Mutation field list is explicit in the Action.
5. Update client types, hooks/forms, and UI together. Inertia first; no TanStack Query.
6. Tests, including negatives (authorization, illegal transitions).
7. `composer ci:check`
8. Documentation harvest.

---

# Keep pairs in sync

| Change | Also update |
| --- | --- |
| Inertia prop / JSON field | Frontend type + module API doc |
| Enum case | Validation, UI options, glossary |
| Validation rule | Client schema (UX only) |
| Route | Typed route generator |
| New ability | Negative tests + module flow |

---

# Stop and ask

* Business rule is unclear
* Existing data would become invalid
* A published contract must break
* The change fights an accepted `DEC-XXX`
* Sender/recipient/address model would be invented

---

# Related Documents

* [new-module.md](./new-module.md)
* [new-api.md](./new-api.md)
* [review.md](./review.md)
* [../RULES.md](../RULES.md)
