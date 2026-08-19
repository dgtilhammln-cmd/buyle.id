<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case Pending   = 'pending';
    case PickedUp  = 'picked_up';
    case InTransit = 'in_transit';
    case Delivered = 'delivered';
    case Returned  = 'returned';

    /**
     * Mendapatkan label bahasa Indonesia.
     */
    public function label(): string
    {
        return match($this) {
            self::Pending   => 'Menunggu Penjemputan',
            self::PickedUp  => 'Paket Dijemput Kurir',
            self::InTransit => 'Dalam Perjalanan',
            self::Delivered => 'Paket Diterima',
            self::Returned  => 'Paket Dikembalikan',
        };
    }

    /**
     * Ikon untuk tampilan tracking (misal: Heroicons / Font Awesome class).
     */
    public function icon(): string
    {
        return match($this) {
            self::Pending   => 'clock',
            self::PickedUp  => 'archive-box',
            self::InTransit => 'truck',
            self::Delivered => 'check-circle',
            self::Returned  => 'arrow-uturn-left',
        };
    }

    /**
     * Warna badge untuk tampilan UI.
     */
    public function color(): string
    {
        return match($this) {
            self::Pending   => 'gray',
            self::PickedUp  => 'blue',
            self::InTransit => 'purple',
            self::Delivered => 'green',
            self::Returned  => 'red',
        };
    }

    /**
     * Apakah pengiriman sudah selesai (final).
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::Delivered, self::Returned]);
    }

    /**
     * Mendapatkan semua kasus sebagai array [value => label].
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_column(
            array_map(fn(self $case) => ['value' => $case->value, 'label' => $case->label()], self::cases()),
            'label',
            'value'
        );
    }
}
