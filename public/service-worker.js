const CACHE_NAME = 'xpatly-v4';
const STATIC_CACHE = [
  '/',
  '/assets/css/app.css',
  '/assets/css/custom.css'
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_CACHE);
    })
  );
  self.skipWaiting();
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
  // Skip non-GET requests
  if (event.request.method !== 'GET') return;

  // Skip chrome-extension and other non-http(s) requests
  if (!event.request.url.startsWith('http')) {
    return;
  }

  // Avoid caching HTML navigations (prevents stale auth/UI state)
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => caches.match('/offline.html'))
    );
    return;
  }

  // Never cache auth-protected or session-sensitive routes
  if (event.request.url.includes('/login') ||
      event.request.url.includes('/register') ||
      event.request.url.includes('/verify-info') ||
      event.request.url.includes('/verify-email') ||
      event.request.url.includes('/forgot-password') ||
      event.request.url.includes('/reset-password') ||
      event.request.url.includes('/dashboard') ||
      event.request.url.includes('/my-listings') ||
      event.request.url.includes('/favorites') ||
      event.request.url.includes('/admin') ||
      event.request.url.includes('/messages') ||
      event.request.url.includes('/me') ||
      event.request.url.includes('/geocode.php')) {
    event.respondWith(fetch(event.request));
    return;
  }

  // Network-first for API/dynamic content
  if (event.request.url.includes('/api/') ||
      event.request.url.includes('/listings/')) {
    event.respondWith(
      fetch(event.request)
        .catch(() => caches.match(event.request))
        .catch(() => caches.match('/offline.html'))
    );
    return;
  }

  // Cache-first for static assets
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request).then((fetchResponse) => {
        // Only cache valid responses
        if (fetchResponse && fetchResponse.status === 200 && fetchResponse.type === 'basic') {
          return caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, fetchResponse.clone());
            return fetchResponse;
          });
        }
        return fetchResponse;
      }).catch(() => caches.match('/offline.html'));
    })
  );
});
