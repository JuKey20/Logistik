# Frontend Service Pattern

> Optional place a feature keeps HTTP/Wayfinder calls. This project uses **Inertia `useHttp` / Form**, not Axios (DEC-007).

`Item` in examples is a documentation stand-in. Prefer Wayfinder URL helpers over hardcoded paths.

Do **not** add `resources/js/api/` or an envelope client for MVP. JSON envelope is not adopted ([`../architecture/data.md`](../architecture/data.md)).

---

# Purpose

When a feature has several async calls, keep URLs and payload mapping out of presentational components. Hooks/forms still own pending UI and `422` mapping.

Small screens may call Wayfinder from the page or form without a service file.

---

# Location (when used)

```text
features/{feature}/services/{domain}-service.ts
```

---

# Rules

* Named functions per operation
* Return data the UI needs, not a fictional envelope
* Do not catch errors here if the form/hook must see them
* No React hooks, no business invariants, no toasts required in this layer

---

# Must not contain

* Axios
* TanStack Query
* Authorization decisions
* Client-side lunas / status / assignment rules

---

# Related Documents

* [query-hook.md](./query-hook.md)
* [form.md](./form.md)
* [../architecture/frontend.md](../architecture/frontend.md)
