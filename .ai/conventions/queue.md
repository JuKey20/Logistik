# Queue (MVP)

All product features run **synchronously** in MVP (DEC-021). Do not implement `ShouldQueue`, application Job classes, or queued listeners. Do not require a Queue Worker in deploy.

Keep the Laravel `jobs` table. Prefer `sync` so a stray dispatch still runs inline.

Events and queues are **deferred** until there is a real asynchronous, non-critical side effect (for example WhatsApp/email, webhook, large export, image processing). They are not forbidden for the life of the product. Core state mutations must not depend on those side effects succeeding.

See [`../TECH_STACK.md`](../TECH_STACK.md), DEC-029.
