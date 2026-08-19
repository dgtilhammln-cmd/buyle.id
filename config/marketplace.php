<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Platform Fee Rate (%)
    |--------------------------------------------------------------------------
    | Persentase komisi platform buyle.id dari setiap transaksi seller.
    | Gunakan environment variable MARKETPLACE_PLATFORM_FEE_RATE untuk override.
    */
    'platform_fee_rate' => env('MARKETPLACE_PLATFORM_FEE_RATE', 10),

    /*
    |--------------------------------------------------------------------------
    | Minimum Payout
    |--------------------------------------------------------------------------
    */
    'minimum_payout' => 50000,
];
