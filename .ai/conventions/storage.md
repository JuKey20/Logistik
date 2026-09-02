# Storage conventions

> How this project writes files. Policy for agents: [../rules/storage-facade.md](../rules/storage-facade.md). Decision: DEC-013, DEC-014.

## MVP disks

* Default: Laravel `local` via `FILESYSTEM_DISK` (currently `local` in `.env`)
* Public files: Laravel `public` disk, selected **only** through config/env (not a string literal in app code)
* Not in MVP: S3 / GCS as the runtime disk. Switching later must be env/config, not a rewrite of callers

## Application code

Use the `Storage` facade. Resolve the disk from configuration. Do not invent a storage port/interface while the facade is enough.

```php
Storage::disk(config('filesystems.default'))->put($path, $contents);
```

or the default disk:

```php
Storage::put($path, $contents);
```

For the public disk, read the disk name from config (env-backed). Do not write `Storage::disk('public')` or `Storage::disk('local')` in `app/`.

**Proof of delivery is never on the public disk** (DEC-014).

## Proof of delivery (private only)

* Store on the **default/private** disk (`FILESYSTEM_DISK` / `local` in MVP)
* Do not copy, symlink, or serve POD files via public disk or `/storage` URLs
* **MVP default for view/download:** an **authenticated** route, authorized with **Gate/Policy**, streaming via `Storage`
* **Temporary signed URL** remains allowed by DEC-014 (signed HTTP route; object `temporaryUrl` when the configured disk supports it). It is **not** the default MVP path. Prefer it later for cloud disks, not as a second parallel design now

## File vs database consistency

The database and the filesystem / object storage are **not** in the same atomic transaction.

```text
DB transaction
+
explicit filesystem compensation / cleanup
```

* If the file is stored and the **database transaction fails:** best-effort delete or forget that object. `DB::rollBack()` does **not** undo the filesystem.
* If the **database row succeeds** and a later file step fails: do not assume a distributed transaction. Compensate in the Action (retry upload, mark incomplete, or delete the orphan metadata according to that use case). Do not leave a “success” status that requires a file that is not there.

Do not build a two-phase commit or a custom storage abstraction for this.

Validate type, size, and content on the **server**. Client compression (DEC-002) is not a security control.

## Forbidden

* Hardcoded disk names in controllers, services, actions, jobs, listeners, or similar
* Uploading with `move_uploaded_file`, raw `file_put_contents` into `storage/`, or Flysystem drivers constructed in those classes
* Embedding raw storage paths in JSON for private files ([`../patterns/attachment.md`](../patterns/attachment.md))
* Public URLs for bukti pengiriman
* Treating `DB::rollBack()` as file rollback
