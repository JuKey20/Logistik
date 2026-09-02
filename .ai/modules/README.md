# Modules

Product capabilities live in [`../PROJECT.md`](../PROJECT.md). They are **not** equivalent to architectural bounded contexts or `app/Modules/*` (DEC-029).

Write a folder here **when that capability is implemented**, using [`_template/README.md`](./_template/README.md). Do not pre-create eleven empty module packs.

## Product capabilities (keep these names)

User, Customer (CRM directory), Vehicle (armada), Shipment, Scheduling, Assignment, Payment, POD, Dashboard/Report, Activity Log, My Tasks — plus supporting auth, storage, responsive UI, export.

## MVP implementation grouping

```text
User
Customer
Vehicle
Shipment workflow   ← payment, assignment, POD, scheduling, audit, report, My Tasks may live here in code
```

On the current MVP scope, Customer and Vehicle are reference data. That is not permanent.

## Unresolved (Decision Required)

* Customer vs sender vs recipient vs locations / Address entity
* Availability/overlap rules for drivers and vehicles

Do not invent those in module docs as if they were locked.

## Related

* [`../knowledge/mvp-lock.md`](../knowledge/mvp-lock.md)
* [`../architecture/principles.md`](../architecture/principles.md)
* [`../DECISION_LOG.md`](../DECISION_LOG.md) (DEC-029)
