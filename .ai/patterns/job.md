# Job Pattern

> **This MVP does not use a queue** (DEC-021). Do not implement `ShouldQueue` Jobs or queued listeners.

Exports are also synchronous (DEC-020). Do not add `app/Jobs` for product work in MVP. Keep the `jobs` table; do not run workers.

Queues are **deferred** until a real async/non-critical side effect needs them (DEC-029). Not a lifetime ban. Do not create job classes “for later.”

---

# Later-phase shape (not MVP)

```text
app/Jobs/{Purpose}Job.php
```

When a future DEC enables the queue:

* The HTTP request does not wait for the job
* Jobs must not encode core business rules that belong in the use case
* Core transactions must not require the job to succeed

---

# Related Documents

* [listener.md](./listener.md)
* [notification.md](./notification.md)
* [../TECH_STACK.md](../TECH_STACK.md)
* [../conventions/queue.md](../conventions/queue.md)
