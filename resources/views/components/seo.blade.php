{{--
    SEO Component — Auto-Generated SEO, AEO (JSON-LD) & GEO
    Variables (semua optional): $seo[], $schema, $breadcrumbs[]
--}}
@php
    $seoData        = $seo        ?? [];
    $schemaData     = $schema     ?? null;
    $breadcrumbData = $breadcrumbs ?? [];
    $appUrl         = rtrim(config('app.url'), '/');

    // Get Company Profiles from Settings
    $settings = \App\Models\Setting::getAllAsArray();
    $siteName = $settings['site_name'] ?? 'buyle.id';
    $tagline  = $settings['tagline']   ?? 'Semua Kebutuhan Rumah, Satu Tempat';
    $domain   = $settings['domain']    ?? 'buyle.id';
    $shortDesc= $settings['short_desc']?? 'Toko online buyle.id tangga terlengkap.';
    $addressDetail = $settings['address'] ?? '';
    $village  = $settings['village_name'] ?? '';
    $district = $settings['district_name'] ?? '';
    $city     = $settings['city_name'] ?? 'Surabaya';
    $province = $settings['province_name'] ?? 'Jawa Timur';
    $postal   = $settings['postal_code'] ?? '';
    
    $waNumber = $settings['phone'] ?? '';
    $email    = $settings['email'] ?? 'info@' . $domain;

    $parts = array_filter([$addressDetail, $village, $district]);
    $streetAddress = !empty($parts) ? implode(', ', $parts) : $city;
    // Auto-Fallback Logic
    $autoTitle = !empty($seoData['title']) ? $seoData['title'] : ($siteName . ' - ' . $tagline);
    $autoDesc  = !empty($seoData['description']) ? $seoData['description'] : $shortDesc;
    $autoKeywords = !empty($seoData['keywords']) ? $seoData['keywords'] : (!empty($settings['meta_keywords_home']) ? $settings['meta_keywords_home'] : 'buyle.id, digital marketplace, produk digital, creator, jual beli digital');

    // Canonical — always use app.url, never localhost
    $rawCanonical   = $seoData['canonical'] ?? url()->current();
    $canonical      = preg_replace('#^https?://[^/]+#', $appUrl, $rawCanonical);

    // OG Image — make absolute using app.url
    $defaultOg      = !empty($settings['logo']) ? $appUrl . '/storage/' . ltrim($settings['logo'], '/') : $appUrl . '/favicon.ico';
    $rawOg          = $seoData['og_image'] ?? $defaultOg;
    $ogImage        = preg_match('#^https?://#', $rawOg)
                        ? preg_replace('#^https?://[^/]+#', $appUrl, $rawOg)
                        : $appUrl . '/' . ltrim($rawOg, '/');
@endphp

<title>{{ $autoTitle }}</title>
<meta name="description" content="{{ $autoDesc }}">
<meta name="keywords" content="{{ $autoKeywords }}">
<meta name="author" content="{{ $siteName }}">
<meta name="publisher" content="{{ $siteName }}">
<meta name="robots" content="{{ $seoData['robots'] ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' }}">
<link rel="canonical" href="{{ $canonical }}">

@if(!empty($settings['google_search_console']))
    {!! $settings['google_search_console'] !!}
@endif

{{-- Open Graph --}}
<meta property="og:type"         content="{{ $seoData['og_type'] ?? 'website' }}">
<meta property="og:title"        content="{{ $autoTitle }}">
<meta property="og:description"  content="{{ $autoDesc }}">
<meta property="og:image"        content="{{ $ogImage }}">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt"    content="{{ $autoTitle }}">
<meta property="og:url"          content="{{ $canonical }}">
<meta property="og:site_name"    content="{{ $siteName }}">
<meta property="og:locale"       content="{{ $seoData['locale_og'] ?? 'id_ID' }}">

{{-- Twitter Card --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $autoTitle }}">
<meta name="twitter:description" content="{{ $autoDesc }}">
<meta name="twitter:image"       content="{{ $ogImage }}">

{{-- Article-specific meta tags --}}
@if(!empty($seoData['article_published']))
<meta property="article:published_time" content="{{ $seoData['article_published'] }}">
<meta property="article:modified_time"  content="{{ $seoData['article_modified'] ?? $seoData['article_published'] }}">
<meta property="article:author"         content="{{ $seoData['article_author'] ?? $siteName }}">
<meta property="article:section"        content="{{ $seoData['article_section'] ?? 'Artikel' }}">
@endif

{{-- Hreflang alternate links (if any remains, mostly default to current url now) --}}
@if(!empty($seoData['hreflangs']))
    @foreach($seoData['hreflangs'] as $hlLocale => $hlUrl)
    <link rel="alternate" hreflang="{{ $hlLocale === 'id' ? 'id' : $hlLocale }}" href="{{ $hlUrl }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $seoData['hreflangs']['en'] ?? ($seoData['hreflangs']['id'] ?? $canonical) }}">
