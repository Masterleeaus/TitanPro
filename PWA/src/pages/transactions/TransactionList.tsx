import { useEffect, useState, useCallback, useRef } from 'react'
import { useNavigate } from 'react-router-dom'
import { transactionsApi, type Transaction } from '../../api/transactions'
import { getCachedTransactions, cacheTransactions } from '../../db'
import { useOnlineStatus } from '../../hooks/useOnlineStatus'

const filters = ['All', 'Sent', 'Received', 'Pending', 'Failed']
const filterQueryMap: Record<string, { type?: string; status?: string }> = {
  Sent: { type: 'sent' },
  Received: { type: 'received' },
  Pending: { status: 'pending' },
  Failed: { status: 'failed' },
}
const loadMoreRootMargin = '120px'

function statusColor(s: string) {
  if (s === 'completed') return { bg: '#dcfce7', text: '#166534' }
  if (s === 'failed') return { bg: '#fee2e2', text: '#991b1b' }
  return { bg: '#fef3c7', text: '#92400e' }
}

export default function TransactionList() {
  const [transactions, setTransactions] = useState<Transaction[]>([])
  const [filter, setFilter] = useState('All')
  const [search, setSearch] = useState('')
  const [page, setPage] = useState(1)
  const [hasMore, setHasMore] = useState(true)
  const [loading, setLoading] = useState(false)
  const loadMoreRef = useRef<HTMLDivElement | null>(null)
  const loadingRef = useRef(false)
  const pageRef = useRef(1)
  const online = useOnlineStatus()
  const navigate = useNavigate()

  const loadTransactions = useCallback(async (p: number, f: string, q: string, replace = false) => {
    setLoading(true)
    try {
      if (!online) {
        const cached = await getCachedTransactions()
        setTransactions(cached.map(c => c.data as unknown as Transaction))
        setHasMore(false)
        return
      }
      const params: Record<string, unknown> = { page: p }
      Object.assign(params, filterQueryMap[f] ?? {})
      if (q) params.search = q
      const res = await transactionsApi.list(params as { page?: number; type?: string; status?: string; search?: string })
      const newTxs = res.data.data
      setTransactions(prev => replace ? newTxs : [...prev, ...newTxs])
      setHasMore(res.data.meta.current_page < res.data.meta.last_page)
      const toCache = newTxs.map(t => ({ id: t.id, data: t as unknown as Record<string, unknown>, synced_at: new Date().toISOString() }))
      await cacheTransactions(toCache.slice(0, 50))
    } catch {
      const cached = await getCachedTransactions()
      if (cached.length > 0) setTransactions(cached.map(c => c.data as unknown as Transaction))
    } finally {
      setLoading(false)
    }
  }, [online])

  // Debounced search
  useEffect(() => {
    const timer = setTimeout(() => {
      setPage(1)
      void loadTransactions(1, filter, search, true)
    }, 300)
    return () => clearTimeout(timer)
  }, [search, filter, loadTransactions])

  useEffect(() => {
    loadingRef.current = loading
  }, [loading])

  useEffect(() => {
    pageRef.current = page
  }, [page])

  useEffect(() => {
    const target = loadMoreRef.current
    if (!target || !hasMore) return
    const observer = new IntersectionObserver(entries => {
      if (!entries[0].isIntersecting || loadingRef.current) return
      const next = pageRef.current + 1
      setPage(next)
      void loadTransactions(next, filter, search)
    }, { rootMargin: loadMoreRootMargin })
    observer.observe(target)
    return () => observer.disconnect()
  }, [hasMore, filter, search, loadTransactions])

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <h2 style={{ color: '#1a1a2e' }}>📋 Transaction History</h2>

      {/* Search */}
      <input
        style={{ width: '100%', padding: '12px 14px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '15px', boxSizing: 'border-box', marginBottom: '12px' }}
        placeholder="Search by reference, merchant…"
        value={search}
        onChange={e => setSearch(e.target.value)}
      />

      {/* Filter tabs */}
      <div style={{ display: 'flex', gap: '6px', overflowX: 'auto', marginBottom: '16px', paddingBottom: '4px' }}>
        {filters.map(f => (
          <button key={f} onClick={() => setFilter(f)} style={{
            padding: '6px 14px', borderRadius: '20px', border: 'none',
            background: filter === f ? '#1a1a2e' : '#e0e0e0',
            color: filter === f ? '#fff' : '#333',
            fontWeight: 600, fontSize: '13px', cursor: 'pointer', whiteSpace: 'nowrap',
          }}>
            {f}
          </button>
        ))}
      </div>

      {!online && <p style={{ color: '#f59e0b', fontSize: '13px', marginBottom: '12px' }}>📶 Showing cached transactions</p>}

      {/* List */}
      {transactions.map(tx => {
        const status = statusColor(tx.status)
        return <div key={tx.id} onClick={() => navigate(`/transactions/${tx.id}`)} style={{
            display: 'flex', justifyContent: 'space-between', alignItems: 'center',
            padding: '12px', background: '#fff', borderRadius: '10px', marginBottom: '8px',
            boxShadow: '0 1px 4px rgba(0,0,0,0.06)', cursor: 'pointer',
          }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
              <div style={{ width: 40, height: 40, borderRadius: '50%', background: tx.direction === 'received' ? '#dcfce7' : '#fee2e2', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '18px' }}>
                {tx.direction === 'received' ? '📥' : '📤'}
              </div>
              <div>
                <p style={{ margin: 0, fontWeight: 600, fontSize: '14px' }}>{tx.merchant_name ?? tx.reference ?? 'Payment'}</p>
                <p style={{ margin: '2px 0 0', color: '#666', fontSize: '12px' }}>{tx.gateway} · {new Date(tx.created_at).toLocaleDateString()}</p>
              </div>
            </div>
            <div style={{ textAlign: 'right' }}>
              <p style={{ margin: 0, fontWeight: 700, color: tx.direction === 'received' ? '#22c55e' : '#1a1a2e', fontSize: '14px' }}>
                {tx.direction === 'received' ? '+' : '-'}{tx.currency} {tx.amount.toFixed(2)}
              </p>
              <span style={{
                fontSize: '11px',
                fontWeight: 700,
                borderRadius: '12px',
                padding: '2px 8px',
                background: status.bg,
                color: status.text,
                textTransform: 'capitalize',
              }}>{tx.status}</span>
            </div>
          </div>
      })}

      {loading && <p style={{ textAlign: 'center', color: '#666' }}>Loading…</p>}
      {!loading && transactions.length === 0 && <p style={{ textAlign: 'center', color: '#666' }}>No transactions found</p>}

      {hasMore && <div ref={loadMoreRef} style={{ height: 1 }} />}
    </div>
  )
}
