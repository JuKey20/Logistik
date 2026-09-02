# Explicit mutation fields

Each use case persists **only the fields it owns**. `$request->validated()` is not a write list.

Do not mass-assign `role`, shipment status, payment status, assigned user/vehicle, audit columns, or ownership through a generic payload.

Status and assignment changes go through their dedicated Actions.

Details: [`../architecture/security.md`](../architecture/security.md), [`../architecture/data.md`](../architecture/data.md).
