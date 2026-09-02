# Repository Pattern

> Persistence abstraction. **Not used in this project** (DEC-023, DEC-029). Do not add Repositories. Do not generate `app/Repositories`.

Complex queries: Eloquent **local scopes** first. If a query later becomes unreadable, a **query object** is the escape hatch — that is not this pattern.

The rest of this file is historical baseline shape for a later DEC only. Do not copy it into the codebase.

`Item` in examples is a documentation stand-in.

---

# Purpose (later phase only)

The use case states what data it needs. A repository would decide how to query. Eloquent already does that for MVP.

---

# Location (do not create)

```text
app/Repositories/Contracts/{Entity}RepositoryInterface.php
app/Repositories/Eloquent/Eloquent{Entity}Repository.php
```

---

# Must not contain (if a future DEC ever adds this)

* Business workflow
* Transactions (owned by the use case)
* Serialization
* Authorization
* One-line wrappers around `find()` / `create()`

---

# Related Documents

* [action.md](./action.md)
* [../architecture/backend.md](../architecture/backend.md)
* [../RULES.md](../RULES.md)