@endif

{{-- Geo (Local SEO & GEO) --}}
<meta name="geo.region"    content="ID-JT">
<meta name="geo.placename" content="{{ $city }}, {{ $province }}, Indonesia">
@if(!empty($settings['contact.lat']) && !empty($settings['contact.lng']))
<meta name="geo.position"  content="{{ $settings['contact.lat'] }};{{ $settings['contact.lng'] }}">
<meta name="ICBM"          content="{{ $settings['contact.lat'] }}, {{ $settings['contact.lng'] }}">
@else
<meta name="geo.position"  content="-7.2575;112.7521">
<meta name="ICBM"          content="-7.2575, 112.7521">
@endif

{{-- og:locale explicit --}}
<meta property="og:locale" content="id_ID">

{{-- JSON-LD LocalBusiness (AEO) --}}
@php
$formattedWa = $waNumber ? '+62' . ltrim(preg_replace('/[^0-9]/', '', $waNumber), '062') : '';

$lbSchema = [
    '@@context'     => 'https://schema.org',
    '@@type'        => ['LocalBusiness', 'Organization'],
    '@@id'          => $appUrl . '/#organization',
    'name'          => $siteName,
    'alternateName' => [$domain],
    'description'   => $shortDesc,
    'url'           => $appUrl,
    'image'         => $ogImage,
    'logo'          => [
        '@@type' => 'ImageObject',
        'url'    => $defaultOg,
    ],
    'priceRange'    => '$$',
    'address'       => [
        '@@type'           => 'PostalAddress',
        'streetAddress'    => $streetAddress,
        'addressLocality'  => $city,
        'addressRegion'    => $province,
        'postalCode'       => $postal,
        'addressCountry'   => 'ID',
    ],
    'hasMap'        => 'https://www.google.com/maps?q=' . urlencode($city . ', ' . $province),
    'openingHoursSpecification' => [
        [
            '@@type'     => 'OpeningHoursSpecification',
            'dayOfWeek'  => ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
            'opens'      => '08:00',
            'closes'     => '17:00',
        ],
    ],
    'contactPoint'  => [
        '@@type'             => 'ContactPoint',
        'contactType'        => 'customer service',
        'areaServed'         => ['ID'],
        'availableLanguage'  => ['Indonesian'],
    ],
];

if ($formattedWa) {
    $lbSchema['telephone'] = $formattedWa;
    $lbSchema['contactPoint']['telephone'] = $formattedWa;
}
if ($email) {
    $lbSchema['email'] = $email;
}
if (!empty($settings['contact.lat']) && !empty($settings['contact.lng'])) {
    $lbSchema['geo'] = [
        '@@type' => 'GeoCoordinates',
        'latitude' => $settings['contact.lat'],
        'longitude' => $settings['contact.lng']
    ];
}

$lbSchemaJson = str_replace('"@@', '"@', json_encode($lbSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));

// WebSite Schema with SearchAction
$websiteSchema = [
    '@@context' => 'https://schema.org',
    '@@type'    => 'WebSite',
    '@@id'      => $appUrl . '/#website',
    'url'       => $appUrl,
    'name'      => $siteName,
    'description' => $shortDesc,
    'inLanguage' => 'id-ID',
    'potentialAction' => [
        '@@type'       => 'SearchAction',
        'target'       => [
            '@@type'   => 'EntryPoint',
            'urlTemplate' => $appUrl . '/produk?q={search_term_string}',
        ],
        'query-input'  => 'required name=search_term_string',
    ],
];
$websiteSchemaJson = str_replace('"@@', '"@', json_encode($websiteSchema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
@endphp
<script type="application/ld+json">{!! $lbSchemaJson !!}</script>
<script type="application/ld+json">{!! $websiteSchemaJson !!}</script>

{{-- Additional schema (Article, FAQ, ImageObject, etc.) --}}
@if(!empty($schemaData))
<script type="application/ld+json">{!! $schemaData !!}</script>
@endif

{{-- BreadcrumbList --}}
@if(!empty($breadcrumbData))
@php
    $bcItems = [];
    foreach ($breadcrumbData as $idx => $crumb) {
        $bcItems[] = [
            '@type'    => 'ListItem',
            'position' => $idx + 1,
            'name'     => $crumb['name'] ?? '',
            'item'     => preg_replace('#^https?://[^/]+#', $appUrl, $crumb['url'] ?? ''),
        ];
    }
    $bcJson = json_encode([
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $bcItems,
    ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
@endphp
<script type="application/ld+json">{!! $bcJson !!}</script>
@endif
