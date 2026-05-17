import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { walletApi, type ReceiveInfo } from '../../api/wallet'
import { transactionsApi, type Transaction } from '../../api/transactions'
import { cacheSession, getCachedSession } from '../../db'
import { useOnlineStatus } from '../../hooks/useOnlineStatus'
import QRCode from 'qrcode'

const RECEIVE_INFO_CACHE_KEY = 'receive-info'

export default function ReceiveMoney() {
  const [info, setInfo] = useState<ReceiveInfo | null>(null)
  const [qrDataUrl, setQrDataUrl] = useState('')
  const [recent, setRecent] = useState<Transaction[]>([])
  const [copied, setCopied] = useState(false)
  const online = useOnlineStatus()
  const navigate = useNavigate()

  useEffect(() => {
    const load = async () => {
      if (online) {
        try {
          const r = await walletApi.getReceiveInfo()
          setInfo(r.data)
          const url = await QRCode.toDataURL(r.data.qr_payload ?? r.data.payid, { width: 200, margin: 2 })
          setQrDataUrl(url)
          // Cache for offline use
          await cacheSession({
            session_token: RECEIVE_INFO_CACHE_KEY,
            qr_payload: r.data as unknown as Record<string, unknown>,
            expires_at: Date.now() + 24 * 60 * 60 * 1000, // 24 h
          })
          transactionsApi.list({ type: 'received' }).then(r => setRecent(r.data.data.slice(0, 3))).catch(() => {})
          return // loaded from network; skip cache fallback
        } catch {
          // Network failed – fall through to cached data
        }
      }

      // Offline or network fetch failed: load from IndexedDB
      const cached = await getCachedSession(RECEIVE_INFO_CACHE_KEY)
      if (cached) {
        const cachedInfo = cached.qr_payload as unknown as ReceiveInfo
        setInfo(cachedInfo)
        const url = await QRCode.toDataURL(cachedInfo.qr_payload ?? cachedInfo.payid, { width: 200, margin: 2 })
        setQrDataUrl(url)
      }
    }
    void load()
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [online])

  const share = async () => {
    if (!info) return
    const payIdLink = info.qr_payload?.trim() || info.payid
    const text = `Pay me via ZeroPay: ${payIdLink}`
    if (navigator.share) {
      let url: string | undefined
      if (info.qr_payload) {
        try {
          url = new URL(info.qr_payload).toString()
        } catch {
          url = undefined
        }
      }
      await navigator.share({ title: 'My PayID', text, ...(url ? { url } : {}) })
    } else {
      await navigator.clipboard.writeText(payIdLink)
      setCopied(true)
      setTimeout(() => setCopied(false), 2000)
    }
  }

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <h2 style={{ color: '#1a1a2e' }}>📥 Receive Money</h2>

      {!online && (
        <p style={{ color: '#f59e0b', fontSize: '13px', marginBottom: '12px' }}>
          📶 Offline — showing cached QR code
        </p>
      )}

      <div style={{ background: '#fff', borderRadius: '16px', padding: '24px', boxShadow: '0 2px 8px rgba(0,0,0,0.08)', textAlign: 'center', marginBottom: '24px' }}>
        {qrDataUrl && <img src={qrDataUrl} alt="My PayID QR" style={{ width: '180px', height: '180px', borderRadius: '8px' }} />}
        {info && (
          <>
            <p style={{ fontWeight: 700, fontSize: '1.1rem', margin: '12px 0 4px' }}>{info.account_name}</p>
            <p style={{ color: '#666', fontSize: '14px', margin: '0 0 16px' }}>{info.payid}</p>
          </>
        )}
        <button onClick={() => void share()} style={{ background: '#1a1a2e', color: '#fff', border: 'none', borderRadius: '10px', padding: '12px 24px', fontWeight: 600, cursor: 'pointer' }}>
          {copied ? '✅ Copied!' : '🔗 Share My PayID'}
        </button>
      </div>

      <h3 style={{ color: '#1a1a2e', marginBottom: '12px' }}>Recent Received</h3>
      {recent.length === 0
        ? <p style={{ color: '#666' }}>No received payments yet</p>
        : recent.map(tx => (
          <div key={tx.id} onClick={() => navigate(`/receive/confirm/${tx.id}`)} style={{
            display: 'flex', justifyContent: 'space-between', alignItems: 'center',
            padding: '12px 0', borderBottom: '1px solid #f0f0f0', cursor: 'pointer',
          }}>
            <div>
              <p style={{ margin: 0, fontWeight: 600, fontSize: '14px' }}>{tx.merchant_name ?? 'Payment'}</p>
              <p style={{ margin: '2px 0 0', color: '#666', fontSize: '12px' }}>{new Date(tx.created_at).toLocaleDateString()}</p>
            </div>
            <p style={{ margin: 0, fontWeight: 700, color: '#22c55e' }}>+{tx.currency} {tx.amount.toFixed(2)}</p>
          </div>
        ))
      }
    </div>
  )
}
