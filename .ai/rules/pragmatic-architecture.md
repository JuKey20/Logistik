# Pragmatic Modular Monolith

Default flow: Controller → Form Request + Policy → Action → Eloquent (DEC-029).

Do not add Repositories, `app/Modules`, speculative `app/Domain` or `app/Queries`, or Domain Events for core workflow.

Extract a dedicated invariant class only when justified (complexity, criticality, readability, duplication, reuse, testability, scatter risk) — not because a rule is used twice.

Product capability names in `PROJECT.md` are not code packages.

Details: [`../architecture/principles.md`](../architecture/principles.md), [`../architecture/backend.md`](../architecture/backend.md).
