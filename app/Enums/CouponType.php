<?php

namespace App\Enums;

enum CouponType: string
{
    case Percentage = 'percentage';
    case Fixed      = 'fixed';

    /**
     * Mendapatkan label bahasa Indonesia.
     */
    public function label(): string
    {
        return match($this) {
            self::Percentage => 'Persentase (%)',
            self::Fixed      => 'Nominal Tetap (Rp)',
        };
    }

    /**
     * Menghitung jumlah diskon berdasarkan tipe dan nilai kupon.
     *
     * @param float $subtotal    Total belanja sebelum diskon
     * @param float $maxDiscount Maksimal diskon (untuk tipe percentage)
     */
    public function calculate(float $value, float $subtotal, ?float $maxDiscount = null): float
    {
        $discount = match($this) {
            self::Percentage => $subtotal * ($value / 100),
            self::Fixed      => $value,
        };

        // Terapkan batas maksimal diskon jika ada
        if ($maxDiscount !== null) {
            $discount = min($discount, $maxDiscount);
        }

        // Diskon tidak boleh melebihi subtotal
        return min($discount, $subtotal);
    }

    /**
     * Format tampilan nilai kupon (misal: "10%" atau "Rp 50.000").
     */
    public function formatValue(float $value): string
    {
        return match($this) {
            self::Percentage => number_format($value, 0) . '%',
            self::Fixed      => 'Rp ' . number_format($value, 0, ',', '.'),
        };
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
