# Private proof-of-delivery files

Bukti pengiriman **must** live on the **private** disk. **Never** expose them on a public URL, the `public` disk, or `/storage/...`.

**MVP default** for view/download: an **authenticated** route, **Gate/Policy**, streaming through `Storage`.

A temporary signed URL remains allowed (DEC-014) but is **not** the default MVP path.

Database rollback does not undo stored files. Compensation: [`../conventions/storage.md`](../conventions/storage.md).

Do not return permanent public links. Details: DEC-014, [`../patterns/attachment.md`](../patterns/attachment.md).
