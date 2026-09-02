# Scrollbar Pattern

> One scrollbar style for the app. Tokenized, not copied per feature.

---

# Utilities (Recommended names)

| Class | Use |
| --- | --- |
| `scroll-area` | Vertical region (dialog body, lists) |
| `scroll-area-x` | Intentional horizontal scroll (wide tables) |
| `scrollbar-thin` | Visual only; compose with your own overflow (sidebar) |

Do not paste `::-webkit-scrollbar` into feature files.

---

# Behavior

* Desktop: thin scrollbar, colors from design tokens
* Small viewports: hide the bar; keep gesture scrolling
* Do not set `overflow: hidden` on content that must scroll
* Dialogs: only the body scrolls ([modal.md](./modal.md))

---

# Related Documents

* [modal.md](./modal.md)
* [ui.md](./ui.md)
* [../architecture/frontend.md](../architecture/frontend.md)
