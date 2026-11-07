// Buyle POS Service Worker - Offline Mode Support
// Versi: 2.0.0 - Runtime cache strategy (tidak pre-cache halaman PHP)
const SW_VERSION = 'buyle-pos-v2.0.0';

// Asset statis yang aman untuk di-precache (bukan halaman PHP)
const STATIC_ASSETS = [
    '/buyle-logo.png',
    '/favicon.png',
    '/pos-manifest.json',
];

// Cache name untuk halaman POS (pisah agar mudah di-invalidate)
const PAGE_CACHE = 'buyle-pos-pages-v2';
const STATIC_CACHE = 'buyle-pos-static-v2';

// ── INSTALL: hanya cache asset statis ───────────────────────────────
self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => {
            return cache.addAll(STATIC_ASSETS).catch((err) => {
                console.warn('[SW-POS] Precache partial error:', err);
            });
        })
    );
});

// ── ACTIVATE: bersihkan cache lama ─────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    // Hapus semua cache versi lama
                    if (key !== PAGE_CACHE && key !== STATIC_CACHE) {
                        console.log('[SW-POS] Deleting old cache:', key);
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// ── FETCH: Strategy berbeda untuk halaman vs asset ──────────────────
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    if (!event.request.url.startsWith(self.location.origin)) return;

    const url = new URL(event.request.url);

    // ── 1. Navigasi ke halaman POS: Network First, cache fallback ──
    if (event.request.mode === 'navigate' && (
        url.pathname === '/creator/pos' ||
        url.pathname === '/login-pos' ||
        url.pathname.startsWith('/creator/pos/')
    )) {
        event.respondWith(
            fetch(event.request.clone())
                .then((networkResponse) => {
                    // Berhasil online: update cache dengan halaman terbaru
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(PAGE_CACHE).then((cache) => {
                            cache.put('/creator/pos', responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(() => {
                    // Offline: gunakan versi yang ter-cache
                    return caches.match('/creator/pos', { cacheName: PAGE_CACHE })
                        .then((cachedResponse) => {
                            if (cachedResponse) {
                                console.log('[SW-POS] Serving POS from cache (offline)');
                                return cachedResponse;
                            }
                            // Fallback: halaman offline sederhana
                            return new Response(buildOfflinePage(), {
                                headers: { 'Content-Type': 'text/html; charset=utf-8' }
                            });
                        });
                })
        );
        return;
    }

    // ── 2. API/AJAX requests POS: Network only, fail silently offline ──
    if (url.pathname.startsWith('/creator/pos/') && !event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return new Response(JSON.stringify({ success: false, offline: true, message: 'Sedang offline' }), {
                    headers: { 'Content-Type': 'application/json' },
                    status: 503
                });
            })
        );
        return;
    }

    // ── 3. Asset statis: Cache first, network fallback ──────────────
    if (
        url.pathname.match(/\.(png|jpg|jpeg|gif|svg|ico|css|js|woff|woff2|ttf)$/) &&
        url.hostname === self.location.hostname
    ) {
        event.respondWith(
            caches.match(event.request).then((cached) => {
                return cached || fetch(event.request).then((response) => {
                    if (response && response.status === 200) {
                        const clone = response.clone();
                        caches.open(STATIC_CACHE).then((c) => c.put(event.request, clone));
                    }
                    return response;
                }).catch(() => cached);
            })
        );
        return;
    }

    // ── 4. Semua lainnya: Network only ──────────────────────────────
    // Biarkan browser handle normally (tidak intercept)
});

// ── Halaman offline fallback sederhana ─────────────────────────────
function buildOfflinePage() {
    return `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyle POS - Mode Offline</title>
    <style>
        * { font-family: 'Segoe UI', system-ui, sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8fafc; padding: 1.5rem; }
        .card { background: #fff; border-radius: 20px; padding: 2rem; max-width: 380px; width: 100%; text-align: center; box-shadow: 0 8px 32px rgba(0,0,0,0.08); }
        .icon { font-size: 3rem; margin-bottom: 1rem; }
        h1 { font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; }
        p { font-size: 0.85rem; color: #64748b; line-height: 1.6; margin-bottom: 1.25rem; }
        .btn { display: block; width: 100%; padding: 0.75rem; border-radius: 12px; background: #0f172a; color: #fff; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; }
        .info { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 0.75rem; font-size: 0.78rem; color: #166534; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">📡</div>
        <h1>Mode Offline</h1>
        <div class="info">
            ✅ Halaman POS belum tersimpan di perangkat ini.<br>
            Buka halaman POS sekali saat online agar bisa diakses offline.
        </div>
        <p>Pastikan kamu pernah membuka <strong>/creator/pos</strong> saat ada koneksi internet agar halaman bisa disimpan untuk penggunaan offline.</p>
        <button class="btn" onclick="window.location.reload()">🔄 Coba Lagi</button>
    </div>
    <script>
        // Auto-retry saat koneksi kembali
        window.addEventListener('online', function() {
            window.location.href = '/creator/pos';
        });
    </script>
</body>
</html>`;
}

// ── Broadcast Push Notification ────────────────────────────────────
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SHOW_NOTIFICATION') {
        const payload = event.data.payload || event.data.options || {};
        const title = event.data.title || payload.title || 'Buyle POS';
        const options = {
            body: payload.body || event.data.body || 'Notifikasi transaksi baru.',
            icon: payload.icon || '/buyle-logo.png',
            badge: '/favicon.png',
            vibrate: [200, 100, 200],
            data: { url: payload.url || '/creator/pos' },
            requireInteraction: true
        };
        self.registration.showNotification(title, options);
    }

    // Force update cache POS saat dapat perintah dari halaman
    if (event.data && event.data.type === 'CACHE_POS_PAGE') {
        caches.open(PAGE_CACHE).then((cache) => {
            fetch('/creator/pos').then((response) => {
                if (response && response.status === 200) {
                    cache.put('/creator/pos', response);
                    console.log('[SW-POS] POS page cached for offline use');
                }
            }).catch(() => {});
        });
    }
});

