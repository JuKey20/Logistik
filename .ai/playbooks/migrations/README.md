# Playbooks: Migrations

> Read before writing or reviewing a migration.

| Document | Role |
| --- | --- |
| [SAFE_MIGRATION_RECOVERY.md](./SAFE_MIGRATION_RECOVERY.md) | Recovery when migrate fails mid-DDL |
| [`../../conventions/database.md`](../../conventions/database.md) | Daily SOP and review checklist |
| [`../../RULES.md`](../../RULES.md) | Environment policy (Production vs local/CI) |

```text
Plan schema
  → conventions/database.md
  → new migration (append-only on Production / Production-like)
  → self-check the review list
  → migrate (existing) + migrate:fresh (local/CI only)
  → code review
  → merge / deploy
```

If migrate fails: [`SAFE_MIGRATION_RECOVERY.md`](./SAFE_MIGRATION_RECOVERY.md).
