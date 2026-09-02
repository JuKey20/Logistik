# Frontend Architecture

Canonical location for feature-based UI, client/server communication, and visual principles.

Examples assume a React + Inertia feature folder (lihat `TECH_STACK.md`). Keep the boundaries.

---

# Feature-based structure (Recommended)

Not full Feature-Sliced Design. Do not add FSD layers `widgets`, `entities`, or `processes`. Inertia route entries stay in `pages/`.

```text
resources/js/
├── features/{feature}/
│   ├── components/
│   ├── hooks/
│   ├── services/
│   ├── schemas/
│   ├── tables/
│   ├── types/
│   ├── constants/
│   └── pages/
├── pages/          → thin route entries (required)
├── components/     → shared UI only
├── hooks/          → truly shared hooks
├── layouts/
├── lib/
└── providers/
```

Create only the subfolders a feature needs. Do not scaffold empty feature trees.

Rules:

* Feature folders use singular names (`item`, not `items`).
* Features do not import other features. Shared code moves up.
* Prefer kebab-case file names. Avoid barrel `index.ts` exports unless the project decides otherwise.
* Pages stay thin: title, layout metadata, and the feature page component.

---

# Communication

**Page navigation / first paint / auth redirects / flash:** Inertia.

**Asynchronous data (lists, filters, modal CRUD, uploads):** Inertia `useHttp` / Form through a feature `services/` module, then a feature hook. Not Axios. Not TanStack Query (DEC-006, DEC-007).

Components do not call HTTP directly.

Server state for pages lives in Inertia props. Async calls use Inertia `useHttp` / Form (lihat `TECH_STACK.md`). UI state stays local.

Optimistic updates are not the default for workflows with approvals, assignments, or attachments. Invalidate after a successful mutation.

Details: [`../patterns/query-hook.md`](../patterns/query-hook.md), [`../patterns/service.md`](../patterns/service.md)

---

# Forms and tables

* Forms: schema + mapping of backend field errors. Backend remains the authority. [`../patterns/form.md`](../patterns/form.md)
* Tables: separate column definitions from the page; handle loading, empty, and filled states. [`../patterns/table.md`](../patterns/table.md)

---

# Visual principles

Independent of brand colors:

* Information first, not decoration
* WCAG AA contrast for text and interactive states
* One token source for color, elevation, and status (success, warning, info, destructive)
* Light and dark from the same token set
* No hardcoded brand hex in features
* Motion 150–200ms; respect `prefers-reduced-motion`
* Confirm destructive actions in a dialog, not `window.confirm`

Brand values belong in the adopting project's stylesheet, not in this baseline.

When using Tailwind: prefer semantic utilities (`bg-primary`, `text-destructive`) over raw palette classes (`bg-green-600`).

---

# Shared UI

Keep primitives close to the design-system source. App-level patterns (page shell, form field, data table chrome, confirm dialog) live in shared components, not inside a feature.

Details: [`../patterns/ui.md`](../patterns/ui.md)

---

# Related Documents

* [principles.md](./principles.md)
* [../TECH_STACK.md](../TECH_STACK.md)
* [../patterns/form.md](../patterns/form.md)
* [../patterns/table.md](../patterns/table.md)
