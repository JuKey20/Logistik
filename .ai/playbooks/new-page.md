# Playbook: New Page

> Add a server-rendered page entry plus a feature page component.

---

# Structure (Recommended for Inertia)

`pages/{route}/index.tsx` — thin entry: document title, layout metadata, feature page.

`features/{feature}/pages/{domain}-index-page.tsx` — filters, hooks, composition.

---

# Steps

1. Page adapter authorizes, then sends **small, stable** initial props.
2. Register a named route.
3. Regenerate typed routes.
4. Create the thin entry and the feature page.
5. Add navigation only if needed. Navigation gating is UX, not security.
6. Test authorization and the rendered page component.

---

# Initial props versus async data

Send as props when the data is small, stable, and required for first paint (for example option lists from enums).

Fetch with Inertia `useHttp` / Form when the data is paginated, filtered, or changes after mutations.

Do not send the same paginated list as props **and** as a second query library.

---

# Required UI states

Every page that loads data handles loading, empty, error (with retry), and in-flight actions (disabled controls).

Reset pagination in the filter handler, not inside `useEffect` that writes state.

---

# Related Documents

* [new-api.md](./new-api.md)
* [../patterns/table.md](../patterns/table.md)
* [../patterns/form.md](../patterns/form.md)
* [../architecture/frontend.md](../architecture/frontend.md)
