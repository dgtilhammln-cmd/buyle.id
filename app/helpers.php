<?php

if (!function_exists('route_locale')) {
    /**
     * Simplified route helper — semua route kini tanpa prefix bahasa.
     * Nama route tetap sama (home, about, products, dll) tapi tidak perlu locale param.
     */
    function route_locale(string $name, mixed $params = [], ?string $locale = null): string
    {
        // Strip locale suffix jika ada (home.id → home)
        $baseName = preg_replace('/\.(id|en|ar|ko)$/', '', $name);

        if (!is_array($params)) {
            $params = [$params];
        }

        if (\Illuminate\Support\Facades\Route::has($baseName)) {
            return route($baseName, $params);
        }

        return url('/');
    }
}

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return 'id';
    }
}

if (!function_exists('switch_locale_url')) {
    // Dihapus fungsionalitasnya — tidak ada multi-bahasa
    function switch_locale_url(string $targetLocale): string
    {
        return url('/');
    }
}

if (!function_exists('locale_labels')) {
    // Dihapus — tidak ada multi-bahasa
    function locale_labels(): array
    {
        return [];
    }
}
