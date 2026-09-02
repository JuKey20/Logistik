# Audit in Actions, not events

Do not add application Domain Events or Listeners in MVP (DEC-024). Events/queues are deferred until a real async side effect (DEC-029), never for audit.

Write **Audit Trail** rows **explicitly and synchronously in the Action** for important changes (status, price, assignment). Same database transaction as the business write when the audit is part of that change.

Details: [`../conventions/audit.md`](../conventions/audit.md), [`../conventions/transactions.md`](../conventions/transactions.md).
