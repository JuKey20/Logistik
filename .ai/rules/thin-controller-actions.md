# Thin controllers; HTTP-agnostic Actions

Product domain mutations belong in `app/Actions/` (DEC-022, DEC-029).

Controllers: Form Request + Policy, call the Action, return Inertia Response or Redirect. No queries, no status machines, no payment math.

Actions **must not** use `request()`, `response()`, `redirect()`, or session. They take arrays or primitives (DTO optional). They persist **only fields that use case owns**.

Fortify Actions under `app/Actions/Fortify/` follow Fortify contracts.

Details: [`../patterns/action.md`](../patterns/action.md), [`../architecture/backend.md`](../architecture/backend.md).
