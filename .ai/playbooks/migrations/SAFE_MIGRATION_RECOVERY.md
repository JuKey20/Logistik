# Safe Migration Recovery

> Engineering playbook for partial migrations. Not a record of any product incident.

---

# Failure mode

On engines such as MySQL, multi-statement `up()` is often **not atomic**. One statement can commit; the next can fail; the migrator may **not** record the batch.

Then a retry runs `up()` from the start and hits **duplicate column** (or equivalent).

---

# Symptoms

* Live schema has a new column (or table) that the failed file was adding
* Expected foreign key or unique constraint is missing
* The migration filename is **absent** from the migrator table
* Retrying migrate fails with a duplicate-object error

---

# Response

## STOP

Do not:

* Retry `migrate` blindly
* `migrate:fresh` / `db:wipe` on Production or Production-like
* Edit the migrator table
* Fill or delete a migration that **already recorded** (including empty stubs)

Local and CI may reset only when the environment policy in `RULES.md` allows it, and never as a substitute for understanding Production-like state.

## Inspect

Compare live schema (`SHOW COLUMNS` / `SHOW CREATE TABLE` or the project's equivalent) with the migration file.

Decide: partial success vs no change vs fully applied but unrecorded (rare).

## Repair orphans, then append

Typical partial add-column + failed constraint:

1. Confirm the column exists and the constraint does not.
2. Confirm the failed file is **not** recorded.
3. Drop the orphan column (or other orphan object) on that environment.
4. Verify the orphan is gone.
5. Ship a **new** migration with explicit short constraint names.
6. Run migrate.
7. Verify column + constraints.

If a failed file was never recorded, it may be removed and replaced by the new file. Record that choice in `DECISION_LOG.md`.

If a no-op stub **was** recorded, leave it untouched forever.

---

# Lessons that always apply

1. Identifier length limits are real — measure before merge.
2. Partial migration is a normal MySQL DDL failure mode — plan recovery, not only the happy path.
3. Duplicate column usually means desync, not a random engine bug.
4. The migrator table is not the only source of truth after an exception.
5. Recorded migrations are immutable.
6. Unrecorded failed files may be replaced with a documented new file.
7. A review checklist is cheaper than Production recovery.

---

# Prevention

* Explicit constraint names for foreign keys and for long index/unique names
* Recovery note when `up()` has multiple DDL statements
* Test migrate on existing DB and migrate:fresh on local/CI
* Regression test for identifier length when the engine requires it

---

# Related Documents

* [README.md](./README.md)
* [`../../conventions/database.md`](../../conventions/database.md)
* [`../../RULES.md`](../../RULES.md)
