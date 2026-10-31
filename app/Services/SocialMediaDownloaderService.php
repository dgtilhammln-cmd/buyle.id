<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialMediaDownloaderService
{
    /**
     * Deteksi platform dan jenis konten dari link URL yang dimasukkan.
     */
    public function detectPlatform(string $url): array
    {
        $url = trim($url);

        // TikTok pattern
        if (preg_match('/(tiktok\.com|douyin\.com)/i', $url)) {
            return [
                'platform' => 'tiktok',
                'type'     => 'video',
                'label'    => 'TikTok',
            ];
        }

        // Instagram pattern
        if (preg_match('/(instagram\.com|instagr\.am)/i', $url)) {
            if (preg_match('/\/reels?\/([a-zA-Z0-9_\-]+)/i', $url)) {
                return [
                    'platform' => 'instagram',
                    'type'     => 'reels',
                    'label'    => 'Instagram Reels',
                ];
            }
            if (preg_match('/\/p\/([a-zA-Z0-9_\-]+)/i', $url)) {
                return [
                    'platform' => 'instagram',
                    'type'     => 'post',
                    'label'    => 'Instagram Post',
                ];
            }
            if (preg_match('/\/stories\/([a-zA-Z0-9_\.\-]+)/i', $url)) {
                return [
                    'platform' => 'instagram',
                    'type'     => 'story',
                    'label'    => 'Instagram Story',
                ];
            }
            // Profil URL / DP
            if (preg_match('/instagram\.com\/([a-zA-Z0-9_\.\-]+)\/?$/i', $url, $m)) {
                if (!in_array(strtolower($m[1]), ['p', 'reel', 'reels', 'stories', 'explore', 'direct'])) {
                    return [
                        'platform' => 'instagram',
                        'type'     => 'profile',
                        'label'    => 'Instagram Foto Profil',
                    ];
                }
            }
            return [
                'platform' => 'instagram',
                'type'     => 'general',
                'label'    => 'Instagram',
            ];
        }

        // Jika user cuma input username Instagram (misal @username atau username)
        if (preg_match('/^@?([a-zA-Z0-9_\.]{3,30})$/', $url, $m)) {
            return [
                'platform' => 'instagram',
                'type'     => 'profile',
                'label'    => 'Instagram Foto Profil',
            ];
        }

        return [
            'platform' => 'unknown',
            'type'     => 'unknown',
            'label'    => 'Auto Detect',
        ];
    }

    /**
     * Resolusi media dari link URL TikTok atau Instagram.
     */
    public function resolve(string $url): array
    {
        $detected = $this->detectPlatform($url);

        if ($detected['platform'] === 'tiktok') {
            return $this->resolveTikTok($url);
        }

        if ($detected['platform'] === 'instagram') {
            return $this->resolveInstagram($url, $detected['type']);
        }

        return [
            'success' => false,
            'message' => 'Link tidak dikenali. Mohon masukkan link publik dari Instagram atau TikTok yang valid.',
        ];
    }

    /**
     * Ambil data video TikTok tanpa watermark.
     */
    protected function resolveTikTok(string $url): array
    {
        // 1. Coba via TikWM API (sangat cepat & tanpa watermark)
        try {
            $response = Http::timeout(10)->asForm()->post('https://www.tikwm.com/api/', [
                'url' => $url,
                'count' => 12,
                'cursor' => 0,
                'web' => 1,
                'hd' => 1,
            ]);

            if ($response->successful() && $response->json('code') === 0) {
                $data = $response->json('data');

                $author = [
                    'name'     => $data['author']['nickname'] ?? 'Kreator TikTok',
                    'username' => '@' . ($data['author']['unique_id'] ?? 'user'),
                    'avatar'   => $data['author']['avatar'] ?? null,
                ];

                $downloads = [];

                // Video tanpa watermark HD
                if (!empty($data['hdplay'])) {
                    $downloads[] = [
                        'type'  => 'video',
                        'label' => 'Download Video HD (Tanpa Watermark)',
                        'url'   => $data['hdplay'],
                        'ext'   => 'mp4',
                    ];
                } elseif (!empty($data['play'])) {
                    $downloads[] = [
                        'type'  => 'video',
                        'label' => 'Download Video (Tanpa Watermark)',
                        'url'   => $data['play'],
                        'ext'   => 'mp4',
                    ];
                }

                // Cover / Thumbnail Video HD
                if (!empty($data['cover'])) {
                    $downloads[] = [
                        'type'  => 'image',
                        'label' => 'Download Cover / Thumbnail HD',
                        'url'   => $data['cover'],
                        'ext'   => 'jpg',
                    ];
                }

                // Audio MP3 jika ada
                if (!empty($data['music'])) {
                    $downloads[] = [
                        'type'  => 'audio',
                        'label' => 'Download Audio Musik MP3',
                        'url'   => $data['music'],
                        'ext'   => 'mp3',
                    ];
                }

                // Jika berupa slide foto / images
                $images = [];
                if (!empty($data['images']) && is_array($data['images'])) {
                    foreach ($data['images'] as $idx => $imgUrl) {
                        $images[] = $imgUrl;
                        $downloads[] = [
                            'type'  => 'image',
                            'label' => 'Foto Slide ' . ($idx + 1),
                            'url'   => $imgUrl,
                            'ext'   => 'jpg',
                        ];
                    }
                }

                return [
                    'success'     => true,
                    'platform'    => 'tiktok',
                    'title'       => $data['title'] ?? 'Video TikTok',
                    'preview'     => !empty($images) ? $images[0] : ($data['cover'] ?? null),
                    'author'      => $author,
                    'is_video'    => empty($images),
                    'video_url'   => $data['play'] ?? ($data['hdplay'] ?? null),
                    'downloads'   => $downloads,
                    'stats'       => [
                        'likes'    => $data['digg_count'] ?? 0,
                        'comments' => $data['comment_count'] ?? 0,
                        'shares'   => $data['share_count'] ?? 0,
                    ],
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('TikWM API gagal: ' . $e->getMessage());
        }

        // 2. Fallback via TiklyDown API
        try {
            $cleanUrl = urlencode($url);
            $response = Http::timeout(10)->get("https://api.tiklydown.eu.org/api/download?url={$cleanUrl}");

            if ($response->successful() && !empty($response->json('video'))) {
                $res = $response->json();
                $downloads = [];

                if (!empty($res['video']['noWatermark'])) {
                    $downloads[] = [
                        'type'  => 'video',
                        'label' => 'Video HD (Tanpa Watermark)',
                        'url'   => $res['video']['noWatermark'],
                        'ext'   => 'mp4',
                    ];
                }

                if (!empty($res['music']['play_url'])) {
                    $downloads[] = [
                        'type'  => 'audio',
                        'label' => 'Audio Musik MP3',
                        'url'   => $res['music']['play_url'],
                        'ext'   => 'mp3',
                    ];
                }

                return [
                    'success'   => true,
                    'platform'  => 'tiktok',
                    'title'     => $res['title'] ?? 'Video TikTok',
                    'preview'   => $res['video']['cover'] ?? null,
                    'author'    => [
                        'name'     => $res['author']['name'] ?? 'Kreator TikTok',
                        'username' => '@' . ($res['author']['unique_id'] ?? 'user'),
                        'avatar'   => $res['author']['avatar'] ?? null,
                    ],
                    'is_video'  => true,
                    'video_url' => $res['video']['noWatermark'] ?? null,
                    'downloads' => $downloads,
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('TiklyDown API gagal: ' . $e->getMessage());
        }

        return [
            'success' => false,
            'message' => 'Gagal mengambil video TikTok. Pastikan video bersifat publik dan tautan dapat diakses.',
        ];
    }

    /**
     * Ambil data Instagram (Reels, Post, atau Foto Profil DP).
     */
    protected function resolveInstagram(string $url, string $type): array
    {
        // 1. Jika request adalah Foto Profil / DP
        if ($type === 'profile' || (!str_contains($url, '/p/') && !str_contains($url, '/reel/') && !str_contains($url, '/reels/'))) {
            return $this->resolveInstagramProfile($url);
        }

        // 2. Jika request adalah Post atau Reels
        return $this->resolveInstagramPostOrReel($url);
    }

    /**
     * Ambil foto profil Instagram resolusi HD dan seluruh feed postingan.
     */
    protected function resolveInstagramProfile(string $input): array
    {
        // Ekstrak username dari URL atau input teks
        $username = trim($input);
        if (preg_match('/instagram\.com\/([a-zA-Z0-9_\.\-]+)/i', $username, $m)) {
            $username = $m[1];
        }
        $username = ltrim($username, '@');
        $username = explode('?', $username)[0];
        $username = trim($username, '/');

        if (empty($username)) {
            return ['success' => false, 'message' => 'Username Instagram tidak valid.'];
        }

        // 1. Coba via SaveInsta Profile Scraper (mendapatkan Foto Profil HD + Seluruh Postingan Feed)
        $profileRes = $this->resolveInstagramProfileViaSaveInsta($username);
        if (!empty($profileRes['success'])) {
            return $profileRes;
        }

        // 2. Fallback via Instagram Public Web Profile API
        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'X-IG-App-ID' => '936619743392459',
                ])
                ->get("https://i.instagram.com/api/v1/users/web_profile_info/?username={$username}");

            if ($response->successful() && !empty($response->json('data.user'))) {
                $user = $response->json('data.user');
                $hdPic = $user['hd_profile_pic_url_info']['url'] ?? ($user['profile_pic_url_hd'] ?? ($user['profile_pic_url'] ?? null));

                if ($hdPic) {
                    return [
                        'success'   => true,
                        'platform'  => 'instagram',
                        'type'      => 'profile',
                        'title'     => 'Foto Profil HD: ' . ($user['full_name'] ?: $username),
                        'preview'   => $hdPic,
                        'author'    => [
                            'name'     => $user['full_name'] ?: $username,
                            'username' => '@' . $username,
                            'avatar'   => $hdPic,
                        ],
                        'is_video'  => false,
                        'downloads' => [
                            [
                                'type'  => 'image',
                                'label' => 'Download Foto Profil HD (DP)',
                                'url'   => $hdPic,
                                'ext'   => 'jpg',
                            ]
                        ],
                        'stats' => [
                            'followers' => $user['edge_followed_by']['count'] ?? 0,
                            'posts'     => $user['edge_owner_to_timeline_media']['count'] ?? 0,
                        ],
                    ];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Instagram DP fetch gagal: ' . $e->getMessage());
        }

        // 3. Fallback via oEmbed info
        try {
            $oembed = Http::timeout(8)->get("https://api.instagram.com/oembed/?url=https://www.instagram.com/{$username}/");
            if ($oembed->successful() && !empty($oembed->json('thumbnail_url'))) {
                $picUrl = $oembed->json('thumbnail_url');
                return [
                    'success'   => true,
                    'platform'  => 'instagram',
                    'type'      => 'profile',
                    'title'     => 'Foto Profil Instagram: @' . $username,
                    'preview'   => $picUrl,
                    'author'    => [
                        'name'     => $oembed->json('author_name') ?? $username,
                        'username' => '@' . $username,
                        'avatar'   => $picUrl,
                    ],
                    'is_video'  => false,
                    'downloads' => [
                        [
                            'type'  => 'image',
                            'label' => 'Download Foto Profil HD (DP)',
                            'url'   => $picUrl,
                            'ext'   => 'jpg',
                        ]
                    ],
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Instagram oEmbed fallback gagal: ' . $e->getMessage());
        }

        return [
            'success' => false,
            'message' => 'Gagal mengambil foto profil Instagram. Pastikan akun bersifat publik dan username tertulis dengan benar.',
        ];
    }

    /**
     * Ambil foto profil HD dan seluruh postingan timeline Instagram via SaveInsta Engine.
     */
    protected function resolveInstagramProfileViaSaveInsta(string $username): array
    {
        $cleanUrl = "https://www.instagram.com/{$username}/";

        try {
            // 1. Dapatkan token verifikasi
            $resVerify = Http::timeout(10)->asForm()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Origin'     => 'https://saveinsta.to',
                'Referer'    => 'https://saveinsta.to/',
            ])->post('https://saveinsta.to/api/userverify', [
                'url' => $cleanUrl,
            ]);

            $token = $resVerify->json('token');
            if (empty($token)) {
                return ['success' => false, 'message' => 'Gagal memverifikasi profil Instagram.'];
            }

            // 2. Request ajaxSearch dengan tipe profile
            $resSearch = Http::timeout(20)->asForm()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Origin'     => 'https://saveinsta.to',
                'Referer'    => 'https://saveinsta.to/',
            ])->post('https://saveinsta.to/api/ajaxSearch', [
                'q'       => $cleanUrl,
                't'       => 'profile',
                'lang'    => 'en',
                'cftoken' => $token,
            ]);

            $html = $resSearch->json('data');
            if (empty($html)) {
                return ['success' => false, 'message' => 'Profil Instagram tidak ditemukan atau akun bersifat privat.'];
            }

            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            $xpath = new \DOMXPath($dom);

            $items = $xpath->query('//div[contains(@class, "download-items")]');
            if ($items->length === 0) {
                return ['success' => false, 'message' => 'Tidak ada postingan publik ditemukan pada akun ini.'];
            }

            $avatarUrl = null;
            $feedPosts = [];
            $seenUrls = [];

            foreach ($items as $idx => $it) {
                $img = $xpath->query('.//img', $it)->item(0);
                $thumbSrc = $img ? $img->getAttribute('src') : null;

                $links = $xpath->query('.//a', $it);
                $btnList = [];
                $videoDlUrl = null;
                $photoDlUrl = null;

                foreach ($links as $l) {
                    $href = $l->getAttribute('href');
                    $text = trim($l->textContent);
                    if ($href && $href !== '#' && !str_starts_with($href, 'javascript:')) {
                        $btnList[] = ['text' => $text, 'url' => $href];
                        if (stripos($text, 'video') !== false || stripos($href, '.mp4') !== false) {
                            $videoDlUrl = $href;
                        } elseif (stripos($text, 'image') !== false || stripos($text, 'photo') !== false || stripos($text, 'thumbnail') !== false) {
                            $photoDlUrl = $href;
                        }
                    }
                }

                // Cek apakah item adalah Foto Profil (Avatar)
                $isAvatar = false;
                foreach ($btnList as $b) {
                    if (stripos($b['text'], 'avatar') !== false) {
                        $isAvatar = true;
                        if (!$avatarUrl) {
                            $avatarUrl = $b['url'];
                        }
                    }
                }

                if ($isAvatar) {
                    continue;
                }

                $primaryDlUrl = $videoDlUrl ?: ($photoDlUrl ?: ($btnList[0]['url'] ?? $thumbSrc));
                if (!$primaryDlUrl || in_array($primaryDlUrl, $seenUrls)) {
                    continue;
                }
                $seenUrls[] = $primaryDlUrl;

                $isVideo = !empty($videoDlUrl)
                    || (stripos($primaryDlUrl, '.mp4') !== false)
                    || ($xpath->query('.//i[contains(@class, "icon-dlvideo")]', $it)->length > 0);

                $feedPosts[] = [
                    'id'           => $idx,
                    'thumb'        => $thumbSrc,
                    'is_video'     => $isVideo,
                    'download_url' => $primaryDlUrl,
                    'video_url'    => $videoDlUrl,
                    'image_url'    => $photoDlUrl ?: $thumbSrc,
                    'label'        => $isVideo ? 'Download Video HD' : 'Download Foto HD',
                    'ext'          => $isVideo ? 'mp4' : 'jpg',
                ];
            }

            if (!$avatarUrl && !empty($feedPosts[0]['thumb'])) {
                $avatarUrl = $feedPosts[0]['thumb'];
            }

            return [
                'success'         => true,
                'platform'        => 'instagram',
                'type'            => 'profile',
                'title'           => 'Profil Instagram: @' . $username,
                'preview'         => $avatarUrl,
                'author'          => [
                    'name'     => $username,
                    'username' => '@' . $username,
                    'avatar'   => $avatarUrl,
                ],
                'is_video'        => false,
                'downloads'       => $avatarUrl ? [
                    [
                        'type'  => 'image',
                        'label' => 'Download Foto Profil HD (DP)',
                        'url'   => $avatarUrl,
                        'ext'   => 'jpg',
                    ]
                ] : [],
                'is_profile_feed' => true,
                'posts'           => $feedPosts,
                'total_posts'     => count($feedPosts),
            ];
        } catch (\Throwable $e) {
            Log::warning('Instagram Profile via SaveInsta Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal mengambil postingan profil Instagram.'];
        }
    }

    /**
     * Ambil video Reels atau foto postingan Instagram.
     */
    protected function resolveInstagramPostOrReel(string $url): array
    {
        // 1. Prioritas Utama: Resolver SaveInsta (sangat handal untuk Post, Carousel, dan Reels)
        $saveInstaRes = $this->resolveInstagramViaSaveInsta($url);
        if (!empty($saveInstaRes['success'])) {
            return $saveInstaRes;
        }

        // Ekstrak shortcode
        preg_match('/(p|reel|reels)\/([a-zA-Z0-9_\-]+)/', $url, $matches);
        $shortcode = $matches[2] ?? null;

        if (!$shortcode) {
            return ['success' => false, 'message' => 'Tautan postingan atau Reels Instagram tidak valid.'];
        }

        // 2. Coba via Direct Instagram Embed / API
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                    'X-IG-App-ID' => '936619743392459',
                ])
                ->get("https://www.instagram.com/p/{$shortcode}/?__a=1&__d=dis");

            if ($response->successful() && !empty($response->json('graphql.shortcode_media'))) {
                $media = $response->json('graphql.shortcode_media');
                return $this->formatInstagramMedia($media);
            }

            if ($response->successful() && !empty($response->json('items.0'))) {
                $item = $response->json('items.0');
                return $this->formatInstagramItem($item);
            }
        } catch (\Throwable $e) {
            Log::warning('Instagram Direct API gagal: ' . $e->getMessage());
        }

        // 3. Fallback via oEmbed (minimum thumbnail & author info)
        try {
            $oembed = Http::timeout(8)->get("https://api.instagram.com/oembed/?url=" . urlencode($url));
            if ($oembed->successful() && !empty($oembed->json('thumbnail_url'))) {
                $thumb = $oembed->json('thumbnail_url');
                return [
                    'success'   => true,
                    'platform'  => 'instagram',
                    'type'      => 'post',
                    'title'     => $oembed->json('title') ?? 'Postingan Instagram',
                    'preview'   => $thumb,
                    'author'    => [
                        'name'     => $oembed->json('author_name') ?? 'Instagram Creator',
                        'username' => '@' . ($oembed->json('author_name') ?? 'user'),
                        'avatar'   => null,
                    ],
                    'is_video'  => false,
                    'downloads' => [
                        [
                            'type'  => 'image',
                            'label' => 'Download Gambar Sampul HD',
                            'url'   => $thumb,
                            'ext'   => 'jpg',
                        ]
                    ],
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Instagram oEmbed Post fallback gagal: ' . $e->getMessage());
        }

        return [
            'success' => false,
            'message' => 'Gagal memproses media Instagram. Pastikan akun dan postingan bersifat publik.',
        ];
    }

    /**
     * Resolusi Instagram via SaveInsta Engine (mendukung Foto, Video Reels, dan Multi-Slide Carousel).
     */
    protected function resolveInstagramViaSaveInsta(string $url): array
    {
        // Bersihkan query string pelacak dari URL
        $cleanUrl = preg_replace('/\?.*/', '', trim($url));
        if (!str_ends_with($cleanUrl, '/')) {
            $cleanUrl .= '/';
        }

        try {
            // 1. Ambil token verifikasi dari SaveInsta
            $resVerify = Http::timeout(10)->asForm()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Origin'     => 'https://saveinsta.to',
                'Referer'    => 'https://saveinsta.to/',
            ])->post('https://saveinsta.to/api/userverify', [
                'url' => $cleanUrl,
            ]);

            $token = $resVerify->json('token');
            if (empty($token)) {
                return ['success' => false, 'message' => 'Gagal mendapatkan token verifikasi Instagram.'];
            }

            // 2. Ambil token dinamis k_exp & k_token dari halaman SaveInsta
            $k_exp = '';
            $k_token = '';
            try {
                $basePage = Http::timeout(6)->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])->get('https://saveinsta.to/en');
                if (preg_match('/k_exp\s*=\s*[\"\\\']([^\"\\\']+)[\"\\\']/', $basePage->body(), $mExp)) {
                    $k_exp = $mExp[1];
                }
                if (preg_match('/k_token\s*=\s*[\"\\\']([^\"\\\']+)[\"\\\']/', $basePage->body(), $mTok)) {
                    $k_token = $mTok[1];
                }
            } catch (\Throwable $e) {
                // Abaikan jika gagal ambil token base
            }

            // 3. Request ajaxSearch ke SaveInsta
            $resSearch = Http::timeout(14)->asForm()->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Origin'     => 'https://saveinsta.to',
                'Referer'    => 'https://saveinsta.to/',
            ])->post('https://saveinsta.to/api/ajaxSearch', [
                'q'       => $cleanUrl,
                't'       => 'media',
                'lang'    => 'en',
                'cftoken' => $token,
                'k_exp'   => $k_exp,
                'k_token' => $k_token,
            ]);

            $html = $resSearch->json('data');
            if (empty($html)) {
                $msg = $resSearch->json('mess') ?? '';
                if (stripos($msg, 'private') !== false) {
                    return ['success' => false, 'message' => 'Postingan ini bersifat privat atau telah dihapus oleh pemiliknya.'];
                }
                return ['success' => false, 'message' => 'Tidak dapat menemukan media pada link ini.'];
            }

            // 4. Parse DOM hasil pencarian
            $dom = new \DOMDocument();
            @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
            $xpath = new \DOMXPath($dom);

            $items = $xpath->query('//div[contains(@class, "download-items")]');
            if ($items->length === 0) {
                return ['success' => false, 'message' => 'Tidak ada media ditemukan dalam postingan ini.'];
            }

            $downloads = [];
            $mainPreview = null;
            $hasVideo = false;
            $firstVideoUrl = null;

            foreach ($items as $idx => $item) {
                $img = $xpath->query('.//img', $item)->item(0);
                $thumbSrc = $img ? $img->getAttribute('src') : null;
                if (!$mainPreview && $thumbSrc) {
                    $mainPreview = $thumbSrc;
                }

                // Cari tombol download
                $linkNodes = $xpath->query('.//a[contains(@class, "abutton") or contains(@class, "download-btn")]', $item);
                $itemUrl = null;
                $itemLabel = null;

                foreach ($linkNodes as $ln) {
                    $href = $ln->getAttribute('href');
                    $text = trim($ln->textContent);
                    if ($href && $href !== '#' && !str_starts_with($href, 'javascript:')) {
                        $itemUrl = $href;
                        $itemLabel = $text;
                        break;
                    }
                }

                // Jika tidak ada tag <a> langsung (carousel preview slide), ekstrak dari token di thumbnail
                if (!$itemUrl && $thumbSrc && preg_match('/token=([^&]+)/', $thumbSrc, $m)) {
                    $parts = explode('.', $m[1]);
                    if (isset($parts[1])) {
                        $payload = json_decode(base64_decode(str_pad(strtr($parts[1], '-_', '+/'), strlen($parts[1]) % 4, '=', STR_PAD_RIGHT)), true);
                        if (!empty($payload['url'])) {
                            $itemUrl = $payload['url'];
                        }
                    }
                }

                if (!$itemUrl) {
                    continue;
                }

                // Tentukan apakah item adalah video
                $isItemVideo = (stripos($itemLabel ?? '', 'video') !== false)
                    || (stripos($itemUrl, '.mp4') !== false)
                    || ($xpath->query('.//i[contains(@class, "icon-dlvideo")]', $item)->length > 0);

                $itemType = $isItemVideo ? 'video' : 'image';
                $itemExt  = $isItemVideo ? 'mp4' : 'jpg';

                if ($isItemVideo) {
                    $hasVideo = true;
                    if (!$firstVideoUrl) {
                        $firstVideoUrl = $itemUrl;
                    }
                }

                // Label tombol
                $label = $isItemVideo ? 'Download Video HD' : 'Download Foto HD';
                if ($items->length > 1) {
                    $label .= ' (Slide ' . ($idx + 1) . ')';
                }

                $downloads[] = [
                    'type'  => $itemType,
                    'label' => $label,
                    'url'   => $itemUrl,
                    'ext'   => $itemExt,
                ];
            }

            // Ekstrak username kreator dari URL jika ada (contoh instagram.com/username/reel/..)
            $authorData = null;
            if (preg_match('/instagram\.com\/([a-zA-Z0-9_\.\-]+)\/(?:reel|reels|p)\//i', $cleanUrl, $uMatch)) {
                if (!in_array(strtolower($uMatch[1]), ['reel', 'reels', 'p', 'stories', 'explore'])) {
                    $authorData = [
                        'name'     => $uMatch[1],
                        'username' => '@' . $uMatch[1],
                        'avatar'   => null,
                    ];
                }
            }

            // Jika konten adalah Video atau Reels
            $isReelUrl = str_contains($cleanUrl, '/reel/') || str_contains($cleanUrl, '/reels/');
            if ($hasVideo || $isReelUrl) {
                $targetVideoUrl = $firstVideoUrl;
                if (!$targetVideoUrl && !empty($downloads[0]['url'])) {
                    $targetVideoUrl = $downloads[0]['url'];
                }

                $finalDownloads = [
                    [
                        'type'  => 'video',
                        'label' => 'Download Video HD (MP4)',
                        'url'   => $targetVideoUrl,
                        'ext'   => 'mp4',
                    ]
                ];

                if (!empty($mainPreview)) {
                    $finalDownloads[] = [
                        'type'  => 'image',
                        'label' => 'Download Cover / Thumbnail HD (JPG)',
                        'url'   => $mainPreview,
                        'ext'   => 'jpg',
                    ];
                }

                return [
                    'success'   => true,
                    'platform'  => 'instagram',
                    'type'      => 'reels',
                    'title'     => 'Instagram Reels HD',
                    'preview'   => $mainPreview,
                    'author'    => $authorData,
                    'is_video'  => true,
                    'video_url' => $targetVideoUrl,
                    'downloads' => $finalDownloads,
                ];
            }

            // Jika konten adalah Foto Carousel atau Foto Tunggal
            $uniqueImages = [];
            $seenUrls = [];
            foreach ($downloads as $d) {
                if (!in_array($d['url'], $seenUrls)) {
                    $seenUrls[] = $d['url'];
                    $uniqueImages[] = $d;
                }
            }

            if (empty($uniqueImages)) {
                return ['success' => false, 'message' => 'Gagal mengekstrak tautan unduhan dari media ini.'];
            }

            if (count($uniqueImages) > 1) {
                foreach ($uniqueImages as $i => &$ui) {
                    $ui['label'] = 'Download Foto HD (Slide ' . ($i + 1) . ')';
                }
                $finalDownloads = $uniqueImages;
                $platformType = 'carousel';
                $title = 'Instagram Carousel (' . count($finalDownloads) . ' Foto)';
            } else {
                $finalDownloads = [
                    [
                        'type'  => 'image',
                        'label' => 'Download Foto HD (JPG)',
                        'url'   => $uniqueImages[0]['url'],
                        'ext'   => 'jpg',
                    ]
                ];
                if (!empty($mainPreview) && $mainPreview !== $uniqueImages[0]['url']) {
                    $finalDownloads[] = [
                        'type'  => 'image',
                        'label' => 'Download Cover / Thumbnail HD (JPG)',
                        'url'   => $mainPreview,
                        'ext'   => 'jpg',
                    ];
                }
                $platformType = 'post';
                $title = 'Instagram Post HD';
            }

            return [
                'success'   => true,
                'platform'  => 'instagram',
                'type'      => $platformType,
                'title'     => $title,
                'preview'   => $mainPreview ?: ($finalDownloads[0]['url'] ?? null),
                'author'    => $authorData,
                'is_video'  => false,
                'video_url' => null,
                'downloads' => $finalDownloads,
            ];
        } catch (\Throwable $e) {
            Log::warning('SaveInsta Resolver Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kendala saat menghubungkan ke server unduhan Instagram.'];
        }
    }

    /**
     * Format data GraphQL shortcode media.
     */
    protected function formatInstagramMedia(array $media): array
    {
        $isVideo = $media['is_video'] ?? false;
        $caption = $media['edge_media_to_caption']['edges'][0]['node']['text'] ?? 'Postingan Instagram';
        $author  = [
            'name'     => $media['owner']['full_name'] ?? ($media['owner']['username'] ?? 'Instagram User'),
            'username' => '@' . ($media['owner']['username'] ?? 'user'),
            'avatar'   => $media['owner']['profile_pic_url'] ?? null,
        ];

        $downloads = [];

        if ($isVideo && !empty($media['video_url'])) {
            $downloads[] = [
                'type'  => 'video',
                'label' => 'Download Video HD (MP4)',
                'url'   => $media['video_url'],
                'ext'   => 'mp4',
            ];
        }

        // Cek jika Carousel (banyak slide)
        if (!empty($media['edge_sidecar_to_children']['edges'])) {
            foreach ($media['edge_sidecar_to_children']['edges'] as $idx => $edge) {
                $child = $edge['node'];
                $cIsVideo = $child['is_video'] ?? false;
                $url = $cIsVideo ? ($child['video_url'] ?? null) : ($child['display_url'] ?? null);

                if ($url) {
                    $downloads[] = [
                        'type'  => $cIsVideo ? 'video' : 'image',
                        'label' => ($cIsVideo ? 'Video Slide ' : 'Foto Slide ') . ($idx + 1),
                        'url'   => $url,
                        'ext'   => $cIsVideo ? 'mp4' : 'jpg',
                    ];
                }
            }
        } elseif (!$isVideo && !empty($media['display_url'])) {
            $downloads[] = [
                'type'  => 'image',
                'label' => 'Download Foto Kualitas Tinggi (JPG)',
                'url'   => $media['display_url'],
                'ext'   => 'jpg',
            ];
        }

        return [
            'success'   => true,
            'platform'  => 'instagram',
            'type'      => $isVideo ? 'reels' : 'post',
            'title'     => mb_strimwidth($caption, 0, 90, '...'),
            'preview'   => $media['display_url'] ?? null,
            'author'    => $author,
            'is_video'  => $isVideo,
            'video_url' => $media['video_url'] ?? null,
            'downloads' => $downloads,
        ];
    }

    /**
     * Format data items.0 Instagram API.
     */
    protected function formatInstagramItem(array $item): array
    {
        $videoVersions = $item['video_versions'] ?? [];
        $isVideo = !empty($videoVersions);
        $caption = $item['caption']['text'] ?? 'Postingan Instagram';

        $author = [
            'name'     => $item['user']['full_name'] ?? ($item['user']['username'] ?? 'User'),
            'username' => '@' . ($item['user']['username'] ?? 'user'),
            'avatar'   => $item['user']['profile_pic_url'] ?? null,
        ];

        $downloads = [];

        if ($isVideo && !empty($videoVersions[0]['url'])) {
            $downloads[] = [
                'type'  => 'video',
                'label' => 'Download Video HD (MP4)',
                'url'   => $videoVersions[0]['url'],
                'ext'   => 'mp4',
            ];
        }

        if (!empty($item['image_versions2']['candidates'][0]['url'])) {
            $preview = $item['image_versions2']['candidates'][0]['url'];
            if (!$isVideo) {
                $downloads[] = [
                    'type'  => 'image',
                    'label' => 'Download Foto Resolusi Tinggi (JPG)',
                    'url'   => $preview,
                    'ext'   => 'jpg',
                ];
            }
        } else {
            $preview = null;
        }

        return [
            'success'   => true,
            'platform'  => 'instagram',
            'type'      => $isVideo ? 'reels' : 'post',
            'title'     => mb_strimwidth($caption, 0, 90, '...'),
            'preview'   => $preview,
            'author'    => $author,
            'is_video'  => $isVideo,
            'video_url' => $isVideo ? ($videoVersions[0]['url'] ?? null) : null,
            'downloads' => $downloads,
        ];
    }
}

