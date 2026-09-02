# Payment status machine

`PaymentStatus` is one-way: `belum_dibayar` → `sudah_dibayar`. `sudah_dibayar` is **terminal**.

This machine is **independent of shipment cancellation**. Do not auto-update payment when a shipment is cancelled.

Transition to `sudah_dibayar` **requires** method (`tunai`/`transfer`), actual nominal **>= harga kesepakatan**, and the acting user/admin. Underpayment is **rejected**. Tip = actual − agreed when actual > agreed; tip is never negative (DEC-018). Validate on the **backend**; do not trust client-computed tip or lunas. Details: [`../conventions/payment-status.md`](../conventions/payment-status.md).
