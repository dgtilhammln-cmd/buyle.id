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
            'og_image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery'             => ['nullable', 'array', 'max:6'],
            'product_type'        => ['nullable', 'string', 'in:digital,physical,makanan,service,ticket,external_link'],
            'sku'                 => ['nullable', 'string', 'max:100'],
            'weight'              => ['nullable', 'integer', 'min:0'],
            'length'              => ['nullable', 'numeric', 'min:0'],
            'width'               => ['nullable', 'numeric', 'min:0'],
            'height'              => ['nullable', 'numeric', 'min:0'],
            'volume'              => ['nullable', 'numeric', 'min:0'],
            'event_date'          => ['nullable', 'date'],
            'event_time'          => ['nullable', 'string', 'max:100'],
            'event_location'      => ['nullable', 'string', 'max:1000'],
            'event_type'          => ['nullable', 'string', 'in:online,offline'],
            'event_platform'      => ['nullable', 'string', 'max:100'],
            'ticket_war_start'      => ['nullable', 'date'],
            'max_tickets_per_order' => ['nullable', 'integer', 'min:1', 'max:20'],

            // Syarat klaim produk gratis (Follow sosmed & Join grup)
            'require_follow_join'   => ['nullable', 'boolean'],
            'follow_instagram_url'  => ['nullable', 'url', 'max:500'],
            'follow_tiktok_url'     => ['nullable', 'url', 'max:500'],
            'follow_x_url'          => ['nullable', 'url', 'max:500'],
            'follow_youtube_url'    => ['nullable', 'url', 'max:500'],
            'join_whatsapp_url'     => ['nullable', 'url', 'max:500'],
            'join_telegram_url'     => ['nullable', 'url', 'max:500'],
            'join_discord_url'      => ['nullable', 'url', 'max:500'],
            'social_follow_url'     => ['nullable', 'url', 'max:500'],
            'community_join_url'    => ['nullable', 'url', 'max:500'],

            // URL produk digital — divalidasi oleh SafeDigitalUrl jika diisi
            'digital_resource'    => ['nullable', 'string', 'max:2000'],

            // Exclusive Creator rating & sold count override
            'rating'                    => ['nullable', 'numeric', 'min:0', 'max:5'],
            'sold_count'                => ['nullable', 'integer', 'min:0'],

            'is_active'           => ['boolean'],
            'is_featured'         => ['boolean'],
            'is_whitelabel'             => ['nullable', 'boolean'],
            'whitelabel_price'          => ['nullable', 'numeric', 'min:0'],
            'whitelabel_terms'          => ['nullable', 'string', 'max:2000'],
            'affiliate_commission_rate' => ['nullable', 'numeric', 'min:5', 'max:100'],
            'meta_title'                => ['nullable', 'string', 'max:255'],
            'meta_desc'                 => ['nullable', 'string', 'max:1000'],
            'meta_keywords'             => ['nullable', 'string', 'max:500'],
            'variants'                  => ['nullable', 'array', 'max:7'],
            'variants.*.id'             => ['nullable'],
            'variants.*.name'           => ['nullable', 'string', 'max:100'],
            'variants.*.price'          => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock'          => ['nullable', 'integer', 'min:0'],
            'variants.*.sku'            => ['nullable', 'string', 'max:100'],
            'variants.*.image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'variants.*.existing_image' => ['nullable', 'string'],
            'faqs'                      => ['nullable', 'array'],
            'faqs.*.question'           => ['nullable', 'string', 'max:500'],
            'faqs.*.answer'             => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            if ($this->boolean('require_follow_join')) {
                $hasAny = !empty($this->input('follow_instagram_url'))
                    || !empty($this->input('follow_tiktok_url'))
                    || !empty($this->input('follow_x_url'))
                    || !empty($this->input('follow_youtube_url'))
                    || !empty($this->input('join_whatsapp_url'))
                    || !empty($this->input('join_telegram_url'))
                    || !empty($this->input('join_discord_url'))
                    || !empty($this->input('social_follow_url'))
                    || !empty($this->input('community_join_url'));

                if (!$hasAny) {
                    $v->errors()->add('require_follow_join', 'Karena syarat klaim gratis diaktifkan, mohon isi minimal salah satu platform sosmed atau komunitas.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required'                  => 'Nama produk wajib diisi.',
            'price.required'                 => 'Harga produk wajib diisi.',
            'sale_price.lt'                  => 'Harga diskon harus lebih kecil dari harga normal.',
            'affiliate_commission_rate.min'  => 'Komisi affiliate minimal 5%.',
            'affiliate_commission_rate.max'  => 'Komisi affiliate maksimal 100%.',
            'image.max'                      => 'Ukuran thumbnail maksimal 10MB.',
            'gallery.*.max'                  => 'Ukuran masing-masing gambar galeri maksimal 10MB.',
            'gallery.*.mimes'                => 'Format gambar galeri harus berupa JPG, JPEG, PNG, atau WEBP.',
            'digital_resource.required'      => 'Link produk digital wajib diisi.',
            'meta_title.max'                 => 'Meta Title SEO maksimal 255 karakter.',
            'meta_desc.max'                  => 'Meta Description SEO maksimal 1000 karakter.',
            'meta_keywords.max'              => 'Meta Keywords SEO maksimal 500 karakter.',
            'variants.max'                   => 'Maksimal 7 variasi per produk.',
            'follow_instagram_url.url'       => 'Format URL Instagram tidak valid (harus diawali http:// atau https://).',
            'follow_tiktok_url.url'          => 'Format URL TikTok tidak valid (harus diawali http:// atau https://).',
            'follow_x_url.url'               => 'Format URL X / Twitter tidak valid (harus diawali http:// atau https://).',
            'follow_youtube_url.url'         => 'Format URL YouTube tidak valid (harus diawali http:// atau https://).',
            'join_whatsapp_url.url'          => 'Format URL WhatsApp tidak valid (harus diawali http:// atau https://).',
            'join_telegram_url.url'          => 'Format URL Telegram tidak valid (harus diawali http:// atau https://).',
            'join_discord_url.url'           => 'Format URL Discord tidak valid (harus diawali http:// atau https://).',
            'social_follow_url.url'          => 'Format URL akun sosmed tidak valid (harus diawali http:// atau https://).',
            'community_join_url.url'         => 'Format URL grup komunitas tidak valid (harus diawali http:// atau https://).',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [
            'is_active'          => $this->boolean('is_active', true),
            'is_featured'        => $this->boolean('is_featured', false),
            'is_buyle_checkout' => $this->boolean('is_buyle_checkout', true),
            'require_follow_join' => $this->boolean('require_follow_join', false),
            'stock'       => ($this->has('stock') && $this->input('stock') !== null && $this->input('stock') !== '') ? (int) $this->input('stock') : null,
            'weight'      => (int) ($this->input('weight') ?? 0),
            'length'      => (float) ($this->input('length') ?? 0),
            'width'       => (float) ($this->input('width') ?? 0),
            'height'      => (float) ($this->input('height') ?? 0),
            'volume'      => (float) ($this->input('volume') ?? 0),
        ];

        if ($this->has('meta_title') && $this->input('meta_title')) {
            $merge['meta_title'] = \Illuminate\Support\Str::limit(trim($this->input('meta_title')), 255, '');
        }

        if ($this->has('affiliate_commission_rate') && $this->input('affiliate_commission_rate') !== null) {
            $val = (float) $this->input('affiliate_commission_rate');
            $merge['affiliate_commission_rate'] = $val < 5 ? 5.00 : $val;
        }

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
        if ($this->has('meta_description') && $this->input('meta_description')) {
            $merge['meta_desc'] = \Illuminate\Support\Str::limit(strip_tags((string)$this->input('meta_description')), 160, '');
        } elseif ($this->has('meta_desc') && $this->input('meta_desc')) {
            $merge['meta_desc'] = \Illuminate\Support\Str::limit(strip_tags((string)$this->input('meta_desc')), 160, '');
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

        if ($this->has('variants') && is_array($this->input('variants'))) {
            $cleanedVariants = $this->input('variants');
            foreach ($cleanedVariants as $k => $v) {
                if (isset($v['price']) && $v['price'] !== null && $v['price'] !== '') {
                    $cleanedVariants[$k]['price'] = preg_replace('/[^\d]/', '', (string)$v['price']);
                }
                if (isset($v['stock']) && $v['stock'] !== null && $v['stock'] !== '') {
                    $cleanedVariants[$k]['stock'] = (int) $v['stock'];
                }
            }
            $merge['variants'] = $cleanedVariants;
        }

        $this->merge($merge);
    }
}
