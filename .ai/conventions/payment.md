# Payment record (1:1)

Shipment and payment are **one-to-one**. No cicilan or partial payments (DEC-017).

Required on the payment side: method, actual nominal, validating actor, and **bonus/tip tim**.

`sudah_dibayar` = **lunas mutlak**: `nominal aktual >= harga kesepakatan`. If actual is greater, tip = actual − agreed (never negative). If actual is less, **reject** the transition (DEC-018).

Do not add a second payment row for the same shipment. Details: [`payment-status.md`](./payment-status.md).

The backend is the only authority for lunas, tip, and rejection of underpayment. Do not persist payment status from a client-computed tip.
