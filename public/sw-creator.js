// Buyle Creator Studio Service Worker
const CACHE_NAME = 'buyle-creator-v1';
const PRECACHE_URLS = [
    '/creator/dashboard',
    '/buyle-logo.png',
    '/favicon.png'
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_URLS).catch(() => {});
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Network first, fallback to cache for navigations
self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => {
                return caches.match('/creator/dashboard');
            })
        );
    }
});

// Push Notification Event Listener (Android & iOS Web Push)
self.addEventListener('push', (event) => {
    let data = {
        title: 'Buyle.id Notification',
        body: 'Anda memiliki notifikasi baru di Creator Studio!',
        icon: '/buyle-logo.png',
        badge: '/favicon.png',
        url: '/creator/dashboard'
    };

    if (event.data) {
        try {
            data = Object.assign(data, event.data.json());
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: data.icon || '/buyle-logo.png',
        badge: data.badge || '/favicon.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/creator/dashboard'
        }
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Handle Notification Click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const targetUrl = event.notification.data?.url || '/creator/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            for (let client of windowClients) {
                if (client.url.includes('/creator') && 'focus' in client) {
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

