<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending    = 'pending';
    case Confirmed  = 'confirmed';
    case Processing = 'processing';
    case Shipped    = 'shipped';
    case Delivered  = 'delivered';
    case Cancelled  = 'cancelled';
    case Refunded   = 'refunded';

    /**
     * Mendapatkan label bahasa Indonesia.
     */
    public function label(): string
    {
        return match($this) {
            self::Pending    => 'Menunggu Pembayaran',
            self::Confirmed  => 'Pesanan Dikonfirmasi',
            self::Processing => 'Sedang Diproses',
            self::Shipped    => 'Sedang Dikirim',
            self::Delivered  => 'Pesanan Diterima',
            self::Cancelled  => 'Dibatalkan',
            self::Refunded   => 'Dana Dikembalikan',
        };
    }

    /**
     * Warna badge untuk tampilan UI (Tailwind CSS class atau hex).
     */
    public function color(): string
    {
        return match($this) {
            self::Pending    => 'yellow',
            self::Confirmed  => 'blue',
            self::Processing => 'indigo',
            self::Shipped    => 'purple',
            self::Delivered  => 'green',
            self::Cancelled  => 'red',
            self::Refunded   => 'gray',
        };
    }

    /**
     * Status yang bisa dikonfirmasi oleh admin dari status ini.
     *
     * @return array<self>
     */
    public function nextStatuses(): array
    {
        return match($this) {
            self::Pending    => [self::Confirmed, self::Cancelled],
            self::Confirmed  => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipped],
            self::Shipped    => [self::Delivered],
            self::Delivered  => [self::Refunded],
            self::Cancelled,
            self::Refunded   => [],
        };
    }

    /**
     * Apakah order dalam kondisi aktif (belum selesai/batal).
     */
    public function isActive(): bool
    {
        return ! in_array($this, [self::Cancelled, self::Refunded]);
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
