<?php
<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function getIgMeta($username) {
    try {
        $resp = Illuminate\Support\Facades\Http::timeout(6)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
            'Accept-Language' => 'en-US,en;q=0.9',
        ])->get("https://www.instagram.com/{$username}/");

        if (!$resp->successful()) return null;
        $body = $resp->body();

        $meta = [];
        if (preg_match('/property="og:description"\s+content="([^"]+)"/i', $body, $m)) {
            $desc = html_entity_decode($m[1]);
            $meta['description'] = $desc;
            if (preg_match('/([0-9,\.KkMm]+)\s+Followers,\s*([0-9,\.KkMm]+)\s+Following,\s*([0-9,\.KkMm]+)\s+Posts/i', $desc, $st)) {
                $meta['followers'] = $st[1];
                $meta['following'] = $st[2];
                $meta['posts_count'] = $st[3];
            }
        }

        if (preg_match('/property="og:title"\s+content="([^"]+)"/i', $body, $m)) {
            $meta['title'] = html_entity_decode($m[1]);
            if (preg_match('/^([^\(@]+)/', $meta['title'], $nameMatch)) {
                $meta['full_name'] = trim($nameMatch[1]);
            }
        }

        if (preg_match('/property="og:image"\s+content="([^"]+)"/i', $body, $m)) {
            $meta['avatar'] = html_entity_decode($m[1]);
        }

        return $meta;
    } catch (\Throwable $e) {
        return null;
    }
}

print_r(getIgMeta('ilhamm.ln'));
print_r(getIgMeta('instagram'));
print_r(getIgMeta('alatrumah'));

