import { useState } from 'react'
import { useNavigate, useLocation } from 'react-router-dom'
import { sessionsApi, type QrPayload } from '../../api/sessions'
import { queuePendingPayment } from '../../db'
import { useOnlineStatus } from '../../hooks/useOnlineStatus'

// SyncManager is not yet in the standard TypeScript lib types
type SyncRegistration = ServiceWorkerRegistration & { sync: { register(tag: string): Promise<void> } }

const gateways = ['Wallet', 'BankTransfer', 'PayID']

export default function PaymentSummary() {
  const { state } = useLocation() as { state: { payload: QrPayload } }
  const [gateway, setGateway] = useState('Wallet')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const navigate = useNavigate()
  const online = useOnlineStatus()
  const payload = state?.payload

  if (!payload) {
    navigate('/pay/scan', { replace: true })
    return null
  }

  const handlePay = async () => {
    setLoading(true)
    setError('')
    try {
      if (!online) {
        await queuePendingPayment({
          id: crypto.randomUUID(),
          session_token: payload.session_token,
          auth_token: localStorage.getItem('auth_token') ?? undefined,
          payload: { gateway, ...payload } as Record<string, unknown>,
          created_at: new Date().toISOString(),
          retry_count: 0,
        })
        // Register background sync so the SW replays the payment when back online
        if ('serviceWorker' in navigator && 'SyncManager' in window) {
          try {
            const reg = await navigator.serviceWorker.ready
            await (reg as SyncRegistration).sync.register('sync-payments')
          } catch {
            // SyncManager unavailable (e.g. Firefox, some iOS) – will retry on next page load
          }
        }
        navigate('/dashboard', { replace: true, state: { offlineQueued: true } })
        return
      }
      const res = await sessionsApi.pay(payload.session_token, { gateway })
      navigate('/receive/confirm/' + res.data.transaction_id)
    } catch (err: unknown) {
      const message = err instanceof Error ? err.message : 'Payment failed'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <h2 style={{ color: '#1a1a2e' }}>Payment Summary</h2>
      <div style={{ background: '#fff', borderRadius: '16px', padding: '20px', boxShadow: '0 2px 8px rgba(0,0,0,0.08)', marginBottom: '20px' }}>
        <Row label="Merchant" value={payload.merchant_name} />
        <Row label="Amount" value={`${payload.currency} ${payload.amount != null ? payload.amount.toFixed(2) : 'Any'}`} />
        {payload.reference && <Row label="Reference" value={payload.reference} />}
        <Row label="PayID" value={payload.payid} />
      </div>

      {error && <div style={{ background: '#fee2e2', color: '#991b1b', padding: '12px', borderRadius: '8px', marginBottom: '16px', fontSize: '14px' }}>{error}</div>}

      <div style={{ marginBottom: '20px' }}>
        <p style={{ fontWeight: 600, marginBottom: '8px' }}>Select payment rail</p>
        <div style={{ display: 'flex', gap: '8px' }}>
          {gateways.map(g => (
            <button key={g} onClick={() => setGateway(g)} style={{
              flex: 1, padding: '10px', borderRadius: '8px', border: `2px solid ${gateway === g ? '#1a1a2e' : '#e0e0e0'}`,
              background: gateway === g ? '#1a1a2e' : '#fff', color: gateway === g ? '#fff' : '#1a1a2e',
              fontWeight: 600, cursor: 'pointer', fontSize: '13px',
            }}>
              {g}
            </button>
          ))}
        </div>
      </div>

      {!online && (
        <div style={{ background: '#fef3c7', color: '#92400e', padding: '10px 14px', borderRadius: '8px', marginBottom: '16px', fontSize: '14px' }}>
          ⚠️ Offline — payment will be queued and sent when back online
        </div>
      )}

      <button onClick={handlePay} disabled={loading} style={{ width: '100%', padding: '16px', background: '#e94560', color: '#fff', border: 'none', borderRadius: '12px', fontSize: '16px', fontWeight: 700, cursor: 'pointer' }}>
        {loading ? 'Processing…' : `Pay ${payload.currency} ${payload.amount != null ? payload.amount.toFixed(2) : ''} via ${gateway}`}
      </button>
    </div>
  )
}

function Row({ label, value }: { label: string; value: string | null | undefined }) {
  return (
    <div style={{ display: 'flex', justifyContent: 'space-between', padding: '8px 0', borderBottom: '1px solid #f0f0f0' }}>
      <span style={{ color: '#666', fontSize: '14px' }}>{label}</span>
      <span style={{ fontWeight: 600, fontSize: '14px' }}>{value ?? '—'}</span>
    </div>
  )
}
