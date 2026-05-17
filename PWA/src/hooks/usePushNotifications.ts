import { useCallback, useEffect, useState } from 'react'
import { pushApi } from '../api/push'

const VAPID_PUBLIC_KEY: string = import.meta.env.VITE_VAPID_PUBLIC_KEY ?? ''

if (!VAPID_PUBLIC_KEY) {
  console.warn('[ZeroPay] VITE_VAPID_PUBLIC_KEY is not set — push subscriptions will fail.')
}

function urlBase64ToUint8Array(base64String: string): Uint8Array {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4)
  const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
  const rawData = window.atob(base64)
  return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)))
}

export function usePushNotifications() {
  const [permission, setPermission] = useState<NotificationPermission>(Notification.permission)
  const [subscribed, setSubscribed] = useState(false)

  const subscribe = useCallback(async () => {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return
    const perm = await Notification.requestPermission()
    setPermission(perm)
    if (perm !== 'granted') return

    const registration = await navigator.serviceWorker.ready
    const sub = await registration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY).buffer as ArrayBuffer,
    })
    await pushApi.subscribe(sub.toJSON())
    setSubscribed(true)
  }, [])

  useEffect(() => {
    if (permission === 'granted') setSubscribed(true)
  }, [permission])

  return { permission, subscribed, subscribe }
}
