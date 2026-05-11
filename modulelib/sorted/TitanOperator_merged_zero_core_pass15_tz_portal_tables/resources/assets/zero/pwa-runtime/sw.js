const CACHE_NAME = 'titan-runtime-v2';
const ASSETS = [
  '/pwa-runtime/offline.html',
  '/pwa-runtime/manifest.webmanifest',
  '/pwa-runtime/runtime.js',
  '/pwa-runtime/db.js',
  '/pwa-runtime/signature.js',
  '/pwa-runtime/signalQueue.js',
  '/pwa-runtime/sync.js',
  '/pwa-runtime/ui.js',
];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS)));
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))))
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') {
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then((response) => {
        const clone = response.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
        return response;
      })
      .catch(() => caches.match(event.request).then((cached) => cached || caches.match('/pwa-runtime/offline.html')))
  );
});

self.addEventListener('sync', (event) => {
  if (event.tag === 'titan-runtime-sync') {
    event.waitUntil(self.registration.showNotification('Titan Runtime', {
      body: 'Queued node signals are ready for sync.',
      icon: '/pwa-runtime/icons/icon-192.png',
    }));
  }
});
