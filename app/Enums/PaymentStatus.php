<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending  = 'pending';
    case Success  = 'success';
    case Failed   = 'failed';
    case Expired  = 'expired';
    case Refunded = 'refunded';

    /**
     * Mendapatkan label bahasa Indonesia.
     */
    public function label(): string
    {
        return match($this) {
            self::Pending  => 'Menunggu Pembayaran',
            self::Success  => 'Pembayaran Berhasil',
            self::Failed   => 'Pembayaran Gagal',
            self::Expired  => 'Pembayaran Kedaluwarsa',
            self::Refunded => 'Dana Dikembalikan',
        };
    }

    /**
     * Warna badge untuk tampilan UI.
     */
    public function color(): string
    {
        return match($this) {
            self::Pending  => 'yellow',
            self::Success  => 'green',
            self::Failed   => 'red',
            self::Expired  => 'orange',
            self::Refunded => 'gray',
        };
    }

    /**
     * Apakah pembayaran sudah berhasil (lunas).
     */
    public function isPaid(): bool
    {
        return $this === self::Success;
    }

    /**
     * Apakah pembayaran sudah final (tidak bisa berubah lagi).
     */
    public function isFinal(): bool
    {
        return in_array($this, [self::Success, self::Failed, self::Expired, self::Refunded]);
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
