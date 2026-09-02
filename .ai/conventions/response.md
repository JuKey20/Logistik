# JSON Response Convention

> Optional contract for asynchronous JSON endpoints. **Not adopted** for MVP (Inertia pages and Inertia Form/`useHttp`). Adopt only with a `DEC-XXX`.

Page responses do not use this envelope.

---

# Envelope

```json
{
  "success": true,
  "message": "Success",
  "data": {},
  "meta": {},
  "errors": null
}
```

* `success` — operation outcome
* `message` — short, safe to show
* `data` — payload or `null` when there is no entity representation
* `meta` — pagination or extra metadata
* `errors` — field errors on validation failure; `null` otherwise

---

# HTTP status

Use real statuses. Do not rely only on `success`.

* Create: `201`
* Other success: `200`
* Validation: `422`
* Authorization: `403`
* Missing: `404`

Normalize JSON errors onto the same envelope so the client can always read `message` and `errors`.

Do not send ORM models, stack traces, or internal class names.

---

# Pagination meta (Recommended)

* `current_page`
* `per_page`
* `total`
* `last_page`

When wrapping a Laravel paginator, serialize `items()`, not an unsupported collection helper.

---

# Client

Feature services unwrap the envelope and return domain values. Hooks and components do not parse `success` themselves.

See [`../patterns/service.md`](../patterns/service.md)

---

# Related Documents

* [../architecture/data.md](../architecture/data.md)
* [../RULES.md](../RULES.md)
* [../patterns/resource.md](../patterns/resource.md)
