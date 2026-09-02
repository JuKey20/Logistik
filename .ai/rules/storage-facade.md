# Storage facade; no hardcoded disks

All file upload, read, and delete in application code **must** use Laravel `Storage` and a disk name from **config/env**.

Do **not** hardcode disk names (`'local'`, `'public'`, `'s3'`, `'gcs'`, …) in controllers, services, actions, jobs, or listeners.

MVP disks are `local` and `public` (DEC-013). Future S3/GCS is an env change, not a code change in callers. Details: [`../conventions/storage.md`](../conventions/storage.md), [`../TECH_STACK.md`](../TECH_STACK.md).
