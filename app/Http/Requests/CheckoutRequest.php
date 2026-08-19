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
        $cartService = app(CartService::class);
        $summary = $cartService->getSummary();
        
        $rules = [
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'notes'       => 'nullable|string',
        ];

        // If Guest, require account registration fields
        if (!auth()->check()) {
            $rules['guest_name']     = 'required|string|max:255';
            $rules['guest_email']    = 'required|email|unique:users,email';
            $rules['guest_phone']    = 'required|string|min:9|max:20';
            $rules['guest_password'] = 'required|string|min:6';
        }

        if ($summary['has_physical_product']) {
            if (auth()->check() && request('address_id') && request('address_id') !== 'new') {
                $rules['address_id'] = 'required|exists:addresses,id';
            } else {
                // Requiring new address fields
                $rules['new_address_receiver'] = 'required|string|max:255';
                $rules['new_address_phone']    = 'required|string|max:20';
                $rules['new_address_province'] = 'required|string';
                $rules['new_address_city']     = 'required|string';
                $rules['new_address_district'] = 'required|string';
                $rules['new_address_postal']   = 'required|string|max:10';
                $rules['new_address_full']     = 'required|string|min:10';
                $rules['new_address_lat']      = 'nullable|numeric';
                $rules['new_address_lng']      = 'nullable|numeric';
            }
            $rules['shipping_cost'] = 'nullable|numeric|min:0';
            $rules['courier_name']  = 'nullable|string|max:100';
            $rules['courier_service'] = 'nullable|string|max:100';
        }

        if ($summary['has_service']) {
            $rules['service_address'] = 'required|string|min:10';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'address_id.required'           => 'Alamat pengiriman wajib dipilih untuk produk fisik.',
            'service_address.required'      => 'Alamat pengerjaan wajib diisi untuk layanan jasa.',
            'service_address.min'           => 'Alamat pengerjaan terlalu singkat, mohon lengkapi.',
            'coupon_code.exists'            => 'Kode kupon tidak ditemukan.',
            'guest_email.unique'            => 'Email ini sudah terdaftar. Silakan login terlebih dahulu.',
            'new_address_province.required' => 'Provinsi wajib dipilih.',
            'new_address_city.required'     => 'Kota/Kabupaten wajib dipilih.',
            'new_address_district.required' => 'Kecamatan wajib dipilih.',
            'new_address_full.required'     => 'Alamat lengkap wajib diisi.',
            'new_address_full.min'          => 'Alamat lengkap terlalu singkat.',
        ];
    }
}
