# Eloquent in Actions; scopes for complex queries

No Repository (DEC-023, DEC-029). Actions may use Eloquent directly.

If a query is long or complex, **move it into an Eloquent local scope**. Keep Actions free of stacked query-builder chains.

A query object is allowed later if a scope is no longer readable. Do not create `app/Queries` speculatively.

Details: [`../architecture/backend.md`](../architecture/backend.md).
