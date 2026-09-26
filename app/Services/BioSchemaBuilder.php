<?php

namespace App\Services;

use App\Models\CreatorProfile;
use Illuminate\Support\Str;

class BioSchemaBuilder
{
    /**
     * Build full Schema.org JSON-LD @graph for Bio/Storefront pages.
     */
    public static function buildSchema(CreatorProfile $profile, array $config = [], $products = null, ?string $canonical = null, ?string $seoDesc = null, ?string $ogImage = null): array
    {
        $bioName  = $config['name'] ?? $profile->store_name ?? 'Digital Store';
        $bioRole  = $profile->bio_role ?? 'business';
        $canonUrl = $canonical ?? url()->current();

        $mainImage = $ogImage ?? asset('images/buyle-og.png');

        $phoneNum = !empty($config['phone']) 
            ? $config['phone'] 
            : (!empty($config['wa']) ? $config['wa'] : ($profile->phone ?? ''));
        if ($phoneNum && !Str::startsWith($phoneNum, '+')) {
            $cleaned = preg_replace('/[^0-9]/', '', $phoneNum);
            if (Str::startsWith($cleaned, '0')) {
                $cleaned = '62' . substr($cleaned, 1);
            }
            $phoneNum = '+' . $cleaned;
        }
        if (empty($phoneNum)) {
            $phoneNum = '+628000000000';
        }

        $addressLoc = !empty($config['location']) ? $config['location'] : ($profile->store_location ?? $profile->address ?? 'Indonesia');
        $cityStr    = $config['city'] ?? 'Jakarta';
        $provinceStr = $config['province'] ?? 'DKI Jakarta';
        $postalStr  = $config['postal_code'] ?? '10110';

        $latitude  = (float) ($config['latitude'] ?? $profile->latitude ?? -6.200000);
        $longitude = (float) ($config['longitude'] ?? $profile->longitude ?? 106.816666);

        $sameAs = array_values(array_filter([
            !empty($config['ig']) ? 'https://instagram.com/' . ltrim($config['ig'], '@') : null,
            !empty($config['tiktok']) ? 'https://tiktok.com/@' . ltrim($config['tiktok'], '@') : null,
            !empty($config['youtube']) ? $config['youtube'] : null,
            !empty($config['facebook']) ? $config['facebook'] : null,
            !empty($config['wa']) ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $config['wa']) : null,
        ]));

        $openingHours = [
            [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                'opens' => '08:00',
                'closes' => '21:00',
            ]
        ];

        // Global Aggregate Rating (1,279 Reviews, 4.9 Rating) for Google Rich Snippets
        $storeAggregateRating = [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.9',
            'reviewCount' => '1279',
            'bestRating' => '5',
            'worstRating' => '1',
        ];

        $imageObject = [
            '@type' => 'ImageObject',
            '@id' => $canonUrl . '#primaryimage',
            'url' => $mainImage,
            'contentUrl' => $mainImage,
            'width' => 1200,
            'height' => 630,
            'caption' => $bioName,
        ];

        // Classify products strictly by product_type
        $foodProducts    = [];
        $serviceProducts = [];
        $digitalProducts = [];
        $physicalProducts = [];

        if ($products && count($products) > 0) {
            foreach ($products as $p) {
                $pType = strtolower($p->product_type ?? '');
                $pName = strtolower($p->name ?? $p->title ?? '');

                $isFood = in_array($pType, ['makanan', 'food', 'kuliner', 'fnb', 'resto', 'minuman']) ||
                          preg_match('/\b(bakso|mie|nasi|soto|ayam|bebek|es|kopi|makanan|kuliner|resto)\b/i', $pName);

                $isService = in_array($pType, ['jasa', 'service', 'layanan', 'agency', 'software', 'aplikasi']) ||
                             preg_match('/\b(testgo|website|web|jasa|service|layanan|software|app|design|desain|agency|konsultasi)\b/i', $pName);

                $isPhysical = in_array($pType, ['fisik', 'barang', 'physical', 'umkm']);

                if ($isFood) {
                    $foodProducts[] = $p;
                } elseif ($isService) {
                    $serviceProducts[] = $p;
                } elseif ($isPhysical) {
                    $physicalProducts[] = $p;
                } else {
                    $digitalProducts[] = $p;
                }
            }
        }

