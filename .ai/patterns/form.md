# Form Pattern

> Client schema + backend field-error mapping. Backend remains the authority.

**Required** for this project (DEC-001): React Hook Form + Zod. Backend remains the authority.

`Item` in examples is a documentation stand-in.

---

# Location

```text
features/{feature}/schemas/{domain}-schema.ts
features/{feature}/components/{domain}-form-dialog.tsx
```

---

# Schema

* Field names match the backend, including confirmation fields, so `422` maps without a dictionary
* Do not duplicate backend limits; pass policy/limits as props when they vary by environment
* Conditional rules use `superRefine` (or equivalent), not a second schema per mode when one form serves create and edit

Rules that cannot run in the browser stay on the server.

---

# Component

* Memoize the resolver when the schema depends on props
* `reset()` when opening a dialog; do not fight changing `defaultValues`
* Disable submit while submitting
* Every input has a label; helper text uses `aria-describedby`
* UI locks (disabled fields) are UX; the server still rejects illegal payloads

---

# Dialog width and long text

Dialog width is a design token, not a function of data. Long values must shrink (`min-w-0`) and truncate or wrap. Do not use `overflow-x-hidden` as a fix. Truncated single-line text gets a design-system tooltip only when actually truncated.

See [modal.md](./modal.md) and [form-layout.md](./form-layout.md).

---

# Server errors

Close the dialog only on success. Map `422` to fields with `setError`. Unknown keys are ignored. Non-validation errors become a toast, unless already handled globally.

---

# Related Documents

* [form-layout.md](./form-layout.md)
* [query-hook.md](./query-hook.md)
* [ui.md](./ui.md)
* [../conventions/response.md](../conventions/response.md)
