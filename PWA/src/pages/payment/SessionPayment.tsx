import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { sessionsApi, type Session } from '../../api/sessions'

export default function SessionPayment() {
  const { token } = useParams<{ token: string }>()
  const [session, setSession] = useState<Session | null>(null)
  const [error, setError] = useState('')
  const navigate = useNavigate()

  useEffect(() => {
    if (!token) return
    sessionsApi.get(token)
      .then(r => setSession(r.data))
      .catch(() => setError('Session not found or has expired.'))
  }, [token])

  if (error) {
    return (
      <div style={{ padding: '32px', textAlign: 'center' }}>
        <div style={{ fontSize: '48px', marginBottom: '16px' }}>❌</div>
        <h2 style={{ color: '#1a1a2e' }}>{error}</h2>
      </div>
    )
  }

  if (!session) {
    return <div style={{ padding: '32px', textAlign: 'center', color: '#666' }}>Loading session…</div>
  }

  const payload = session.qr_payload

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '24px' }}>
      <h2 style={{ textAlign: 'center', color: '#1a1a2e' }}>💳 ZeroPay</h2>
      <p style={{ textAlign: 'center', color: '#666' }}>Payment request from</p>
      <h3 style={{ textAlign: 'center', fontSize: '1.4rem' }}>{payload.merchant_name}</h3>
      {payload.amount != null && (
        <div style={{ textAlign: 'center', fontSize: '2.5rem', fontWeight: 800, color: '#1a1a2e', margin: '16px 0' }}>
          {payload.currency} {payload.amount.toFixed(2)}
        </div>
      )}
      <button
        onClick={() => navigate('/pay/summary', { state: { payload } })}
        style={{ width: '100%', padding: '16px', background: '#e94560', color: '#fff', border: 'none', borderRadius: '12px', fontSize: '16px', fontWeight: 700, cursor: 'pointer', marginTop: '24px' }}
      >
        {localStorage.getItem('zeropay_token') ? 'Pay Now' : 'Sign In to Pay'}
      </button>
    </div>
  )
}
