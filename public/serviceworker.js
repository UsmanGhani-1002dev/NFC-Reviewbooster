var staticCacheName = "reviewbooster-pwa-v" + new Date().getTime(); // Updated: 2026-04-02 14:02:00

var filesToCache = [
    '/offline',
    '/images/icons/icon-72x72.png',
    '/images/icons/icon-96x96.png',
    '/images/icons/icon-128x128.png',
    '/images/icons/icon-144x144.png',
    '/images/icons/icon-152x152.png',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-384x384.png',
    '/images/icons/icon-512x512.png',
];

// Cache on install
self.addEventListener("install", event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName)
            .then(cache => {
                return cache.addAll(filesToCache);
            })
    );
});

// Clear old caches on activate
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(cacheName => cacheName.startsWith("reviewbooster-pwa-"))
                    .filter(cacheName => cacheName !== staticCacheName)
                    .map(cacheName => caches.delete(cacheName))
            );
        })
    );
    self.clients.claim();
});

// Serve from Network first for pages, Cache first for static assets
self.addEventListener("fetch", event => {
    // Skip non-GET, cross-origin, and non-http/https requests
    if (event.request.method !== 'GET') return;
    if (!event.request.url.startsWith('http')) return;

    const url = new URL(event.request.url);
    const isPage = event.request.mode === 'navigate' || 
                   (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html'));
    const isStaticAsset = /\.(js|css|png|jpg|jpeg|gif|svg|ico|woff2?|ttf|eot)$/i.test(url.pathname);

    if (isStaticAsset) {
        // Static assets: Cache First (fast, rarely changes)
        event.respondWith(
            caches.match(event.request).then(cached => {
                return cached || fetch(event.request).then(response => {
                    if (response && response.status === 200 && response.type === 'basic') {
                        let clone = response.clone();
                        caches.open(staticCacheName).then(cache => cache.put(event.request, clone));
                    }
                    return response;
                });
            }).catch(() => caches.match('/offline'))
        );
    } else {
        // HTML pages & API calls: Network First (always fresh content)
        event.respondWith(
            fetch(event.request).then(response => {
                if (response && response.status === 200 && response.type === 'basic') {
                    let clone = response.clone();
                    caches.open(staticCacheName).then(cache => cache.put(event.request, clone));
                }
                return response;
            }).catch(() => {
                return caches.match(event.request).then(cached => {
                    return cached || caches.match('/offline');
                });
            })
        );
    }
});