# Listener Pattern

> React to a domain event without owning the business workflow.

**This MVP:** do not add application Domain Events or Listeners (DEC-024). Do not `implements ShouldQueue` (DEC-021). Audit trail is **not** a listener — it is an explicit write in the Action.

Notification translators are out of scope (DEC-010). Listeners are **deferred** with Events (DEC-029), not banned forever.

Do not create `app/Listeners` speculatively.

---

# Must not contain (if a future DEC adds listeners)

* Domain status updates that belong in the Action
* Audit writes that DEC-024 keeps in the Action
* Core transaction success depending on the listener

---

# Related Documents

* [event.md](./event.md)
* [notification.md](./notification.md)
* [job.md](./job.md)
