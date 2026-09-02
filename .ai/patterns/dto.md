# DTO Pattern

> Optional typed data between layers. **Not required** on every Action (DEC-022, DEC-029).

`Item` in examples is a documentation stand-in.

Do not introduce `app/DTO` speculatively. Use a DTO when a payload is large, reused, or error-prone as a loose array — not because a diagram has a DTO box.

---

# Purpose

After a DTO exists, later layers do not re-check keys or types. Light normalization (`trim`, enum conversion, defaults) belongs here.

A DTO is **not** a mass-assignment list. The Action still chooses which attributes to persist.

---

# Location (when used)

```text
app/DTO/{Domain}/{Purpose}Data.php
```

Suffix `Data` is required in this pattern.

---

# Shape

```php
final readonly class ItemData
{
    public function __construct(
        public string $name,
        public ItemStatus $status,
    ) {}

    /**
     * @param  array{name: string, status: string}  $validated
     */
    public static function fromValidated(array $validated): self
    {
        return new self(
            name: $validated['name'],
            status: ItemStatus::from($validated['status']),
        );
    }
}
```

---

# Rules

* `final readonly class` with typed promoted properties
* `fromValidated()` / `fromConfig()` named factories
* PHPDoc array shapes on input
* Must not contain database queries, HTTP facades, or invariants that need other persisted records (those stay in the Action or an extracted class)

---

# Related Documents

* [action.md](./action.md)
* [request.md](./request.md)
* [../architecture/data.md](../architecture/data.md)
