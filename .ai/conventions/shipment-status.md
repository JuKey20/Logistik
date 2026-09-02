# Shipment status transitions

> Locked graph for `ShipmentStatus`. Decision: DEC-015. Values: [`../TECH_STACK.md`](../TECH_STACK.md).

## Happy path (exactly one step forward)

| From | To |
| --- | --- |
| `menunggu_persetujuan` | `menunggu_penjadwalan` |
| `menunggu_penjadwalan` | `terjadwal` |
| `terjadwal` | `dalam_perjalanan` |
| `dalam_perjalanan` | `proses_pemindahan` |
| `proses_pemindahan` | `dalam_pengiriman` |
| `dalam_pengiriman` | `menunggu_validasi` |
| `menunggu_validasi` | `selesai` |

## Cancel

Any **non-terminal** status may go to `dibatalkan`.

## Terminal (immutable)

`selesai` and `dibatalkan` have **no** outbound transitions. No reopen, no un-cancel, no `selesai` → `dibatalkan`.

## Enforcement

The **backend** must reject illegal transitions. Do not trust a status field from the client. UI hiding is not enough.

**Single source of truth:** shipment status changes only through the dedicated use case / Action (and any cohesive class extracted for that invariant). Do **not** set status via `$shipment->update(['status' => …])` from a controller, a generic mass assignment, or another Action that is not the status/cancel operation.

Details: [`../architecture/backend.md`](../architecture/backend.md), [`transactions.md`](./transactions.md).
