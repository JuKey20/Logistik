# Payment 1:1 and tip

One **payment** per **shipment**. No installments or partial payments.

Payment data **must** include method, actual nominal (>= agreed price to mark paid), validating actor, and bonus/tip (actual − agreed when overpaid; never negative). Underpayment **rejects** `sudah_dibayar` (DEC-018).

Do not invent extra payment rows. DEC-017, [`../conventions/payment.md`](../conventions/payment.md), [`../conventions/payment-status.md`](../conventions/payment-status.md).