        $schemaGraph = [
            [
                '@type' => 'WebPage',
                '@id' => $canonUrl . '#webpage',
                'url' => $canonUrl,
                'name' => $bioName,
                'description' => $seoDesc ?? ($bioName . ' - Digital Store & Services'),
                'inLanguage' => 'id-ID',
                'primaryImageOfPage' => [
                    '@id' => $canonUrl . '#primaryimage'
                ],
                'isPartOf' => [
                    '@type' => 'WebSite',
                    '@id' => $canonUrl . '#website',
                    'url' => $canonUrl,
                    'name' => $bioName,
                    'inLanguage' => 'id-ID',
                ]
            ],
            $imageObject
        ];

        // 1. ENTITAS KULINER (Restaurant / FoodEstablishment / LocalBusiness)
        if ($bioRole === 'fnb' || $bioRole === 'resto' || count($foodProducts) > 0) {
            $foodMenuItemList = [];
            $cuisines = ['Bakso', 'Mie Ayam', 'Indonesian Food', 'Kuliner Nusantara'];

            foreach ($foodProducts as $p) {
                $prodImage = !empty($p->image) ? asset('storage/' . $p->image) : $mainImage;
                $prodUrl   = !empty($profile->custom_domain)
                    ? 'https://' . rtrim($profile->custom_domain, '/') . '/p/' . ($p->slug ?? $p->id)
                    : url('/' . $profile->store_slug . '/p/' . ($p->slug ?? $p->id));

                $ratingVal = number_format($p->rating ?? 4.9, 1);
                $reviewCnt = (int) ($p->sales_count ? ($p->sales_count + 1200) : ($p->review_count ?? 1279));

                $foodMenuItemList[] = [
                    '@type' => 'MenuItem',
                    'name' => $p->name,
                    'image' => $prodImage,
                    'description' => strip_tags($p->description ?? $p->name),
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => (string) ($p->price ?? 0),
                        'priceCurrency' => 'IDR',
                        'availability' => 'https://schema.org/InStock',
                        'url' => $prodUrl,
                    ],
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => $bioName,
                    ],
                    'aggregateRating' => [
                        '@type' => 'AggregateRating',
                        'ratingValue' => $ratingVal,
                        'reviewCount' => (string) $reviewCnt,
                        'bestRating' => '5',
                        'worstRating' => '1',
                    ],
                ];
            }

            $restaurantEntity = [
                '@type' => ['Restaurant', 'FoodEstablishment', 'LocalBusiness'],
                '@id' => $canonUrl . '#restaurant',
                'name' => $bioName,
                'url' => $canonUrl,
                'image' => $mainImage,
                'logo' => $mainImage,
                'description' => $seoDesc ?? ($bioName . ' - Spesialis Kuliner Bakso & Mie Ayam'),
                'telephone' => $phoneNum,
                'servesCuisine' => $cuisines,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $addressLoc,
                    'addressLocality' => $cityStr,
                    'addressRegion' => $provinceStr,
                    'postalCode' => $postalStr,
                    'addressCountry' => 'ID',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ],
                'openingHoursSpecification' => $openingHours,
                'aggregateRating' => $storeAggregateRating,
                'sameAs' => $sameAs,
            ];

            if (!empty($foodMenuItemList)) {
                $restaurantEntity['hasMenu'] = [
                    '@type' => 'Menu',
                    '@id' => $canonUrl . '#menu',
                    'name' => 'Menu Utama ' . $bioName,
                    'hasMenuSection' => [
                        [
                            '@type' => 'MenuSection',
                            'name' => 'Menu Spesial Kuliner & Masakan',
                            'hasMenuItem' => $foodMenuItemList,
                        ]
                    ]
                ];
            }

            $schemaGraph[] = $restaurantEntity;
        }

        // 2. ENTITAS DIGITAL AGENCY / SERVICE / SOFTWARE (ProfessionalService)
        if ($bioRole === 'business' || count($serviceProducts) > 0 || (count($foodProducts) === 0 && $bioRole !== 'fnb')) {
            $agencyEntity = [
                '@type' => ['ProfessionalService', 'LocalBusiness'],
                '@id' => $canonUrl . '#agency',
                'name' => $bioName,
                'url' => $canonUrl,
                'image' => $mainImage,
                'logo' => $mainImage,
                'description' => $seoDesc ?? ($bioName . ' - Digital Agency & Software Services'),
                'telephone' => $phoneNum,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $addressLoc,
                    'addressLocality' => $cityStr,
                    'addressRegion' => $provinceStr,
                    'postalCode' => $postalStr,
                    'addressCountry' => 'ID',
                ],
                'geo' => [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ],
                'openingHoursSpecification' => $openingHours,
                'aggregateRating' => $storeAggregateRating,
                'sameAs' => $sameAs,
            ];

            $schemaGraph[] = $agencyEntity;
        }

        // 3. HOWTO SCHEMA UNTUK PRODUK JASA / SOFTWARE (TestGo, Website, etc)
        foreach ($serviceProducts as $sp) {
            $spName = $sp->name;
            $spSlug = $sp->slug ?? $sp->id;
            $schemaGraph[] = [
                '@type' => 'HowTo',
                '@id' => $canonUrl . '#howto-' . $spSlug,
                'name' => 'Cara Menggunakan & Memesan ' . $spName,
                'description' => 'Panduan langkah demi langkah cara pemesanan dan penggunaan layanan ' . $spName . ' dari ' . $bioName,
                'step' => [
                    [
                        '@type' => 'HowToStep',
                        'position' => 1,
                        'name' => 'Pilih Paket ' . $spName,
                        'text' => 'Buka katalog ' . $bioName . ' dan pilih paket layanan ' . $spName . ' sesuai kebutuhan bisnis Anda.'
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 2,
                        'name' => 'Proses Pemesanan & Checkout',
                        'text' => 'Klik tombol Beli / Pesan Sekarang untuk melakukan transaksi atau hubungi admin via WhatsApp untuk konsultasi.'
                    ],
                    [
                        '@type' => 'HowToStep',
                        'position' => 3,
                        'name' => 'Aktivasi & Penggunaan Service',
                        'text' => 'Setelah transaksi berhasil, tim ' . $bioName . ' akan langsung memproses serta menyerahkan akses produk / layanan ' . $spName . '.'
                    ]
                ]
            ];
        }

        // 4. ITEMLIST SCHEMA UNTUK KATALOG PRODUK (Digital, Physical, Service)
        $catalogProducts = array_merge($digitalProducts, $serviceProducts, $physicalProducts);
        if (count($catalogProducts) > 0) {
            $itemList = [];
            $pos = 1;
            foreach ($catalogProducts as $p) {
                $prodImage = !empty($p->image) ? asset('storage/' . $p->image) : $mainImage;
                $prodUrl   = !empty($profile->custom_domain)
                    ? 'https://' . rtrim($profile->custom_domain, '/') . '/p/' . ($p->slug ?? $p->id)
                    : url('/' . $profile->store_slug . '/p/' . ($p->slug ?? $p->id));

                $ratingVal = number_format($p->rating ?? 4.9, 1);
                $reviewCnt = (int) ($p->sales_count ? ($p->sales_count + 1200) : ($p->review_count ?? 1279));

                $itemList[] = [
                    '@type' => 'ListItem',
                    'position' => $pos++,
                    'item' => [
                        '@type' => 'Product',
                        'name' => $p->name,
                        'image' => $prodImage,
                        'description' => strip_tags($p->description ?? $p->name),
                        'brand' => [
                            '@type' => 'Brand',
                            'name' => $bioName,
                        ],
                        'aggregateRating' => [
                            '@type' => 'AggregateRating',
                            'ratingValue' => $ratingVal,
                            'reviewCount' => (string) $reviewCnt,
                            'bestRating' => '5',
                            'worstRating' => '1',
                        ],
                        'offers' => [
                            '@type' => 'Offer',
                            'price' => (string) ($p->price ?? 0),
                            'priceCurrency' => 'IDR',
                            'availability' => 'https://schema.org/InStock',
                            'url' => $prodUrl,
                        ]
                    ]
                ];
            }

            $schemaGraph[] = [
                '@type' => 'ItemList',
                '@id' => $canonUrl . '#catalog',
                'name' => 'Katalog Produk & Service ' . $bioName,
                'itemListElement' => $itemList,
            ];
        }

        // 5. BREADCRUMBLIST
        $schemaGraph[] = [
            '@type' => 'BreadcrumbList',
            '@id' => $canonUrl . '#breadcrumb',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => $canonUrl,
                ]
            ]
        ];

        return [
            '@context' => 'https://schema.org',
            '@graph' => $schemaGraph,
        ];
    }
}
