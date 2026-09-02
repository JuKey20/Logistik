# Database Conventions

> Operational SOP for schema and migrations. Policy lives in `RULES.md`. This file does not repeat the environment table.

---

# Migration rules

* After a migration is committed and/or recorded in the migrator table, it is immutable.
* Do not edit, delete, rename, reorder, or refill recorded migrations (including no-op stubs), **including starter migrations already in this repo**.
* A migration that **never succeeded** and **was never recorded** may be replaced by a **new file**, with a written decision. Do not silently patch it.
* Every production schema change is a new migration.
* Do not duplicate tables, columns, indexes, or foreign keys that already exist.
* Agents must not run `migrate:fresh`, `db:wipe`, drop database, or truncate existing data unless the user explicitly asks for a local/CI reset. Classify the environment first (`RULES.md`).

Before proposing a schema change, answer: **is this migration safe against a database that already has data?** If unsure, stop.

---

# Identifier rules

Relational engines often cap identifier length (MySQL: **64** characters).

* Do not rely on auto-generated foreign key names (`{table}_{column}_foreign`) for long table or column names.
* Give foreign keys an explicit, short name.
* Give long index and unique names an explicit name.
* Measure length before merge. An over-long name is a common cause of **partial migration**.

```php
$table->foreign('parent_id', 'items_parent_fk')
    ->references('id')
    ->on('items')
    ->restrictOnDelete();
```

`Item` in examples is a documentation stand-in, not a required entity.

---

# Money columns (DEC-019)

All financial attributes (harga kesepakatan, nominal aktual, bonus/tip, and any later money columns) are **whole Rupiah**: no cents, no decimal scale in the domain.

**Database (MySQL):** `BIGINT` **or** `DECIMAL(p, 0)` with scale **0**. Use a width that will not overflow B2B totals (`BIGINT` is the default choice).

**Forbidden:** `FLOAT`, `DOUBLE`, `DECIMAL` with scale > 0 for these amounts.

**PHP:** `int` (Eloquent integer / `bigint` casts). Do not use `float` for money.

See [`money.md`](./money.md).

---

# Testing migrations

| Check | Environment | Action |
| --- | --- | --- |
| Append migrate | Existing DB (Production-like locally if available) | migrate — must pass |
| Full rebuild | Local / CI only | migrate:fresh — must pass |
| Schema validation | After migrate | Columns, nullability, defaults, FK, indexes match the design |
| Identifier length | Review + test | Explicit names ≤ engine limit |
| Dual path | Required | Works on empty DB **and** existing DB |

`migrate:fresh` remains **forbidden** on Production and Production-like environments. See `RULES.md`.

---

# Recovery (summary)

If migrate fails:

**STOP.** Do not blindly retry, rollback as a reflex, wipe the database, or edit the migrator table.

1. Inspect live schema.
2. Decide whether a **partial migration** happened (some DDL committed, migrator row missing).
3. Follow [`../playbooks/migrations/SAFE_MIGRATION_RECOVERY.md`](../playbooks/migrations/SAFE_MIGRATION_RECOVERY.md).
4. Verify orphans are gone.
5. Continue with an append-only repair migration if needed.

Remember: "migration failed" does not mean "database unchanged". MySQL DDL often implicit-commits.

---

# Review checklist (required on PRs with migrations)

Schema

- [ ] Names match project convention
- [ ] Types, nullability, defaults match the need
- [ ] Money columns: `BIGINT` or `DECIMAL(p,0)` — never FLOAT/DOUBLE (DEC-019)
- [ ] Foreign keys and `onDelete` are intentional
- [ ] Indexes match query paths
- [ ] Unique constraints consider soft-deleted rows
- [ ] No duplicate structure

Engine

- [ ] FK / index / unique names within length limits
- [ ] Multi-statement `up()` has a partial-failure plan

Safety

- [ ] No edits to recorded history
- [ ] Safe for a database that already has data
- [ ] Destructive changes have explicit approval

Testing

- [ ] migrate on existing DB
- [ ] migrate:fresh on local/CI
- [ ] Related tests green

---

# Related Documents

* [../RULES.md](../RULES.md)
* [../playbooks/migrations/README.md](../playbooks/migrations/README.md)
* [../playbooks/review.md](../playbooks/review.md)
