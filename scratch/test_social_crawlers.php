<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$url = 'https://lynk.id/mindiw/PZbVe7P';

$crawlers = [
    'facebook'    => 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
    'facebook_2'  => 'facebookexternalhit/1.1',
    'whatsapp'    => 'WhatsApp/2.23.20.0 i',
    'whatsapp_2'  => 'WhatsApp/2.21.12.21 N',
    'telegram'    => 'TelegramBot (like TelegramURLPreview/1.0)',
    'twitter'     => 'Twitterbot/1.0',
    'linkedin'    => 'LinkedInBot/1.0 (compatible; Mozilla/5.0; Jakarta/Indonesia)',
    'discord'     => 'Mozilla/5.0 (compatible; Discordbot/2.0; +https://discordapp.com)',
    'pinterest'   => 'Pinterest/0.2 (+http://www.pinterest.com/bot.html)',
    'slack'       => 'Slackbot-LinkExpanding 1.0 (+https://api.slack.com/robots)',
    'googlebot'   => 'Googlebot/2.1 (+http://www.google.com/bot.html)',
    'bingbot'     => 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
    'yandex'      => 'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)',
    'duckduckgo'  => 'DuckDuckGo-Favicons-Bot/1.0 (+http://duckduckgo.com)',
    'apple'       => 'Applebot/0.1 (+http://www.apple.com/go/applebot)',
];

foreach ($crawlers as $name => $ua) {
    echo "=== Testing $name ($ua) ===\n";
    try {
        $res = \Illuminate\Support\Facades\Http::withHeaders([
            'User-Agent'      => $ua,
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        ])->timeout(8)->withOptions(['allow_redirects' => true])->get($url);

        $status = $res->status();
        $body = $res->body();
        echo "Status: $status Length: " . strlen($body) . "\n";

        if (str_contains($body, 'Cloudflare') || str_contains($body, 'Attention Required!')) {
            echo "Cloudflare blocked\n";
        } else {
            echo "SUCCESS BINGO!\n";
            echo "Sample: " . substr(strip_tags($body), 0, 400) . "\n";
            if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $body, $m)) {
                echo "TITLE: " . $m[1] . "\n";
            }
            if (preg_match_all('/https?:\/\/cdn\.lynkid\.my\.id\/[^\s"\']+/i', $body, $m)) {
                echo "IMAGES: " . implode(', ', array_unique($m[0])) . "\n";
            }
        }
    } catch (\Throwable $e) {
        echo "ERR: " . $e->getMessage() . "\n";
    }
    echo "\n";
}
