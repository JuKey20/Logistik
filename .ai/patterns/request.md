# Form Request Pattern

> Authorize and validate. Optional mapping to a DTO. Recommended when using Laravel.

`Item` in examples is a documentation stand-in.

---

# Purpose

Keeps the HTTP adapter thin. Does **not** own the business write set — the Action does.

---

# Location

```text
app/Http/Requests/{Domain}/{Operation}{Entity}Request.php
```

---

# Shape

```php
class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Item::class) ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
```

`toData()` is optional. Do not require a DTO. Endpoints without a payload do not need a Form Request; authorize on the adapter.

Do not put `status`, `role`, or assignment in a generic store/update request unless that request **is** the dedicated operation for that field.

---

# Rules

* `authorize()` delegates to Policy/Gate — do not inline role lists
* Fixed domain values: `Rule::enum()`, not `Rule::exists()` on a sync table
* Unique rules on update must `ignore()` the current model
* Cross-field input checks go in `after()`; object permission stays in Policy
* Business invariants (legal next status, lunas) belong in the Action / extracted class, not only in the Form Request

---

# Must not contain

* Use-case queries
* Calls to Actions
* Response construction

---

# Related Documents

* [policy.md](./policy.md)
* [dto.md](./dto.md)
* [enum.md](./enum.md)
* [../architecture/security.md](../architecture/security.md)
