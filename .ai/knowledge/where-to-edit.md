# Where to edit (when implementation starts)

Intended layout vs starter. Do not search for shipment modules — they are not in source yet.

| Change | Where |
| --- | --- |
| Locale / timezone / APP_NAME | `.env`, `config/app.php` |
| Auth features off | `config/fortify.php`, `routes/web.php` (`verified`) |
| Roles & policies | `users.role` migration, `app/Enums/UserRole.php`, `app/Policies/` |
| Domain enums | `app/Enums/` |
| Business use cases | `app/Actions/{User,Customer,Vehicle,Shipment}/` |
| Extracted invariant class | Only when justified; location follows convention (`Support/`, `Rules/`, …). **Not** speculative `app/Domain` |
| Complex queries | Local scopes on `app/Models/` first. Query object only when unreadable |
| Audit writes | Same Action as the mutation |
| POD upload/download | `Storage` + private disk; default = auth route + Policy + stream |
| Money columns | `BIGINT` / `DECIMAL(p,0)` |
| UI | `resources/js/pages/` + `features/` as needed; Indonesian |
| Quality | `composer ci:check` only in CI |

Canonical constraints: [`mvp-lock.md`](./mvp-lock.md), DEC-029.
