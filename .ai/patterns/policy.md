# Policy Pattern

> Server-side authorization for "may this actor do this to this object?"

The project **requires** Laravel Gates & Policies with a string `users.role` column (DEC-005). This file is the locked Policy shape, not an optional alternative.

`Item` in examples is a documentation stand-in.

---

# Purpose

Authorization must not be scattered across controllers, use cases, and UI.

---

# Location

```text
app/Policies/{Entity}Policy.php
```

---

# Shape

```php
class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canViewItems();
    }

    public function update(User $user, Item $item): bool
    {
        return $user->canManageItem($item);
    }
}
```

Keep role or permission details in named helpers so policies stay readable. Do not hardcode another product's role names.

---

# Enforcement

| Context | Typical call |
| --- | --- |
| Request with payload | `authorize()` on the Form Request |
| Request without payload | `Gate::authorize()` on the adapter |
| Page | `Gate::authorize()` on the page adapter |

Shared auth props on the client only hide chrome.

---

# Boundary

Policy: may the actor touch this object?

Validation `after()`: is this payload consistent?

---

# Testing

Every ability needs a negative test. Forbidden responses must not leak internals. Inertia/JSON need not use the deferred envelope.

---

# Related Documents

* [request.md](./request.md)
* [../architecture/security.md](../architecture/security.md)
* [../TECH_STACK.md](../TECH_STACK.md)
