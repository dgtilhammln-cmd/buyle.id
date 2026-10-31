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
                        'label' => 'Video HD (Tanpa Watermark)',
                        'url'   => $data['hdplay'],
                        'ext'   => 'mp4',
                    ];
                } elseif (!empty($data['play'])) {
                    $downloads[] = [
                        'type'  => 'video',
                        'label' => 'Video (Tanpa Watermark)',
                        'url'   => $data['play'],
                        'ext'   => 'mp4',
                    ];
                }

                // Audio MP3 jika ada
                if (!empty($data['music'])) {
                    $downloads[] = [
                        'type'  => 'audio',
                        'label' => 'Audio Musik MP3',
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
        if ($type === 'profile' || !str_contains($url, '/p/') && !str_contains($url, '/reel/')) {
            return $this->resolveInstagramProfile($url);
        }

        // 2. Jika request adalah Post atau Reels
        return $this->resolveInstagramPostOrReel($url);
    }

    /**
     * Ambil foto profil Instagram resolusi HD.
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

        // Coba ambil profile data via Instagram Public Web Profile API
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
                                'label' => 'Download Foto Profil Resolusi Tinggi (HD)',
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

        // Fallback oEmbed info
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
                            'label' => 'Download Foto Profil HD',
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
     * Ambil video Reels atau foto postingan Instagram.
     */
    protected function resolveInstagramPostOrReel(string $url): array
    {
        // Ekstrak shortcode
        preg_match('/(p|reel|reels)\/([a-zA-Z0-9_\-]+)/', $url, $matches);
        $shortcode = $matches[2] ?? null;

        if (!$shortcode) {
            return ['success' => false, 'message' => 'Tautan postingan atau Reels Instagram tidak valid.'];
        }

        // 1. Coba via GraphQL / Public Embed API
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

        // 2. Fallback via public resolver service
        try {
            $endpoint = "https://api.vkrdown.com/api/getMedia?url=" . urlencode($url);
            $res = Http::timeout(10)->get($endpoint);

            if ($res->successful() && !empty($res->json('data.downloadUrl'))) {
                $data = $res->json('data');
                $videoUrl = $data['downloadUrl'];
                $cover = $data['thumbnail'] ?? null;

                return [
                    'success'   => true,
                    'platform'  => 'instagram',
                    'type'      => 'reels',
                    'title'     => $data['title'] ?? 'Instagram Reels',
                    'preview'   => $cover,
                    'author'    => [
                        'name'     => 'Kreator Instagram',
                        'username' => '@instagram',
                        'avatar'   => null,
                    ],
                    'is_video'  => true,
                    'video_url' => $videoUrl,
                    'downloads' => [
                        [
                            'type'  => 'video',
                            'label' => 'Download Video Reels HD (MP4)',
                            'url'   => $videoUrl,
                            'ext'   => 'mp4',
                        ]
                    ],
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Instagram VKR API gagal: ' . $e->getMessage());
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

