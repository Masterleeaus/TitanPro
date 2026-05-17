import { useOnlineStatus } from '../hooks/useOnlineStatus'

export default function OfflineBanner() {
  const online = useOnlineStatus()
  if (online) return null
  return (
    <div style={{
      position: 'fixed', top: 0, left: 0, right: 0, zIndex: 9999,
      background: '#f59e0b', color: '#fff', textAlign: 'center',
      padding: '8px 16px', fontSize: '14px', fontWeight: 600,
    }}>
      📶 You're offline — some features are limited
    </div>
  )
}
