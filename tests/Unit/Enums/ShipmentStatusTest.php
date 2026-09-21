<?php

use App\Enums\ShipmentStatus;

test('shipment status exposes the approved values and Indonesian labels', function () {
    expect(ShipmentStatus::options())->toBe([
        ['name' => 'menunggu_persetujuan', 'label' => 'Menunggu Persetujuan'],
        ['name' => 'menunggu_penjadwalan', 'label' => 'Menunggu Penjadwalan'],
        ['name' => 'terjadwal', 'label' => 'Terjadwal'],
        ['name' => 'dalam_perjalanan', 'label' => 'Dalam Perjalanan'],
        ['name' => 'proses_pemindahan', 'label' => 'Proses Pemindahan'],
        ['name' => 'dalam_pengiriman', 'label' => 'Dalam Pengiriman'],
        ['name' => 'menunggu_validasi', 'label' => 'Menunggu Validasi'],
        ['name' => 'selesai', 'label' => 'Selesai'],
        ['name' => 'dibatalkan', 'label' => 'Dibatalkan'],
    ]);
});

test('a shipment starts by waiting for approval', function () {
    expect(ShipmentStatus::initial())->toBe(ShipmentStatus::MenungguPersetujuan);
});

test('non-terminal statuses allow only one forward step or cancellation', function (
    ShipmentStatus $current,
    ShipmentStatus $next,
) {
    expect($current->allowedTransitions())->toBe([$next, ShipmentStatus::Dibatalkan])
        ->and($current->canTransitionTo($next))->toBeTrue()
        ->and($current->canTransitionTo(ShipmentStatus::Dibatalkan))->toBeTrue();
})->with([
    'approval to scheduling' => [ShipmentStatus::MenungguPersetujuan, ShipmentStatus::MenungguPenjadwalan],
    'scheduling to scheduled' => [ShipmentStatus::MenungguPenjadwalan, ShipmentStatus::Terjadwal],
    'scheduled to outbound travel' => [ShipmentStatus::Terjadwal, ShipmentStatus::DalamPerjalanan],
    'outbound travel to transfer' => [ShipmentStatus::DalamPerjalanan, ShipmentStatus::ProsesPemindahan],
    'transfer to delivery' => [ShipmentStatus::ProsesPemindahan, ShipmentStatus::DalamPengiriman],
    'delivery to validation' => [ShipmentStatus::DalamPengiriman, ShipmentStatus::MenungguValidasi],
    'validation to complete' => [ShipmentStatus::MenungguValidasi, ShipmentStatus::Selesai],
]);

test('status transitions reject skips reversals and the current status', function (
    ShipmentStatus $current,
    ShipmentStatus $requested,
) {
    expect($current->canTransitionTo($requested))->toBeFalse();
})->with([
    'skip' => [ShipmentStatus::MenungguPersetujuan, ShipmentStatus::Terjadwal],
    'reverse' => [ShipmentStatus::DalamPengiriman, ShipmentStatus::ProsesPemindahan],
    'same status' => [ShipmentStatus::Terjadwal, ShipmentStatus::Terjadwal],
]);

test('completed and cancelled shipments are terminal and immutable', function (
    ShipmentStatus $terminal,
) {
    expect($terminal->isTerminal())->toBeTrue()
        ->and($terminal->allowedTransitions())->toBe([]);

    foreach (ShipmentStatus::cases() as $requested) {
        expect($terminal->canTransitionTo($requested))->toBeFalse();
    }
})->with([
    'completed' => ShipmentStatus::Selesai,
    'cancelled' => ShipmentStatus::Dibatalkan,
]);

test('non-terminal shipment statuses are not marked terminal', function (
    ShipmentStatus $status,
) {
    expect($status->isTerminal())->toBeFalse();
})->with([
    ShipmentStatus::MenungguPersetujuan,
    ShipmentStatus::MenungguPenjadwalan,
    ShipmentStatus::Terjadwal,
    ShipmentStatus::DalamPerjalanan,
    ShipmentStatus::ProsesPemindahan,
    ShipmentStatus::DalamPengiriman,
    ShipmentStatus::MenungguValidasi,
]);
