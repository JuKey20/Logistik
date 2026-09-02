# Module documentation template

> Recommended structure. Not every module needs every file.

Create `modules/{module-name}/` and add only the documents that match complexity.

---

# Suggested files

| File | When to write it |
| --- | --- |
| `README.md` | Always for an implemented module |
| `flow.md` | There is a user or state workflow |
| `backend.md` | Non-trivial use cases, policies, or actions |
| `frontend.md` | Screens, hooks, or tables |
| `api.md` | JSON or page contracts others consume |
| `database.md` | Tables, enums, or migrations owned here |
| `checklist.md` | Multi-step delivery you want to track |
| `history.md` | After the first shipped change |

A lookup table with one screen may be a single `README.md`.

---

# README skeleton

```markdown
# {Module}

## Purpose

## In scope

## Out of scope

## Who may use it

## Main flows

## Related documents
```

Fill facts from this project. Do not copy another product's modules.

---

# Harvest

When the module ships, update the files that exist so they match the code. See `RULES.md`.

---

# Related Documents

* [../../playbooks/new-module.md](../../playbooks/new-module.md)
* [../../HOW_TO_ADAPT.md](../../HOW_TO_ADAPT.md)
