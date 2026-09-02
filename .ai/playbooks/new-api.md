# Playbook: New JSON Endpoint

> Add an asynchronous JSON endpoint consumed by the same application's frontend.

---

# Context

This playbook assumes session JSON on the web stack when a screen needs async JSON, not a public API. Follow `TECH_STACK.md` and DEC-006/007.

Do not adopt the REST envelope unless a DEC says so. Prefer Inertia Form / `useHttp`.

---

# Steps

1. Write the contract in the module API doc.
2. Add validation when there is a payload or query. Authorize in that validator, or in the adapter when there is no payload.
3. Add an Action for product mutations.
4. Keep the controller thin. Do not return models.
5. Register the route with a stable name.
6. Regenerate typed routes; wire the client with Wayfinder + Inertia.
7. Tests: success, validation, authorization, missing resource.

---

# Response

Inertia pages: props, not [`../conventions/response.md`](../conventions/response.md).

JSON envelope: **not adopted** for MVP. If a later DEC adopts it, follow that file.

Do not return models. Use correct HTTP status codes.

---

# Checklist

- [ ] Contract documented
- [ ] Server-side authorization
- [ ] Validation when there is input
- [ ] Adapter has no queries or business rules
- [ ] Relations eager-loaded for the serializer
- [ ] Client uses generated URLs
- [ ] Tests cover success and failure

---

# Related Documents

* [../conventions/response.md](../conventions/response.md)
* [../patterns/resource.md](../patterns/resource.md)
* [../patterns/service.md](../patterns/service.md)
