# Shipment status machine

`ShipmentStatus` changes must follow the linear graph in [`../conventions/shipment-status.md`](../conventions/shipment-status.md) (DEC-015).

`selesai` and `dibatalkan` are **terminal and immutable**.

Validate **on the server**. Do not accept skip, reverse, or client-supplied next status without that check.

Change status **only** through the official use case / Action (plus an extracted invariant class if one exists). Do not mass-assign `status`.
