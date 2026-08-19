<?php

namespace App\Rules;

use App\Services\DigitalLinkValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * SafeDigitalUrl
 *
 * Laravel Validation Rule untuk memvalidasi URL produk digital.
 * Digunakan di form tambah/edit produk oleh seller.
 *
 * Usage:
 *   'digital_resource' => ['required', 'string', new SafeDigitalUrl()],
 */
class SafeDigitalUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = app(DigitalLinkValidator::class);
        $result    = $validator->validate((string) $value);

        if (!$result['valid']) {
            $fail($result['reason']);
        }
    }
}
