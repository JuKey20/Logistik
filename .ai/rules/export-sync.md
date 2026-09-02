# Synchronous Excel export

MVP data export uses **`maatwebsite/excel`** on the **backend** and returns a **direct download** in the same HTTP request.

Do **not** dispatch a Queue or Background Job for export. Do not add a download center or “export ready” notification (DEC-010 already skipped product notifications).

Keep the export small enough for a synchronous request. Details: DEC-020, [`../TECH_STACK.md`](../TECH_STACK.md).
