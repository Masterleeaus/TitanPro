import { useState } from 'react'
import { useNavigate, Link } from 'react-router-dom'
import { authApi } from '../../api/auth'

export default function Login() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError('')
    try {
      const res = await authApi.login({ email, password })
      localStorage.setItem('zeropay_token', res.data.token)
      localStorage.setItem('user', JSON.stringify(res.data.user))
      navigate('/dashboard', { replace: true })
    } catch (err: unknown) {
      const message = err instanceof Error ? err.message : 'Login failed. Please try again.'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div style={pageStyle}>
      <div style={cardStyle}>
        <div style={{ textAlign: 'center', marginBottom: '32px' }}>
          <div style={{ fontSize: '40px' }}>💳</div>
          <h1 style={{ fontSize: '1.8rem', fontWeight: 800, color: '#1a1a2e', margin: '8px 0 4px' }}>ZeroPay</h1>
          <p style={{ color: '#666', margin: 0 }}>Sign in to your account</p>
        </div>
        {error && <div style={errorStyle}>{error}</div>}
        <form onSubmit={handleSubmit}>
          <div style={fieldStyle}>
            <label style={labelStyle}>Email</label>
            <input style={inputStyle} type="email" value={email} onChange={e => setEmail(e.target.value)} required placeholder="you@example.com" />
          </div>
          <div style={fieldStyle}>
            <label style={labelStyle}>Password</label>
            <input style={inputStyle} type="password" value={password} onChange={e => setPassword(e.target.value)} required placeholder="••••••••" />
          </div>
          <div style={{ textAlign: 'right', marginBottom: '16px' }}>
            <Link to="/auth/forgot-password" style={{ fontSize: '14px', color: '#e94560' }}>Forgot password?</Link>
          </div>
          <button type="submit" disabled={loading} style={btnStyle}>
            {loading ? 'Signing in…' : 'Sign In'}
          </button>
        </form>
        <p style={{ textAlign: 'center', marginTop: '16px', color: '#666', fontSize: '14px' }}>
          No account? <Link to="/auth/register">Register</Link>
        </p>
      </div>
    </div>
  )
}

const pageStyle: React.CSSProperties = { minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: '#f5f5f5', padding: '16px' }
const cardStyle: React.CSSProperties = { background: '#fff', borderRadius: '16px', padding: '32px 24px', width: '100%', maxWidth: '400px', boxShadow: '0 2px 24px rgba(0,0,0,0.08)' }
const fieldStyle: React.CSSProperties = { marginBottom: '16px' }
const labelStyle: React.CSSProperties = { display: 'block', fontWeight: 600, marginBottom: '6px', fontSize: '14px', color: '#1a1a2e' }
const inputStyle: React.CSSProperties = { width: '100%', padding: '12px 14px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '15px', outline: 'none', boxSizing: 'border-box' }
const btnStyle: React.CSSProperties = { width: '100%', padding: '14px', background: '#1a1a2e', color: '#fff', border: 'none', borderRadius: '10px', fontSize: '16px', fontWeight: 600 }
const errorStyle: React.CSSProperties = { background: '#fee2e2', color: '#991b1b', padding: '10px 14px', borderRadius: '8px', marginBottom: '16px', fontSize: '14px' }