// ── Native Web Push Notification ───────────────────────────────────
self.addEventListener('push', (event) => {
    let payload = {
        title: 'Buyle POS - Kasir Digital',
        body: 'Ada aktivitas transaksi baru di POS Anda!',
        icon: '/buyle-logo.png',
        badge: '/favicon.png',
        url: '/creator/pos'
    };

    if (event.data) {
        try { payload = Object.assign(payload, event.data.json()); }
        catch (e) { payload.body = event.data.text(); }
    }

    event.waitUntil(
        self.registration.showNotification(payload.title, {
            body: payload.body,
            icon: payload.icon || '/buyle-logo.png',
            badge: payload.badge || '/favicon.png',
            vibrate: [200, 100, 200, 100, 200],
            tag: payload.tag || ('pos-notification-' + Date.now()),
            data: { url: payload.url || '/creator/pos' },
            requireInteraction: true
        })
    );
});

// ── Notification Click ──────────────────────────────────────────────
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = event.notification.data?.url || '/creator/pos';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            for (let client of windowClients) {
                if (client.url.includes('/creator/pos') && 'focus' in client) {
                    client.navigate(targetUrl);
                    return client.focus();
                }
            }
            if (clients.openWindow) return clients.openWindow(targetUrl);
        })
    );
});

// ── Background Sync ─────────────────────────────────────────────────
self.addEventListener('sync', (event) => {
    if (event.tag === 'pos-sync' || event.tag === 'sync-pos-transactions') {
        event.waitUntil(
            fetch('/creator/pos/notifications/check', {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (data && data.success && data.notifications && data.notifications.length > 0) {
                    return Promise.all(data.notifications.map(n =>
                        self.registration.showNotification(n.title || 'Buyle POS', {
                            body: n.body || 'Ada transaksi baru.',
                            icon: '/pos-icon-192.png',
                            badge: '/pos-icon-192.png',
                            vibrate: [200, 100, 200],
                            data: { url: n.url || '/creator/pos' },
                            requireInteraction: true
                        })
                    ));
                }
            })
            .catch(() => {})
        );
    }
});
