# Audit trail (MVP)

Important changes (shipment **status**, **price**, **team assignment**, and similar) must be recorded **explicitly and synchronously inside the Action** that performs the change (DEC-024).

Do **not** use Domain Events, Listeners, Model Observers, or queued jobs for audit.

Prefer the same database transaction as the business write so a failed audit does not leave an unaudited mutation ([`transactions.md`](./transactions.md)).

Timestamps on audit rows use **Asia/Jakarta** (DEC-026), same as `created_at`.

Filesystem/POD writes are not undone by this rollback. [`storage.md`](./storage.md).

Details: [`../architecture/backend.md`](../architecture/backend.md).
