import { useState } from 'react'
import { Link } from 'react-router-dom'
import { authApi } from '../../api/auth'

export default function ForgotPassword() {
  const [email, setEmail] = useState('')
  const [sent, setSent] = useState(false)
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError('')
    try {
      await authApi.forgotPassword(email)
      setSent(true)
    } catch (err: unknown) {
      const message = err instanceof Error ? err.message : 'Request failed'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: '#f5f5f5', padding: '16px' }}>
      <div style={{ background: '#fff', borderRadius: '16px', padding: '32px 24px', width: '100%', maxWidth: '400px', boxShadow: '0 2px 24px rgba(0,0,0,0.08)' }}>
        <h2 style={{ textAlign: 'center', color: '#1a1a2e', marginTop: 0 }}>Reset Password</h2>
        {sent ? (
          <div style={{ textAlign: 'center' }}>
            <div style={{ fontSize: '48px', marginBottom: '16px' }}>📧</div>
            <p>Check your email for reset instructions.</p>
            <Link to="/auth/login">Back to Login</Link>
          </div>
        ) : (
          <form onSubmit={handleSubmit}>
            {error && <div style={{ background: '#fee2e2', color: '#991b1b', padding: '10px 14px', borderRadius: '8px', marginBottom: '16px', fontSize: '14px' }}>{error}</div>}
            <label style={{ display: 'block', fontWeight: 600, marginBottom: '6px', fontSize: '14px' }}>Email</label>
            <input style={{ width: '100%', padding: '12px 14px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '15px', boxSizing: 'border-box', marginBottom: '16px' }} type="email" value={email} onChange={e => setEmail(e.target.value)} required placeholder="you@example.com" />
            <button type="submit" disabled={loading} style={{ width: '100%', padding: '14px', background: '#1a1a2e', color: '#fff', border: 'none', borderRadius: '10px', fontSize: '16px', fontWeight: 600 }}>
              {loading ? 'Sending…' : 'Send Reset Link'}
            </button>
            <p style={{ textAlign: 'center', marginTop: '16px', fontSize: '14px' }}><Link to="/auth/login">Back to Login</Link></p>
          </form>
        )}
      </div>
    </div>
  )
}
