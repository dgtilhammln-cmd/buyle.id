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
        $rules = [
            'coupon_code'          => 'nullable|string|exists:coupons,code',
            'notes'                => 'nullable|string',
            'shipping_cost'        => 'nullable|numeric|min:0',
            'address_id'           => 'nullable|string',
            'new_address_receiver' => 'nullable|string|max:100',
            'new_address_phone'    => 'nullable|string|max:20',
            'new_address_province' => 'nullable|string|max:100',
            'new_address_city'     => 'nullable|string|max:100',
            'new_address_district' => 'nullable|string|max:100',
            'new_address_postal'   => 'nullable|string|max:20',
            'new_address_label'    => 'nullable|string|max:50',
            'new_address_full'     => 'nullable|string|max:1000',
            'courier_name'         => 'nullable|string|max:50',
            'courier_service'      => 'nullable|string|max:50',
        ];

        if (!auth()->check()) {
            $rules['guest_name']     = 'required|string|max:100';
            $rules['guest_email']    = 'required|email';
            $rules['guest_phone']    = 'required|string|max:20';
            $rules['guest_password'] = 'nullable|string|min:6';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'coupon_code.exists'   => 'Kode kupon tidak valid atau tidak ditemukan.',
            'guest_name.required'  => 'Nama lengkap wajib diisi.',
            'guest_email.required' => 'Email wajib diisi.',
            'guest_email.email'    => 'Format email tidak valid.',
            'guest_phone.required' => 'No. WhatsApp wajib diisi.',
            'guest_password.min'   => 'Kata sandi minimal 6 karakter.',
        ];
    }
}
