<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\CartService;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Allow guests
    }

    public function rules(): array
    {
        return [
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'notes'       => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'coupon_code.exists' => 'Kode kupon tidak valid atau tidak ditemukan.',
        ];
    }
}
