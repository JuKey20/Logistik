# Action Pattern

> **Required** for this project (DEC-022): one business operation, HTTP-agnostic. Architecture: DEC-029.

Simple settings CRUD in the starter may stay thinner. Product domain mutations (shipment, payment, assignment, POD, users/roles) go through an Action.

Fortify contract Actions under `app/Actions/Fortify/` are the exception.

`Item` in examples is a documentation stand-in.

---

# Purpose

An Action **orchestrates** a use case. It is isolated from the HTTP cycle. It receives an array, primitives, or an optional DTO, and returns a domain value. It never redirects or writes the session.

A reusable or complex business invariant may be extracted into a cohesive dedicated class when justified (complexity, criticality, readability, duplication, reuse, testability, or scatter risk). Duplication across Actions is one signal, not a quota. Do not prescribe `app/Domain` as the home for those classes.

---

# Location

```text
app/Actions/{Domain}/{Verb}{Entity}Action.php
```

`{Domain}` here is an implementation grouping (User, Customer, Vehicle, Shipment), not a product-capability package.

---

# Shape

```php
final class CreateItemAction
{
    public function handle(array $data): Item
    {
        return Item::query()->create($data);
    }
}
```

The Action must persist **only the fields that use case owns**. Do not mass-assign `status`, `role`, assignment, or audit columns from a generic array.

Complex reads use **local scopes** on the model (DEC-023). Do not add a Repository. A query object is a later escape hatch when a scope is no longer readable — do not create `app/Queries` speculatively.

---

# Rules

* One public method `handle()` with an explicit return type
* Constructor injection, `private readonly`
* Input as array/primitives; DTO optional
* `DB::transaction()` when operations must be one atomic business change, or when consistency/concurrency needs it — not “because there are two tables”. [`../conventions/transactions.md`](../conventions/transactions.md)
* Log identifiers after success — never passwords or file contents

---

# Must not contain

* `request()`, `response()`, `redirect()`, session read/write
* `Illuminate\Http\Request`, `Response`, Redirect responses
* Input validation (Form Request / controller)
* Authorization (Policy/Gate on the HTTP layer)
* Response serialization
* Eloquent query piles (filters, aggregations, joins) — those belong in **local scopes** (or a later query object)

Important mutations (status, price, assignment) **must** write audit history in this Action, same request, typically same database transaction (DEC-024).

---

# Testing

Prefer feature tests through the HTTP endpoint. Add a unit test for extracted invariant classes or branching that is hard to reach over HTTP.

---

# Related Documents

* [repository.md](./repository.md)
* [dto.md](./dto.md)
* [../architecture/backend.md](../architecture/backend.md)
* [../architecture/principles.md](../architecture/principles.md)
* [../RULES.md](../RULES.md)
