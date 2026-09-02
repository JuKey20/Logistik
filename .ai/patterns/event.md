# Event Pattern

> A business fact that already happened.

**This MVP:** do not add application Domain Events or Listeners (DEC-024). Audit is written **explicitly and synchronously inside the Action**.

Events/queues are **deferred** until a proven asynchronous, non-critical side effect exists (DEC-029, DEC-021). They are not a lifetime ban. Do not `make:event` “to be ready.” Core mutations must not depend on listeners succeeding.

The rest of this file is for a later phase after a new DEC.

---

# Purpose (later phase)

After the use case persists successfully, dispatch an event. Listeners handle notification, mail, or integrations — not audit, not status machines.

---

# Location (do not create in MVP)

```text
app/Events/{Feature}/{PastTenseBusinessFact}.php
```

---

# Related Documents

* [listener.md](./listener.md)
* [notification.md](./notification.md)
* [action.md](./action.md)
* [../conventions/queue.md](../conventions/queue.md)
