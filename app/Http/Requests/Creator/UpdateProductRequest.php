<?php

namespace App\Http\Requests\Creator;

use App\Rules\SafeDigitalUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya seller pemilik produk (atau admin) yang boleh edit
        $product = $this->route('product');
        return auth()->check()
            && (
                auth()->user()->role === 'admin' 
                || (auth()->user()->role === 'seller' && $product->seller_id === auth()->id())
            );
    }

    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:200'],
            'short_desc'          => ['nullable', 'string', 'max:500'],
            'description'         => ['nullable', 'string'],
            'price'               => ['required', 'numeric', 'min:0'],
            'sale_price'          => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock'               => ['nullable', 'integer', 'min:0'],
            'product_category_id' => ['nullable', 'exists:product_categories,id'],
            'product_sub_category_id' => ['nullable', 'exists:product_sub_categories,id'],
            'creator_group_id'    => ['nullable', 'exists:creator_product_groups,id'],
            'file_type'           => ['nullable', 'string', 'max:50'],
            'image'               => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery'             => ['nullable', 'array', 'max:6'],
            'product_type'        => ['nullable', 'string', 'in:digital,physical,ticket,external_link'],
            'event_date'          => ['nullable', 'date'],
            'event_time'          => ['nullable', 'string', 'max:100'],
            'event_location'      => ['nullable', 'string', 'max:1000'],
            'event_type'          => ['nullable', 'string', 'in:online,offline'],

            // URL produk digital — divalidasi oleh SafeDigitalUrl jika diisi
            'digital_resource'    => ['nullable', 'string', 'max:2000'],

            'is_active'           => ['boolean'],
            'is_featured'         => ['boolean'],
            'is_whitelabel'       => ['nullable', 'boolean'],
            'whitelabel_price'    => ['nullable', 'numeric', 'min:0'],
            'whitelabel_terms'    => ['nullable', 'string', 'max:2000'],
            'meta_title'          => ['nullable', 'string', 'max:70'],
            'meta_desc'           => ['nullable', 'string', 'max:160'],
            'meta_keywords'       => ['nullable', 'string', 'max:255'],
            'faqs'                => ['nullable', 'array'],
            'faqs.*.question'     => ['nullable', 'string', 'max:500'],
            'faqs.*.answer'       => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Nama produk wajib diisi.',
            'price.required'           => 'Harga produk wajib diisi.',
            'sale_price.lt'            => 'Harga diskon harus lebih kecil dari harga normal.',
            'image.max'                => 'Ukuran thumbnail maksimal 10MB.',
            'gallery.*.max'            => 'Ukuran masing-masing gambar galeri maksimal 10MB.',
            'gallery.*.mimes'          => 'Format gambar galeri harus berupa JPG, JPEG, PNG, atau WEBP.',
            'digital_resource.required'=> 'Link produk digital wajib diisi.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [
            'is_active'   => $this->boolean('is_active', true),
            'is_featured' => $this->boolean('is_featured', false),
        ];

        // Map external_link → digital_resource (form uses external_link)
        if ($this->has('external_link') && $this->input('external_link')) {
            $merge['digital_resource'] = $this->input('external_link');
        } elseif ($this->input('product_type') === 'ticket') {
            if (empty($this->input('digital_resource'))) {
                $merge['digital_resource'] = 'https://buyle.id/ticket';
            }
            if ($this->input('event_type') === 'online') {
                $merge['event_location'] = $this->input('event_location_online') ?: $this->input('event_location');
            } else {
                $merge['event_location'] = $this->input('event_location_offline') ?: $this->input('event_location');
            }
        }

        // Map youtube_video_url → tiktok_video_url (reuse column)
        if ($this->has('youtube_video_url')) {
            $merge['tiktok_video_url'] = $this->input('youtube_video_url');
        }

        // Map meta_description → meta_desc
        if ($this->has('meta_description')) {
            $merge['meta_desc'] = $this->input('meta_description');
        }

        // Filter empty FAQ entries
        if ($this->has('faqs')) {
            $faqs = collect($this->input('faqs', []))
                ->filter(fn($f) => !empty($f['question']) || !empty($f['answer']))
                ->values()
                ->toArray();
            $merge['faqs'] = $faqs ?: null;
        }

        if ($this->has('price') && $this->input('price') !== null) {
            $merge['price'] = preg_replace('/[^\d]/', '', (string)$this->input('price'));
        }

        if ($this->has('sale_price')) {
            $rawSale = preg_replace('/[^\d]/', '', (string)$this->input('sale_price'));
            $merge['sale_price'] = $rawSale !== '' ? $rawSale : null;
        }

        $this->merge($merge);
    }
}
