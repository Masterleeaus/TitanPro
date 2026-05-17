/**
 * ZeroPay Service Worker
 *
 * VitePWA strategy: 'injectManifest' (srcDir: 'src', filename: 'sw.ts')
 * Compile target: tsconfig.sw.json (lib: ES2020, types: @types/serviceworker)
 *
 * Responsibilities:
 *   - Precache app shell via injected Workbox manifest
 *   - SPA navigation fallback
 *   - Runtime caching: API (NetworkFirst), Google Fonts (CacheFirst)
 *   - Background sync for offline payment submissions (tag: 'sync-payments')
 *   - Push notification receipt and click handling
 */

import { precacheAndRoute, createHandlerBoundToURL } from 'workbox-precaching'
import { NavigationRoute, registerRoute } from 'workbox-routing'
import { NetworkFirst, CacheFirst } from 'workbox-strategies'
import { ExpirationPlugin } from 'workbox-expiration'
import { CacheableResponsePlugin } from 'workbox-cacheable-response'

declare const self: ServiceWorkerGlobalScope

// ─── Precaching ───────────────────────────────────────────────────────────────

// self.__WB_MANIFEST is injected by VitePWA at build time
const manifest = (
  self as unknown as { __WB_MANIFEST?: Array<{ url: string; revision: string | null }> }
).__WB_MANIFEST ?? []
precacheAndRoute(manifest)

// ─── Routing ─────────────────────────────────────────────────────────────────

// SPA navigation fallback – serve index.html for all non-API navigations
registerRoute(
  new NavigationRoute(createHandlerBoundToURL('/index.html'), {
    denylist: [/^\/api\//],
  }),
)

// API: NetworkFirst with 10 s timeout
registerRoute(
  ({ url }) => url.pathname.startsWith('/api/'),
  new NetworkFirst({
    cacheName: 'api-cache',
    networkTimeoutSeconds: 10,
    plugins: [new CacheableResponsePlugin({ statuses: [0, 200] })],
  }),
)

// Google Fonts: CacheFirst, 1 year max age
registerRoute(
  ({ url }) => /^fonts\.(googleapis|gstatic)\.com$/.test(url.hostname),
  new CacheFirst({
    cacheName: 'google-fonts',
    plugins: [
      new ExpirationPlugin({ maxEntries: 20, maxAgeSeconds: 60 * 60 * 24 * 365 }),
    ],
  }),
)

// ─── Background Sync ─────────────────────────────────────────────────────────

// Background Sync API types are not yet in @types/serviceworker
interface SyncEvent extends ExtendableEvent {
  readonly tag: string
  readonly lastChance: boolean
}

// Augment the event map so addEventListener('sync', …) is type-safe
interface ServiceWorkerGlobalScopeEventMap {
  sync: SyncEvent
}

const PAYMENT_SYNC_TAG = 'sync-payments'

self.addEventListener('sync', (event: SyncEvent) => {
  if (event.tag === PAYMENT_SYNC_TAG) {
    event.waitUntil(replayOfflinePayments())
  }
})

async function replayOfflinePayments(): Promise<void> {
  const db = await openPaymentQueue()
  const tx = db.transaction('pending_payments', 'readwrite')
  const store = tx.objectStore('pending_payments')
  const allRequests: OfflinePayment[] = await storeGetAll(store)

  for (const req of allRequests) {
    try {
      const headers: Record<string, string> = { 'Content-Type': 'application/json' }
      if (req.auth_token) headers['Authorization'] = `Bearer ${req.auth_token}`
      const response = await fetch(`/api/zeropay/sessions/${req.session_token}/pay`, {
        method: 'POST',
        headers,
        body: JSON.stringify(req.payload),
      })
      if (response.ok) {
        await storeDelete(store, req.id)
      }
    } catch {
      // Network still unavailable – leave in queue for next sync
    }
  }
}

// ─── Push Notifications ───────────────────────────────────────────────────────

self.addEventListener('push', (event: PushEvent) => {
  const data = event.data?.json() ?? {}
  const title: string = data.title ?? 'ZeroPay'
  const options: NotificationOptions = {
    body: data.body ?? 'You have a new notification',
    icon: '/icons/icon-192.png',
    badge: '/icons/icon-192.png',
    data: data.url ? { url: data.url } : undefined,
  }
  event.waitUntil(self.registration.showNotification(title, options))
})

self.addEventListener('notificationclick', (event: NotificationEvent) => {
  event.notification.close()
  const url: string = event.notification.data?.url ?? '/'
  event.waitUntil(
    self.clients
      .matchAll({ type: 'window', includeUncontrolled: true })
      .then((clients: readonly WindowClient[]) => {
        const existing = clients.find((c) => c.url === url)
        if (existing) return existing.focus()
        return self.clients.openWindow(url)
      }),
  )
})

// ─── IndexedDB helpers (no external libs in SW scope) ────────────────────────

interface OfflinePayment {
  id: IDBValidKey
  session_token: string
  auth_token?: string
  payload: Record<string, unknown>
  created_at: string
  retry_count: number
}

function openPaymentQueue(): Promise<IDBDatabase> {
  return new Promise((resolve, reject) => {
    // Use the same database as the main app (db/index.ts: 'zeropay-db')
    const req = indexedDB.open('zeropay-db', 1)
    req.onupgradeneeded = () => {
      if (!req.result.objectStoreNames.contains('pending_payments')) {
        req.result.createObjectStore('pending_payments', { keyPath: 'id' })
      }
    }
    req.onsuccess = () => resolve(req.result)
    req.onerror = () => reject(req.error)
  })
}

function storeGetAll(store: IDBObjectStore): Promise<OfflinePayment[]> {
  return new Promise((resolve, reject) => {
    const req = store.getAll()
    req.onsuccess = () => resolve(req.result as OfflinePayment[])
    req.onerror = () => reject(req.error)
  })
}

function storeDelete(store: IDBObjectStore, key: IDBValidKey): Promise<void> {
  return new Promise((resolve, reject) => {
    const req = store.delete(key)
    req.onsuccess = () => resolve()
    req.onerror = () => reject(req.error)
  })
}

