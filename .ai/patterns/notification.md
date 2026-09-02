# Notification Pattern

> Recommended platform capability when the product needs notifications across features, especially more than one channel.

Not mandatory. Simple apps may skip this module.

Channels: **none for MVP** (DEC-010). Do not implement this module until a later DEC. Do **not** copy the event-driven diagram below into MVP code (DEC-024, DEC-029).

---

# Architecture

```text
Use case
    → Domain event (feature-owned)
    → Feature translator listener
    → Notification module
    → Dispatcher
    → Channel resolver
    → Channel strategy
```

Features must not ship their own `NotificationService` or send from controllers, repositories, or models.

---

# Responsibility

| Layer | Knows feature domain? |
| --- | --- |
| Use case | Yes — then dispatches an event |
| Domain event | Yes — does not know notifications |
| Translator listener | Yes — the only glue |
| Dispatcher / resolver / channel | No |

---

# Intent DTO

The module accepts only a generic intent, for example:

* `type` — stable key
* `title`, `body`
* `data` — safe JSON (ids, not Eloquent graphs)
* `recipient_ids`
* `action_url` optional
* `channels` optional — unused in MVP (DEC-010; no product channels)

---

# Channels

Each channel implements one interface. Adding a channel must not change business Actions.

Delivery is asynchronous (`ShouldQueue` on the translator).

---

# In-app UX (when adopted)

Unread count and list are separate queries. Bell unread ≠ sidebar pending-work. See [ux-badge.md](./ux-badge.md).

---

# Must not

* Business Actions calling channels directly
* Notification module importing feature models/events
* Parallel notification paths per feature
* `if (channel === …)` inside domain logic

---

# Related Documents

* [event.md](./event.md)
* [listener.md](./listener.md)
* [../TECH_STACK.md](../TECH_STACK.md)
* [../architecture/backend.md](../architecture/backend.md)
