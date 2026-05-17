const CACHE = 'titanzero-shell-v1';
const URLS = [
  '/dashboard/user/titanzero',
  '/dashboard/user/titanzero/boss',
  '/dashboard/user/titanzero/go',
  '/dashboard/user/titanzero/dispatch',
  '/dashboard/user/titanzero/qc',
];

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(CACHE).then((cache) => cache.addAll(URLS)).catch(() => Promise.resolve()));
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(keys.filter((key) => key !== CACHE).map((key) => caches.delete(key))))
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') {
    return;
  }

  event.respondWith(
    caches.match(event.request).then((cached) => {
      if (cached) {
        return cached;
      }

      return fetch(event.request)
        .then((response) => {
          const clone = response.clone();
          caches.open(CACHE).then((cache) => cache.put(event.request, clone));
          return response;
        })
        .catch(() => caches.match('/dashboard/user/titanzero'));
    })
  );
});
