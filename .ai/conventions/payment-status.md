# Payment status transitions

> Locked graph for `PaymentStatus`. Decision: DEC-016. Independent from shipment cancellation (DEC-015).

## Graph

| From | To |
| --- | --- |
| `belum_dibayar` | `sudah_dibayar` |

`sudah_dibayar` is **terminal and immutable** (no reverse to `belum_dibayar`).

There is no payment status `dibatalkan`. Cancelling a shipment **must not** change `PaymentStatus`. Marking paid **must not** be inferred from shipment cancel (or blocked solely because the shipment was cancelled—unless a later DEC says so). The two machines do not drive each other.

## Required payload to reach `sudah_dibayar`

The backend **must reject** the transition unless all of these are present and valid:

* `PaymentMethod` — `tunai` or `transfer`
* **Nominal aktual** (actual amount paid)
* **Actor** — the authenticated user/admin who performs the update (persist for history / audit trail)
* **Nominal aktual >= harga kesepakatan perusahaan** (DEC-018). Underpayment is not `sudah_dibayar`. `sudah_dibayar` means **lunas mutlak** (1:1, no cicilan)

UI-only checks are not enough.

## Cardinality (DEC-017)

Exactly **one payment record per shipment**. No installments, no partial payments, no second payment row for the same shipment.

## Payment record shape (DEC-017)

The payment structure **must** include:

* `PaymentMethod` (`tunai` / `transfer`)
* **Nominal aktual** — amount the customer actually handed over
* **Actor** — admin/user who validated the paid status
* **Bonus/tip tim** — **otomatis** dari `max(0, nominal_aktual − harga_kesepakatan)`. If actual > agreed, the difference **is** the tip. Tip **must not be negative**. If actual < agreed, **reject** the paid transition (DEC-018) — do not store a negative tip.

Harga kesepakatan is company-agreed price on the shipment (or its billing), distinct from nominal aktual.

## Enforcement

Same bar as shipment status: the **server** use case / Action (and any extracted payment-rules class) is the source of truth. Frontend arithmetic is UX only.

Do not flip `PaymentStatus` through a generic model update or a mixed shipment payload.
