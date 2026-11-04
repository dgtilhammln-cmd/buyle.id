// Buyle POS Dedicated Service Worker (PWABuilder & Web Push Compatible)
const CACHE_NAME = 'buyle-pos-v1.0.1';
const PRECACHE_ASSETS = [
    '/login-pos',
    '/creator/pos',
    '/buyle-logo.png',
    '/favicon.png',
    '/pos-manifest.json'
];

// Install: Cache critical POS assets
self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('POS Precaching partial error:', err);
            });
        })
    );
});

// Activate: Cleanup old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.map((key) => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch: Network first with cache fallback
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    
    // Ignore chrome-extension or external URLs
    if (!event.request.url.startsWith(self.location.origin)) return;

    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match('/creator/pos').then((res) => {
                    return res || caches.match('/login-pos');
                });
            })
        );
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((networkRes) => {
                return networkRes;
            })
            .catch(() => {
                return caches.match(event.request);
            })
    );
});

// Broadcast / Internal Push Notification Receiver
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SHOW_NOTIFICATION') {
        const payload = event.data.payload || event.data.options || {};
        const title = event.data.title || payload.title || 'Buyle POS';
        const options = {
            body: payload.body || event.data.body || 'Notifikasi transaksi baru.',
            icon: payload.icon || '/buyle-logo.png',
            badge: '/favicon.png',
            vibrate: [200, 100, 200],
            data: {
                url: payload.url || '/creator/pos'
            },
            requireInteraction: true
        };
        self.registration.showNotification(title, options);
    }
});

// Native Web Push Notification (Android / Windows / macOS / iOS Web Push)
self.addEventListener('push', (event) => {
    let payload = {
        title: 'Buyle POS - Kasir Digital',
        body: 'Ada aktivitas transaksi baru di POS Anda!',
        icon: '/buyle-logo.png',
        badge: '/favicon.png',
        url: '/creator/pos'
    };

    if (event.data) {
        try {
            payload = Object.assign(payload, event.data.json());
        } catch (e) {
            payload.body = event.data.text();
        }
    }

    const options = {
        body: payload.body,
        icon: payload.icon || '/buyle-logo.png',
        badge: payload.badge || '/favicon.png',
        vibrate: [200, 100, 200, 100, 200],
        tag: payload.tag || ('pos-notification-' + Date.now()),
        data: {
            url: payload.url || '/creator/pos'
        },
        requireInteraction: true
    };

    event.waitUntil(
        self.registration.showNotification(payload.title, options)
    );
});

// Notification Click Handler: Focus existing POS window or open new
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
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});

// Background Sync: Sync pending POS notifications when connectivity restored
self.addEventListener('sync', (event) => {
    if (event.tag === 'pos-sync' || event.tag === 'sync-pos-transactions') {
        event.waitUntil(
            fetch('/creator/pos/notifications/check', { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
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
                .catch(() => { /* silently fail when offline */ })
        );
    }
});

// Periodic Background Sync: Check POS notifications periodically
self.addEventListener('periodicsync', (event) => {
    if (event.tag === 'pos-periodic-sync') {
        event.waitUntil(
            fetch('/creator/pos/notifications/check', { credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.ok ? r.json() : null)
                .then(data => {
                    if (data && data.success && data.notifications && data.notifications.length > 0) {
                        return Promise.all(data.notifications.map(n =>
                            self.registration.showNotification(n.title || 'Buyle POS', {
                                body: n.body || 'Ada aktivitas baru.',
                                icon: '/pos-icon-192.png',
                                badge: '/pos-icon-192.png',
                                data: { url: n.url || '/creator/pos' }
                            })
                        ));
                    }
                })
                .catch(() => {})
        );
    }
});

