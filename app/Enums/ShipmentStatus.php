<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case MenungguPersetujuan = 'menunggu_persetujuan';
    case MenungguPenjadwalan = 'menunggu_penjadwalan';
    case Terjadwal = 'terjadwal';
    case DalamPerjalanan = 'dalam_perjalanan';
    case ProsesPemindahan = 'proses_pemindahan';
    case DalamPengiriman = 'dalam_pengiriman';
    case MenungguValidasi = 'menunggu_validasi';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public static function initial(): self
    {
        return self::MenungguPersetujuan;
    }

    public function label(): string
    {
        return match ($this) {
            self::MenungguPersetujuan => 'Menunggu Persetujuan',
            self::MenungguPenjadwalan => 'Menunggu Penjadwalan',
            self::Terjadwal => 'Terjadwal',
            self::DalamPerjalanan => 'Dalam Perjalanan',
            self::ProsesPemindahan => 'Proses Pemindahan',
            self::DalamPengiriman => 'Dalam Pengiriman',
            self::MenungguValidasi => 'Menunggu Validasi',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    /**
     * @return list<array{name: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            static fn (self $status): array => [
                'name' => $status->value,
                'label' => $status->label(),
            ],
            self::cases(),
        );
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Selesai, self::Dibatalkan], true);
    }

    /**
     * @return list<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::MenungguPersetujuan => [self::MenungguPenjadwalan, self::Dibatalkan],
            self::MenungguPenjadwalan => [self::Terjadwal, self::Dibatalkan],
            self::Terjadwal => [self::DalamPerjalanan, self::Dibatalkan],
            self::DalamPerjalanan => [self::ProsesPemindahan, self::Dibatalkan],
            self::ProsesPemindahan => [self::DalamPengiriman, self::Dibatalkan],
            self::DalamPengiriman => [self::MenungguValidasi, self::Dibatalkan],
            self::MenungguValidasi => [self::Selesai, self::Dibatalkan],
            self::Selesai, self::Dibatalkan => [],
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }
}
