# Modal Pattern

> Viewport-safe dialog: fixed header/footer, only the body scrolls.

---

# Purpose

Prevent dialogs that grow past the viewport, nested scrollbars, and content stuck under the scrollbar.

---

# Structure

```tsx
<Dialog open={open} onOpenChange={onOpenChange}>
    <DialogContent size="xl">
        <DialogHeader>
            <DialogTitle>Title</DialogTitle>
            <DialogDescription>Short description.</DialogDescription>
        </DialogHeader>
        <DialogBody className="space-y-4">{/* long content */}</DialogBody>
        <DialogFooter>{/* actions */}</DialogFooter>
    </DialogContent>
</Dialog>
```

Forms wrap the same regions with a `DialogForm` (or equivalent) so submit lives in the footer.

---

# Rules

* Width is a discrete size (`sm`–`2xl`), not content-driven
* Do not put `overflow-y-auto` on the whole dialog surface — only the body
* Body uses the shared scroll utility and a right gutter so text does not sit under the scrollbar
* See [scrollbar.md](./scrollbar.md) and [form.md](./form.md)

---

# Related Documents

* [form.md](./form.md)
* [form-layout.md](./form-layout.md)
* [ui.md](./ui.md)
