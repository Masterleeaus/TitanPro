import { useCallback, useEffect, useRef, useState } from 'react'
import { sessionsApi, type QrPayload } from '../../api/sessions'
import { cacheSession } from '../../db'
import { useOnlineStatus } from '../../hooks/useOnlineStatus'
import QRCode from 'qrcode'

const SESSION_POLL_INTERVAL_MS = 3000

export default function RequestMoney() {
  const [amount, setAmount] = useState('')
  const [currency, setCurrency] = useState('AUD')
  const [reference, setReference] = useState('')
  const [qrDataUrl, setQrDataUrl] = useState('')
  const [session, setSession] = useState<{ token: string; payload: QrPayload; status: string } | null>(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [copied, setCopied] = useState(false)
  const [infoMessage, setInfoMessage] = useState('')
  const expiredRefreshTokenRef = useRef<string | null>(null)
  const online = useOnlineStatus()

  const createSession = useCallback(async (isRefresh = false) => {
    if (!isRefresh) {
      setLoading(true)
    }
    setError('')
    try {
      const res = await sessionsApi.create({
        amount: amount ? parseFloat(amount) : undefined,
        currency,
        reference: reference || undefined,
      })
      const { session_token, qr_payload } = res.data
      const qrPayload = JSON.stringify(qr_payload)
      const dataUrl = await QRCode.toDataURL(qrPayload, { width: 300, margin: 2 })
      setQrDataUrl(dataUrl)
      setSession({ token: session_token, payload: qr_payload, status: res.data.status })
      if (isRefresh) {
        setInfoMessage('🔄 Session refreshed with a new QR code.')
      } else {
        setInfoMessage('')
      }
      await cacheSession({
        session_token,
        qr_payload: qr_payload as unknown as Record<string, unknown>,
        expires_at: qr_payload.expiry_timestamp,
      })
    } catch (err: unknown) {
      const message = err instanceof Error ? err.message : 'Failed to create session'
      setError(message)
      if (isRefresh) {
        expiredRefreshTokenRef.current = null
      }
    } finally {
      if (!isRefresh) {
        setLoading(false)
      }
    }
  }, [amount, currency, reference])

  const shareLink = async () => {
    if (!session) return
    const url = `${window.location.origin}/pay/session/${session.token}`
    if (navigator.share) {
      try {
        await navigator.share({ title: 'ZeroPay Request', text: 'Pay me via ZeroPay', url })
        return
      } catch {
        // fallback to clipboard below
      }
    }
    try {
      await navigator.clipboard.writeText(url)
      setCopied(true)
      setTimeout(() => setCopied(false), 2000)
    } catch {
      setError('Unable to share link. Please copy it manually below.')
    }
  }

  useEffect(() => {
    if (!session || session.status !== 'pending') return
    let cancelled = false
    const intervalId = window.setInterval(() => {
      void sessionsApi.get(session.token)
        .then((r) => {
          if (cancelled) return
          setSession((prev) => {
            if (!prev || prev.token !== r.data.session_token) return prev
            return { ...prev, status: r.data.status }
          })
          if (r.data.status === 'completed') {
            setInfoMessage('✅ Payment received.')
            window.clearInterval(intervalId)
          }
        })
        .catch((err) => {
          console.error('Failed to poll session status', err)
        })
    }, SESSION_POLL_INTERVAL_MS)
    return () => {
      cancelled = true
      window.clearInterval(intervalId)
    }
  }, [session])

  useEffect(() => {
    if (!session || session.status !== 'pending') return
    // API provides expiry_timestamp in Unix seconds.
    const expiresAtMs = session.payload.expiry_timestamp * 1000
    const remainingMs = expiresAtMs - Date.now()
    if (remainingMs <= 0) {
      if (expiredRefreshTokenRef.current === session.token) return
      expiredRefreshTokenRef.current = session.token
      void createSession(true)
      return
    }
    expiredRefreshTokenRef.current = null
    const timeoutId = window.setTimeout(() => {
      void createSession(true)
    }, remainingMs)
    return () => window.clearTimeout(timeoutId)
  }, [createSession, session])

  const paymentLink = session ? `${window.location.origin}/pay/session/${session.token}` : ''
  const statusLabel = session
    ? session.status === 'completed'
      ? '✅ completed'
      : session.status === 'pending'
        ? '⏳ pending'
        : `ℹ️ ${session.status}`
    : ''
  const statusColor = session?.status === 'completed'
    ? '#16a34a'
    : session?.status === 'pending'
      ? '#f59e0b'
      : '#6b7280'

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <h2 style={{ color: '#1a1a2e' }}>💸 Get Paid</h2>

      {!session ? (
        <div>
          <div style={{ marginBottom: '14px' }}>
            <label style={labelStyle}>Amount (optional)</label>
            <div style={{ display: 'flex', gap: '8px' }}>
              <select value={currency} onChange={e => setCurrency(e.target.value)} style={{ padding: '12px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '15px' }}>
                {['AUD', 'USD', 'GBP', 'EUR'].map(c => <option key={c}>{c}</option>)}
              </select>
              <input style={inputStyle} type="number" min="0" step="0.01" value={amount} onChange={e => setAmount(e.target.value)} placeholder="0.00" />
            </div>
          </div>
          <div style={{ marginBottom: '20px' }}>
            <label style={labelStyle}>Reference (optional)</label>
            <input style={inputStyle} type="text" value={reference} onChange={e => setReference(e.target.value)} placeholder="INV-001" />
          </div>
          {error && <div style={{ background: '#fee2e2', color: '#991b1b', padding: '10px 14px', borderRadius: '8px', marginBottom: '16px', fontSize: '14px' }}>{error}</div>}
          <button onClick={() => void createSession()} disabled={loading || !online} style={btnStyle}>
            {loading ? 'Creating QR…' : '🔳 Generate QR Code'}
          </button>
          {!online && <p style={{ color: '#f59e0b', fontSize: '13px', textAlign: 'center' }}>⚠️ Connect to internet to generate a payment QR</p>}
        </div>
      ) : (
        <div style={{
          position: 'fixed',
          inset: 0,
          background: '#fff',
          zIndex: 9999,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          padding: '20px',
          textAlign: 'center',
        }}>
          <h3 style={{ color: '#1a1a2e', marginBottom: '6px' }}>Show this QR to the payer</h3>
          <p style={{ color: '#666', fontSize: '13px', marginBottom: '16px' }}>
            Status: <strong style={{ color: statusColor }}>{statusLabel}</strong>
          </p>
          {infoMessage && (
            <p style={{ marginTop: 0, marginBottom: '16px', color: '#1a1a2e', fontWeight: 600 }}>{infoMessage}</p>
          )}
          {qrDataUrl && <img src={qrDataUrl} alt="Payment QR" style={{ width: '100%', maxWidth: '360px', margin: '0 auto 16px', display: 'block', borderRadius: '12px' }} />}
          <p style={{ color: '#666', fontSize: '13px', marginBottom: '8px' }}>Share link fallback:</p>
          <a href={paymentLink} style={{ color: '#0f3460', fontSize: '13px', marginBottom: '16px', wordBreak: 'break-all' }}>{paymentLink}</a>
          <div style={{ display: 'flex', gap: '10px', width: '100%', maxWidth: '420px' }}>
            <button onClick={() => void shareLink()} style={{ ...btnStyle, background: '#0f3460', flex: 1 }}>
              {copied ? '✅ Copied!' : '🔗 Share Link'}
            </button>
            <button onClick={() => { setSession(null); setQrDataUrl('') }} style={{ ...btnStyle, background: '#e0e0e0', color: '#1a1a2e', flex: 1 }}>
              New QR
            </button>
          </div>
        </div>
      )}
    </div>
  )
}

const labelStyle: React.CSSProperties = { display: 'block', fontWeight: 600, marginBottom: '6px', fontSize: '14px', color: '#1a1a2e' }
const inputStyle: React.CSSProperties = { flex: 1, width: '100%', padding: '12px 14px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '15px', outline: 'none', boxSizing: 'border-box' }
const btnStyle: React.CSSProperties = { width: '100%', padding: '14px', background: '#1a1a2e', color: '#fff', border: 'none', borderRadius: '10px', fontSize: '15px', fontWeight: 600, cursor: 'pointer' }
