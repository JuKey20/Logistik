# Enum Pattern

> Single source of truth for fixed domain values.

`ItemStatus` in examples is a documentation stand-in. **This project's locked enums:** `UserRole`, `ShipmentStatus`, `PaymentStatus`, `PaymentMethod` — values in [`../TECH_STACK.md`](../TECH_STACK.md) (DEC-009). PHP classes are not in `app/Enums/` yet.

---

# Purpose

Closed sets (status, type) are defined once and reused by validation, DTO, casts, UI options, authorization helpers, and seeders.

The database is not the source of those options.

---

# Location

```text
app/Enums/{Name}.php
```

---

# Shape

```php
enum ItemStatus: string
{
    case Draft = 'draft';
    case Active = 'active';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
        };
    }

    /**
     * @return list<array{name: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            static fn (self $status): array => [
                'name' => $status->value,
                'label' => $status->label(),
            ],
            self::cases(),
        );
    }
}
```

---

# Rules

* Backed string enums; cases TitleCase; values snake_case
* UI labels from `label()` / `options()`, not from interpolating the backing value
* Validate with `Rule::enum()`
* Cast the owning model column to the enum
* Prefer storing the backing value on the owning row when the value has no extra attributes and is not edited at runtime

Create a reference table only when the value has extra attributes or is managed by users at runtime (then it is dynamic data).

If a sync table exists for foreign keys only: seed from `cases()`, never load UI options from it, still validate with `Rule::enum()`.

---

# Frontend

Do not duplicate the enum by hand in two places if it can be avoided. If a client mirror is required, document the sync rule.

Select options should come from the backend (`options()` via page props or a small endpoint), not from a second hardcoded list.

---

# Related Documents

* [resource.md](./resource.md)
* [request.md](./request.md)
* [../architecture/data.md](../architecture/data.md)
