<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php echo '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    @foreach($urls as $url)
    <url>
        <loc>{{ $url['url'] }}</loc>
        <lastmod>{{ $url['lastmod'] }}</lastmod>
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
        @if(!empty($url['images']))
            @foreach($url['images'] as $img)
        <image:image>
            <image:loc>{{ $img['loc'] }}</image:loc>
            <image:title>{{ htmlspecialchars($img['title'] ?? '') }}</image:title>
            <image:caption>{{ htmlspecialchars($img['caption'] ?? '') }}</image:caption>
        </image:image>
            @endforeach
        @endif
    </url>
    @endforeach
</urlset>
