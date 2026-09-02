# API Resource Pattern

> Explicit serialization. Do not return ORM models.

`Item` in examples is a documentation stand-in.

---

# Purpose

The resource defines which fields leave the backend. That shape is the source of truth for feature TypeScript types.

---

# Location

```text
app/Http/Resources/{Domain}/{Entity}Resource.php
```

---

# Shape

```php
/**
 * @mixin Item
 */
class ItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
```

---

# Rules

* `@mixin` the model for static analysis
* Relations through `whenLoaded()`
* Timestamps ISO-8601, null-safe
* Enums as backing value plus label when the UI needs both
* Never emit `password`, remember tokens, or storage paths for private files
* A new resource field updates frontend types and the module API doc

---

# Related Documents

* [../conventions/response.md](../conventions/response.md)
* [enum.md](./enum.md)
* [../architecture/data.md](../architecture/data.md)
