# MVP is fully synchronous

Do not add `ShouldQueue`, application Job classes, or queued listeners for MVP (DEC-021).

Do not require **Queue Workers** in deployment (no Supervisor daemon, no `php artisan queue:work`, no Horizon).

Leave the Laravel `jobs` table in the schema for later scale-out. Do not delete it. Do not build product features that only work if a worker is running.

Events/queues are **deferred** until a proven async/non-critical side effect needs them (DEC-029). Do not treat that as a lifetime ban.

Details: [`../TECH_STACK.md`](../TECH_STACK.md), [`../conventions/queue.md`](../conventions/queue.md), DEC-020 (exports), DEC-010 (no notifications).
