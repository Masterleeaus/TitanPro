import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { transactionsApi, type Transaction } from '../../api/transactions'

function statusColor(s: string) {
  if (s === 'completed') return '#22c55e'
  if (s === 'failed') return '#ef4444'
  return '#f59e0b'
}

export default function ReceiveConfirm() {
  const { transactionId } = useParams<{ transactionId: string }>()
  const [tx, setTx] = useState<Transaction | null>(null)
  const [error, setError] = useState('')
  const navigate = useNavigate()

  useEffect(() => {
    if (!transactionId) return
    let isActive = true
    let timer: ReturnType<typeof setTimeout> | null = null
    const poll = async () => {
      try {
        const r = await transactionsApi.get(transactionId)
        if (!isActive) return
        setTx(r.data)
        if (r.data.status === 'pending') {
          timer = setTimeout(() => void poll(), 3000)
        }
      } catch {
        if (!isActive) return
        setError('Could not load transaction.')
      }
    }
    void poll()
    return () => {
      isActive = false
      if (timer) clearTimeout(timer)
    }
  }, [transactionId])

  if (error) {
    return <div style={{ padding: '32px', textAlign: 'center' }}><p style={{ color: '#ef4444' }}>{error}</p></div>
  }

  if (!tx) {
    return <div style={{ padding: '32px', textAlign: 'center', color: '#666' }}>Loading…</div>
  }

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '24px' }}>
      <div style={{ textAlign: 'center', marginBottom: '32px' }}>
        <div style={{ fontSize: '64px' }}>{tx.status === 'completed' ? '✅' : tx.status === 'failed' ? '❌' : '⏳'}</div>
        <h2 style={{ color: '#1a1a2e' }}>
          {tx.status === 'completed' ? 'Payment Received!' : tx.status === 'failed' ? 'Payment Failed' : 'Payment Pending…'}
        </h2>
        <div style={{ fontSize: '2rem', fontWeight: 800, color: '#1a1a2e' }}>
          +{tx.currency} {tx.amount.toFixed(2)}
        </div>
      </div>

      <div style={{ background: '#fff', borderRadius: '16px', padding: '20px', boxShadow: '0 2px 8px rgba(0,0,0,0.08)' }}>
        {[
          { label: 'Status', value: <span style={{ color: statusColor(tx.status), fontWeight: 700 }}>{tx.status}</span> },
          { label: 'From', value: tx.merchant_name ?? '—' },
          { label: 'Reference', value: tx.reference ?? '—' },
          { label: 'Gateway', value: tx.gateway },
          { label: 'Date', value: new Date(tx.created_at).toLocaleString() },
        ].map(({ label, value }) => (
          <div key={label} style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 0', borderBottom: '1px solid #f0f0f0' }}>
            <span style={{ color: '#666', fontSize: '14px' }}>{label}</span>
            <span style={{ fontWeight: 600, fontSize: '14px' }}>{value}</span>
          </div>
        ))}
      </div>

      <button onClick={() => navigate('/transactions/' + tx.id)} style={{ width: '100%', marginTop: '20px', padding: '14px', background: '#1a1a2e', color: '#fff', border: 'none', borderRadius: '10px', fontSize: '15px', fontWeight: 600, cursor: 'pointer' }}>
        View Transaction
      </button>
    </div>
  )
}
