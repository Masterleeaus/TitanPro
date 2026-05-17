import { useEffect, useRef, useState, type TouchEvent } from 'react'
import { useNavigate } from 'react-router-dom'
import { walletApi, type WalletBalance } from '../../api/wallet'
import { transactionsApi, type Transaction } from '../../api/transactions'
import { usePushNotifications } from '../../hooks/usePushNotifications'
import { featureFlags } from '../../featureFlags'

const quickActions = [
  { label: '📷 Make Payment', to: '/pay/scan', phase: 1 },
  { label: '💸 Request $', to: '/request', phase: 1 },
  { label: '📥 Receive', to: '/receive', phase: 1 },
  { label: '🔗 Pay Links', to: '/links', phase: 1 },
  { label: '📋 Transaction History', to: '/transactions', phase: 2 },
]
const RECENT_TRANSACTIONS_LIMIT = 5
const PULL_REFRESH_THRESHOLD = 70

function statusColor(s: string) {
  if (s === 'completed') return '#22c55e'
  if (s === 'failed') return '#ef4444'
  return '#f59e0b'
}

export default function Dashboard() {
  const [balance, setBalance] = useState<WalletBalance | null>(null)
  const [transactions, setTransactions] = useState<Transaction[]>([])
  const [loading, setLoading] = useState(true)
  const [refreshing, setRefreshing] = useState(false)
  const [pullHintVisible, setPullHintVisible] = useState(false)
  const touchStartY = useRef<number | null>(null)
  const containerRef = useRef<HTMLDivElement | null>(null)
  const navigate = useNavigate()
  const { permission, subscribe } = usePushNotifications()

  const load = async () => {
    try {
      const [bal, txs] = await Promise.all([
        walletApi.getBalance(),
        transactionsApi.list({ limit: RECENT_TRANSACTIONS_LIMIT }),
      ])
      setBalance(bal.data)
      setTransactions(txs.data.data.slice(0, RECENT_TRANSACTIONS_LIMIT))
    } catch {
      // handled gracefully
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    void load()
  }, [])

  useEffect(() => {
    // Prompt for push notifications on first dashboard visit if not yet decided.
    if (permission === 'default') {
      void subscribe()
    }
  }, [permission, subscribe])
  const refreshDashboard = async () => {
    setRefreshing(true)
    await load()
    setRefreshing(false)
  }

  const isAtTop = () => (containerRef.current?.scrollTop ?? 0) <= 0

  const handleTouchStart = (event: TouchEvent<HTMLDivElement>) => {
    if (!isAtTop()) return
    touchStartY.current = event.touches[0].clientY
  }

  const handleTouchMove = (event: TouchEvent<HTMLDivElement>) => {
    if (touchStartY.current === null || !isAtTop()) return
    const shouldShowPullHint =
      event.touches[0].clientY - touchStartY.current > PULL_REFRESH_THRESHOLD
    setPullHintVisible((current) => (current === shouldShowPullHint ? current : shouldShowPullHint))
  }

  const handleTouchEnd = () => {
    if (touchStartY.current === null) return
    const shouldRefresh = isAtTop() ? pullHintVisible : false
    touchStartY.current = null
    setPullHintVisible(false)
    if (shouldRefresh && !refreshing) {
      void refreshDashboard()
    }
  }

  const visibleQuickActions = quickActions.filter((action) =>
    action.phase === 1 || featureFlags.dashboardPhase2Tiles,
  )

  const user = (() => {
    try { return JSON.parse(localStorage.getItem('user') ?? '{}') as { name?: string } }
    catch { return {} }
  })()

  return (
    <div
      ref={containerRef}
      onTouchStart={handleTouchStart}
      onTouchMove={handleTouchMove}
      onTouchEnd={handleTouchEnd}
      style={{ padding: '16px', maxWidth: '480px', margin: '0 auto', overflowY: 'auto' }}
    >
      {/* Header */}
      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h2 style={{ margin: 0, fontSize: '1.3rem', color: '#1a1a2e' }}>ZeroPay</h2>
          <p style={{ margin: '2px 0 0', color: '#666', fontSize: '13px' }}>Hi, {user.name ?? 'User'}</p>
        </div>
        <button onClick={() => navigate('/notifications')} style={{ background: 'none', border: 'none', fontSize: '24px' }}>🔔</button>
      </div>
      {(pullHintVisible || refreshing) && (
        <p style={{ marginTop: '-16px', marginBottom: '12px', color: '#666', fontSize: '12px', textAlign: 'center' }}>
          {refreshing ? 'Refreshing…' : 'Release to refresh'}
        </p>
      )}

      {/* Balance card */}
      <div style={{ background: 'linear-gradient(135deg, #1a1a2e, #0f3460)', color: '#fff', borderRadius: '16px', padding: '24px', marginBottom: '24px' }}>
        <p style={{ margin: '0 0 8px', opacity: 0.7, fontSize: '13px' }}>Wallet Balance</p>
        {loading
          ? <div style={{ height: '40px', background: 'rgba(255,255,255,0.1)', borderRadius: '8px' }} />
          : <h1 style={{ margin: 0, fontSize: '2.2rem', fontWeight: 800 }}>
              {balance ? balance.formatted : '—'}
            </h1>
        }
      </div>

      {/* Quick actions */}
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px', marginBottom: '24px' }}>
        {visibleQuickActions.map(({ label, to }) => (
          <button key={to} onClick={() => navigate(to)} style={{
            background: '#fff', border: '1.5px solid #e0e0e0', borderRadius: '12px',
            padding: '16px', fontSize: '14px', fontWeight: 600, color: '#1a1a2e',
            cursor: 'pointer', textAlign: 'left',
          }}>
            {label}
          </button>
        ))}
      </div>

      {/* Recent transactions */}
      <div>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
          <h3 style={{ margin: 0, fontSize: '1rem', color: '#1a1a2e' }}>Recent Transactions</h3>
          <button onClick={() => navigate('/transactions')} style={{ background: 'none', border: 'none', color: '#e94560', fontSize: '13px', cursor: 'pointer' }}>
            See all
          </button>
        </div>
        {loading ? (
          <p style={{ color: '#666', textAlign: 'center' }}>Loading…</p>
        ) : transactions.length === 0 ? (
          <p style={{ color: '#666', textAlign: 'center' }}>No transactions yet</p>
        ) : (
          transactions.map(tx => (
            <div key={tx.id} onClick={() => navigate(`/transactions/${tx.id}`)} style={{
              display: 'flex', justifyContent: 'space-between', alignItems: 'center',
              padding: '12px 0', borderBottom: '1px solid #f0f0f0', cursor: 'pointer',
            }}>
              <div>
                <p style={{ margin: 0, fontWeight: 600, fontSize: '14px' }}>{tx.merchant_name ?? tx.reference ?? 'Payment'}</p>
                <p style={{ margin: '2px 0 0', color: '#666', fontSize: '12px' }}>{tx.gateway} · {new Date(tx.created_at).toLocaleDateString()}</p>
              </div>
              <div style={{ textAlign: 'right' }}>
                <p style={{ margin: 0, fontWeight: 700, color: tx.direction === 'received' ? '#22c55e' : '#1a1a2e' }}>
                  {tx.direction === 'received' ? '+' : '-'}{tx.currency} {tx.amount.toFixed(2)}
                </p>
                <span style={{ fontSize: '11px', color: statusColor(tx.status), fontWeight: 600 }}>{tx.status}</span>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  )
}
