<?php

namespace App\Http\Requests\Creator;

use App\Rules\SafeDigitalUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya seller pemilik produk yang boleh edit
        $product = $this->route('product');
        return auth()->check()
            && auth()->user()->role === 'seller'
            && $product->seller_id === auth()->id();
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
            'image'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery'             => ['nullable', 'array', 'max:7'],
            'gallery.*'           => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'tiktok_video_url'    => ['nullable', 'url', 'max:255'],

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
            'sale_price.lt'            => 'Harga diskon harus lebih kecil dari harga normal.',
            'image.max'                => 'Ukuran thumbnail maksimal 10MB.',
            'gallery.*.max'            => 'Ukuran masing-masing gambar maksimal 10MB.',
            'gallery.*.mimes'          => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'digital_resource.required'=> 'Link produk digital wajib diisi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'   => $this->boolean('is_active', true),
            'is_featured' => $this->boolean('is_featured', false),
        ]);
    }
}
