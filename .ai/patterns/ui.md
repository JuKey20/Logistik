# UI Patterns

> Shared visual building blocks. Brand colors belong in the adopting project's tokens, not here.

---

# Principles

* Consistency
* Simplicity
* Information first
* WCAG AA
* Readability
* Progressive disclosure

Do not change a workflow only to make a screen prettier.

---

# Tokens

One stylesheet owns semantic tokens: background, card, foreground, muted, border, primary, success, warning, info, destructive, elevation, scrollbar.

Features use those tokens. They do not hardcode palette classes for status or brand.

Icons: pick one family for the project and stay with it.

---

# Layout

Shared `PageShell` + `PageHeader` (title, description, actions). Page padding is consistent.

KPI cards use semantic tone. Charts only when they help a decision.

---

# Table and form chrome

Reuse shared data-table and form wrappers. Do not reimplement pagination, empty, skeleton, or field chrome in each feature.

Confirm destructive actions with a dialog component, not `window.confirm`.

See [table.md](./table.md), [form.md](./form.md), [modal.md](./modal.md).

---

# Menus

Destructive **buttons** use solid destructive styling. Destructive **menu items** use destructive text on the popover surface, not "on-solid" foreground.

Disabled items stay readable; business reasons go through `disabledReason` (or equivalent) for a tooltip.

---

# Anti-patterns

* Decorative gradients and glass
* Mixing icon libraries
* Inline hex for theme colors
* Duplicating empty/pagination per feature

---

# Related Documents

* [../architecture/frontend.md](../architecture/frontend.md)
* [form-layout.md](./form-layout.md)
* [scrollbar.md](./scrollbar.md)
