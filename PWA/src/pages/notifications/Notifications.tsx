import { useEffect, useState } from 'react'
import { usePushNotifications } from '../../hooks/usePushNotifications'
import { notificationsApi, type Notification } from '../../api/notifications'

function notificationIcon(type: string): string {
  if (type.includes('completed')) return '✅'
  if (type.includes('pending')) return '⏳'
  if (type.includes('expiring') || type.includes('expiry')) return '⏰'
  if (type.includes('failed')) return '❌'
  return '🔔'
}

function timeAgo(dateStr: string): string {
  const diff = Math.floor((Date.now() - new Date(dateStr).getTime()) / 1000)
  if (diff < 60) return `${diff}s ago`
  if (diff < 3600) return `${Math.floor(diff / 60)}m ago`
  if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`
  return `${Math.floor(diff / 86400)}d ago`
}

export default function Notifications() {
  const { permission, subscribed, subscribe } = usePushNotifications()
  const [notifications, setNotifications] = useState<Notification[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    notificationsApi.list()
      .then(res => setNotifications(res.data.data))
      .catch(() => setNotifications([]))
      .finally(() => setLoading(false))
  }, [])

  const handleMarkRead = async (id: number) => {
    try {
      await notificationsApi.markRead(id)
      setNotifications(prev =>
        prev.map(n => n.id === id ? { ...n, read_at: new Date().toISOString() } : n),
      )
    } catch {
      // handled gracefully
    }
  }

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <h2 style={{ color: '#1a1a2e' }}>🔔 Notifications</h2>

      {permission === 'default' && (
        <div style={{ background: '#eff6ff', border: '1px solid #bfdbfe', borderRadius: '12px', padding: '16px', marginBottom: '20px' }}>
          <p style={{ margin: '0 0 12px', fontWeight: 600, color: '#1e40af' }}>Enable push notifications</p>
          <p style={{ margin: '0 0 12px', color: '#3b82f6', fontSize: '14px' }}>Get instant alerts when you receive payments.</p>
          <button onClick={() => void subscribe()} style={{ padding: '10px 20px', background: '#1e40af', color: '#fff', border: 'none', borderRadius: '8px', fontWeight: 600, cursor: 'pointer' }}>
            Enable Notifications
          </button>
        </div>
      )}

      {permission === 'denied' && (
        <div style={{ background: '#fee2e2', border: '1px solid #fca5a5', borderRadius: '12px', padding: '16px', marginBottom: '20px' }}>
          <p style={{ margin: 0, color: '#991b1b', fontSize: '14px' }}>Notifications are blocked. Enable them in browser settings.</p>
        </div>
      )}

      {subscribed && (
        <div style={{ background: '#dcfce7', border: '1px solid #86efac', borderRadius: '12px', padding: '12px 16px', marginBottom: '20px' }}>
          <p style={{ margin: 0, color: '#166534', fontSize: '14px' }}>✅ Push notifications enabled</p>
        </div>
      )}

      {loading ? (
        <p style={{ color: '#666', textAlign: 'center' }}>Loading…</p>
      ) : notifications.length === 0 ? (
        <p style={{ color: '#666', textAlign: 'center' }}>No notifications yet</p>
      ) : (
        notifications.map(n => (
          <div
            key={n.id}
            onClick={() => { if (!n.read_at) void handleMarkRead(n.id) }}
            style={{
              display: 'flex', gap: '12px', alignItems: 'flex-start',
              padding: '14px', background: n.read_at ? '#fff' : '#f0f7ff',
              borderRadius: '10px', boxShadow: '0 1px 4px rgba(0,0,0,0.06)',
              marginBottom: '8px', cursor: n.read_at ? 'default' : 'pointer',
            }}
          >
            <div style={{ fontSize: '24px', flexShrink: 0 }}>{notificationIcon(n.type)}</div>
            <div style={{ flex: 1 }}>
              <p style={{ margin: '0 0 4px', fontWeight: 600, fontSize: '14px', color: '#1a1a2e' }}>{n.title}</p>
              <p style={{ margin: '0 0 6px', color: '#666', fontSize: '13px' }}>{n.body}</p>
              <p style={{ margin: 0, color: '#999', fontSize: '12px' }}>{timeAgo(n.created_at)}</p>
            </div>
            {!n.read_at && <div style={{ width: '8px', height: '8px', borderRadius: '50%', background: '#3b82f6', flexShrink: 0, marginTop: '6px' }} />}
          </div>
        ))
      )}
    </div>
  )
}
