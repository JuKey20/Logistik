# UX Badge Pattern

> Communication unread ≠ pending business work.

There is **no notification module in MVP** (DEC-010). Do not build a bell feed from this file until a later DEC. Operational sidebar counts (for example My Tasks) may use Inertia props.

Use when the shell shows a bell and/or sidebar counts.

---

# Bell — unread communication

Source: notification module unread count.

* Own query; do not derive from the full list
* Must not encode feature business rules
* Mark-as-read invalidates notification keys only

If polling (no realtime yet): poll the count, not the list; pause when the tab is hidden; refetch on focus.

---

# Sidebar — pending work

Source: the **feature** that owns the work.

* Own `pending-count` (or equivalent) endpoint
* Shell only displays `pendingCount`
* Notification read-state does not change this badge
* Feature mutations invalidate the feature count

---

# Related Documents

* [notification.md](./notification.md)
* [query-hook.md](./query-hook.md)
