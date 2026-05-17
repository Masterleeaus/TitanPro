import { useEffect, useState } from 'react'
import { useParams, useNavigate } from 'react-router-dom'
import { transactionsApi, type Transaction } from '../../api/transactions'

function StatusBadge({ status }: { status: string }) {
  const colors: Record<string, { bg: string; text: string }> = {
    completed: { bg: '#dcfce7', text: '#166534' },
    failed: { bg: '#fee2e2', text: '#991b1b' },
    pending: { bg: '#fef3c7', text: '#92400e' },
  }
  const c = colors[status] ?? colors.pending
  return (
    <span style={{ background: c.bg, color: c.text, padding: '3px 10px', borderRadius: '20px', fontSize: '12px', fontWeight: 700 }}>
      {status}
    </span>
  )
}

const timeline = ['created', 'pending', 'completed']

export default function TransactionDetail() {
  const { id } = useParams<{ id: string }>()
  const [tx, setTx] = useState<Transaction | null>(null)
  const [error, setError] = useState('')
  const navigate = useNavigate()

  useEffect(() => {
    if (!id) return
    transactionsApi.get(id).then(r => setTx(r.data)).catch(() => setError('Transaction not found.'))
  }, [id])

  if (error) return <div style={{ padding: '32px', textAlign: 'center', color: '#ef4444' }}>{error}</div>
  if (!tx) return <div style={{ padding: '32px', textAlign: 'center', color: '#666' }}>Loading…</div>

  const currentStep = tx.status === 'failed' ? 3 : timeline.indexOf(tx.status === 'completed' ? 'completed' : tx.status)
  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <button onClick={() => navigate(-1)} style={{ background: 'none', border: 'none', color: '#e94560', cursor: 'pointer', fontSize: '14px', marginBottom: '16px' }}>← Back</button>

      <div style={{ textAlign: 'center', marginBottom: '24px' }}>
        <div style={{ fontSize: '48px' }}>{tx.direction === 'received' ? '📥' : '📤'}</div>
        <div style={{ fontSize: '2rem', fontWeight: 800, color: '#1a1a2e', margin: '8px 0' }}>
          {tx.direction === 'received' ? '+' : '-'}{tx.currency} {tx.amount.toFixed(2)}
        </div>
        <StatusBadge status={tx.status} />
      </div>

      {/* Details */}
      <div style={{ background: '#fff', borderRadius: '16px', padding: '20px', boxShadow: '0 2px 8px rgba(0,0,0,0.08)', marginBottom: '20px' }}>
        {[
          { label: 'Type', value: tx.type },
          { label: 'Amount', value: tx.amount.toFixed(2) },
          { label: 'Currency', value: tx.currency },
          { label: 'Gateway', value: tx.gateway },
          { label: tx.direction === 'received' ? 'From' : 'To', value: tx.merchant_name ?? '—' },
          { label: 'Reference', value: tx.reference ?? '—' },
          { label: 'Fee', value: `${tx.currency} ${tx.fee.toFixed(2)}` },
          { label: 'Status', value: tx.status },
          { label: 'Timestamp', value: new Date(tx.created_at).toLocaleString() },
        ].map(({ label, value }) => (
          <div key={label} style={{ display: 'flex', justifyContent: 'space-between', padding: '10px 0', borderBottom: '1px solid #f0f0f0' }}>
            <span style={{ color: '#666', fontSize: '14px' }}>{label}</span>
            <span style={{ fontWeight: 600, fontSize: '14px' }}>{value}</span>
          </div>
        ))}
      </div>

      <button
        type="button"
        onClick={async () => {
          const receiptText = [
            `Transaction #${tx.id}`,
            `Type: ${tx.type}`,
            `Amount: ${tx.currency} ${tx.amount.toFixed(2)}`,
            `Status: ${tx.status}`,
            `Reference: ${tx.reference ?? '—'}`,
            `Merchant: ${tx.merchant_name ?? '—'}`,
            `Gateway: ${tx.gateway}`,
            `Fee: ${tx.currency} ${tx.fee.toFixed(2)}`,
            `Timestamp: ${new Date(tx.created_at).toLocaleString()}`,
          ].join('\n')
          try {
            if (navigator.share) {
              await navigator.share({ title: `Transaction #${tx.id}`, text: receiptText })
              return
            }
          } catch (error) {
            if ((error as Error).name !== 'AbortError') {
              console.error('Failed to share receipt:', error)
              window.alert('Unable to share receipt right now. Opening print view instead.')
            }
          }
          window.print()
        }}
        style={{ width: '100%', padding: '12px', marginBottom: '20px', borderRadius: '10px', border: 'none', background: '#1a1a2e', color: '#fff', fontWeight: 700, cursor: 'pointer' }}
      >
        Print / Share Receipt
      </button>

      {/* Timeline */}
      <div style={{ background: '#fff', borderRadius: '16px', padding: '20px', boxShadow: '0 2px 8px rgba(0,0,0,0.08)' }}>
        <h4 style={{ margin: '0 0 16px', color: '#1a1a2e' }}>Status Timeline</h4>
        {timeline.map((step, i) => (
          <div key={step} style={{ display: 'flex', alignItems: 'center', gap: '12px', marginBottom: '12px' }}>
            <div style={{
              width: 24, height: 24, borderRadius: '50%', flexShrink: 0,
              background: i <= currentStep ? '#22c55e' : '#e0e0e0',
              display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '12px', color: '#fff',
            }}>
              {i <= currentStep ? '✓' : ''}
            </div>
            <span style={{ color: i <= currentStep ? '#1a1a2e' : '#999', fontWeight: i === currentStep ? 700 : 400, textTransform: 'capitalize' }}>
              {step}
            </span>
          </div>
        ))}
        {tx.status === 'failed' && (
          <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
            <div style={{ width: 24, height: 24, borderRadius: '50%', background: '#ef4444', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '12px', color: '#fff' }}>✗</div>
            <span style={{ color: '#ef4444', fontWeight: 700 }}>Failed</span>
          </div>
        )}
      </div>
    </div>
  )
}
