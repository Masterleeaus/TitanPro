import { useNavigate } from 'react-router-dom'

export default function PaymentLinks() {
  const navigate = useNavigate()

  return (
    <div style={{ padding: '16px', maxWidth: '480px', margin: '0 auto' }}>
      <h2 style={{ marginTop: 0, color: '#1a1a2e' }}>Payment Links</h2>
      <p style={{ color: '#666', lineHeight: 1.5 }}>
        Manage and share your payment links from this screen.
      </p>
      <button
        onClick={() => navigate('/dashboard')}
        style={{
          marginTop: '12px',
          padding: '12px 16px',
          borderRadius: '10px',
          border: 'none',
          background: '#1a1a2e',
          color: '#fff',
          fontWeight: 600,
          cursor: 'pointer',
        }}
      >
        Back to Dashboard
      </button>
    </div>
  )
}
