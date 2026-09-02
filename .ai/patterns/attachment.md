# Attachment Pattern

> Private files: authorized preview and download. No public URLs, no raw storage keys in JSON.

This is a **file security** pattern. It is not a product workflow (staging tables, one-to-one attachment rules, or publish/completeness). Those stay project-specific.

Disks: follow DEC-013 / `conventions/storage.md`. Never hardcode disk names.

**Bukti pengiriman (DEC-014):** wajib disk **private** (default `local`). **Dilarang** disk `public`, `storage:link` URL, atau path `/storage/...` untuk file ini.

**MVP default view/download:** route terautentikasi + **Policy/Gate** + stream dari `Storage`.

Temporary signed URL tetap diizinkan DEC-014; **bukan** jalur default MVP. Rincian konsistensi file/DB: [`../conventions/storage.md`](../conventions/storage.md).

Knowing an attachment id is not authorization.

---

# UI

A shared list, not a data table.

Typical actions, each gated by authorization:

1. **Preview** — only if the API sends `can_preview: true`
2. **Download** — for callers who may read the file
3. **Delete** — only if the consumer passes a handler; may be stricter than read

Show original name, size, optional caption. Truncate names without pushing actions out of the row (`min-w-0`, `truncate`, actions `shrink-0`).

The browser does not decide previewable MIME types. The backend does.

---

# Private access

* Preview: `Content-Disposition: inline` plus `X-Content-Type-Options: nosniff`
* Download: `Content-Disposition: attachment`
* Both use the original filename
* Active content (HTML, SVG) is not inlined
* Unsupported preview types: `415`

Knowing an attachment id is not authorization. Use the same policy as the owning resource (or a dedicated file policy). Signed URLs must expire and must not be treated as a public CDN.

---

# Related Documents

* [../architecture/security.md](../architecture/security.md)
* [table.md](./table.md)
