# Patterns

Reusable implementation shapes. **This project:** DEC-029. Several files below are deferred (Repository, Event, Listener, Job, Notification) — read the banner in each file before copying.

Create a new pattern only when a second module will follow it. Do not add empty placeholders.

| Pattern | Use |
| --- | --- |
| [action.md](./action.md) | Product domain operations (**Required**) |
| [repository.md](./repository.md) | **Not used** — do not copy |
| [request.md](./request.md) | Validate + authorize (DTO optional) |
| [resource.md](./resource.md) | Explicit JSON serialization if needed |
| [dto.md](./dto.md) | Optional typed data |
| [policy.md](./policy.md) | Server-side object authorization |
| [enum.md](./enum.md) | Fixed domain values |
| [event.md](./event.md) | Deferred — not MVP default |
| [listener.md](./listener.md) | Deferred — not MVP default |
| [job.md](./job.md) | Deferred — not MVP default |
| [notification.md](./notification.md) | Out of MVP (DEC-010) |
| [query-hook.md](./query-hook.md) | Inertia hooks (not TanStack Query) |
| [service.md](./service.md) | Optional feature HTTP/Wayfinder |
| [table.md](./table.md) | Data tables |
| [form.md](./form.md) | Forms and server errors |
| [form-layout.md](./form-layout.md) | Field alignment |
| [modal.md](./modal.md) | Dialog layout |
| [scrollbar.md](./scrollbar.md) | Scroll utilities |
| [attachment.md](./attachment.md) | Private file access |
| [ux-badge.md](./ux-badge.md) | Bell vs pending counts |
| [ui.md](./ui.md) | Shared visual chrome |

Examples use `Item` as a stand-in entity. Replace with the adopting project's names.
