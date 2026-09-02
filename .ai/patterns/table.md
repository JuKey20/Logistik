# Table Pattern

> Data tables: column factory + shared row-action chrome. Recommended: TanStack Table.

Not every list is a data table. Attachment lists and simple `<ul>` do not need this pattern.

`Item` in examples is a documentation stand-in.

---

# Purpose

Column definitions stay out of the page. Row actions sit in a single **Options** column on the left so action buttons do not steal width.

---

# Location

```text
components/table/row-options-menu.tsx          → shared
features/{feature}/tables/{domain}-columns.tsx
features/{feature}/components/{domain}-table.tsx
```

---

# Options column (Recommended)

First column. Visible header. Icon trigger with an `aria-label` that includes the row identity. Items use icon + text. Destructive items use the destructive menu variant. Disabled items include a reason for a tooltip.

Predicates (`canEdit`) are passed in. Column factories do not call hooks, services, or mutations. Memoize the columns on the page.

---

# Data

Pagination, search, and sort run on the server for paginated endpoints. Use `getCoreRowModel()` only. Do not enable client table models that would filter one page of server data.

Filter state lives on the page and is passed to the query hook. Reset page in the filter handler, not in `useEffect`.

---

# States

The table component handles loading (skeleton), empty (`colSpan`), and rows. Error and retry belong on the page.

---

# Related Documents

* [query-hook.md](./query-hook.md)
* [ui.md](./ui.md)
* [../architecture/frontend.md](../architecture/frontend.md)
