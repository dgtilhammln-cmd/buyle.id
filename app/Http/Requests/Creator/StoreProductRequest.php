<?php

namespace App\Http\Requests\Creator;

use App\Rules\SafeDigitalUrl;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'seller';
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:200'],
            'short_desc'          => ['nullable', 'string', 'max:500'],
            'description'         => ['nullable', 'string'],
            'price'               => ['required', 'numeric', 'min:0'],
            'sale_price'          => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'creator_group_id'    => ['nullable', 'exists:creator_product_groups,id'],
            'file_type'           => ['nullable', 'string', 'max:50'],
            'image'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // URL produk digital — divalidasi oleh SafeDigitalUrl
            'digital_resource'    => ['required', 'string', 'max:2000', new SafeDigitalUrl()],

            'is_active'           => ['boolean'],
            'is_featured'         => ['boolean'],
            'meta_title'          => ['nullable', 'string', 'max:70'],
            'meta_desc'           => ['nullable', 'string', 'max:160'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Nama produk wajib diisi.',
            'price.required'           => 'Harga produk wajib diisi.',
            'price.min'                => 'Harga tidak boleh negatif.',
            'sale_price.lt'            => 'Harga diskon harus lebih kecil dari harga normal.',
            'digital_resource.required'=> 'Link produk digital wajib diisi.',
            'image.max'                => 'Ukuran thumbnail maksimal 2MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Normalisasi boolean dari checkbox HTML
        $this->merge([
            'is_active'   => $this->boolean('is_active', true),
            'is_featured' => $this->boolean('is_featured', false),
        ]);
    }
}
