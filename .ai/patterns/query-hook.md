# Query Hook Pattern

> Server state for this project: Inertia props + `useHttp` / Inertia Form. **Not** TanStack Query (DEC-006).

`item` in examples is a documentation stand-in. **Do not** add `@tanstack/react-query`. **Do not** copy `useQuery` / `useMutation` snippets from older baseline material.

In this app, wrap Inertia `useHttp` (or Form) in `features/{feature}/hooks/` when a feature needs a hook. Pages may use Inertia Form/`useHttp` directly when the screen is still small.

---

# Purpose

Components do not call HTTP/Wayfinder URLs ad hoc when a feature service exists. Caching and toasts, when needed, live in hooks. `422` stays on the form.

---

# Location

```text
features/{feature}/hooks/use-{domain}.ts
```

Create the folder only when the feature needs it.

---

# Shape (Inertia)

Page data: Inertia props.

Async JSON / uploads / modal CRUD: feature function that uses Inertia `useHttp` or Form, with Wayfinder URLs.

Do not store server lists in `useState` + `useEffect` as the primary cache.

Optimistic update is not the default for approvals, assignments, payments, or attachments.

---

# Must not contain

* Axios or a custom `fetch` wrapper (DEC-007)
* TanStack Query
* Authorization decisions
* Business rules (lunas, status, assignment eligibility)

---

# Related Documents

* [service.md](./service.md)
* [form.md](./form.md)
* [../architecture/frontend.md](../architecture/frontend.md)
