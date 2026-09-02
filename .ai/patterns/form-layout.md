# Form Layout Pattern

> Label, control, helper, and error share one vertical rhythm. Action buttons are a field with the same label slot height.

---

# Structure

Each field:

```text
Label
Control
Helper (optional)
Error (optional)
```

Action buttons use the same label slot so they align with inputs on one row (filters, search bars).

---

# Rules

* Alignment comes from grid/flex, not one-off CSS
* Shared wrappers (`FormField`, `FormActionsField`, `FilterForm`) live in shared components
* Features do not copy the chrome
* Works on small viewports: stack, do not squeeze labels

---

# Related Documents

* [form.md](./form.md)
* [ui.md](./ui.md)
