import { useState } from 'react'
import { useNavigate, Link } from 'react-router-dom'
import { authApi } from '../../api/auth'

export default function Register() {
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '', phone: '' })
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()

  const set = (field: string) => (e: React.ChangeEvent<HTMLInputElement>) =>
    setForm(f => ({ ...f, [field]: e.target.value }))

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (form.password !== form.password_confirmation) {
      setError('Passwords do not match')
      return
    }
    setLoading(true)
    setError('')
    try {
      const res = await authApi.register(form)
      localStorage.setItem('zeropay_token', res.data.token)
      localStorage.setItem('user', JSON.stringify(res.data.user))
      navigate('/auth/verify-email', { replace: true })
    } catch (err: unknown) {
      const message = err instanceof Error ? err.message : 'Registration failed'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div style={pageStyle}>
      <div style={cardStyle}>
        <h2 style={{ textAlign: 'center', color: '#1a1a2e', marginTop: 0 }}>Create Account</h2>
        {error && <div style={errorStyle}>{error}</div>}
        <form onSubmit={handleSubmit}>
          {[
            { field: 'name', label: 'Full Name', type: 'text', placeholder: 'John Smith' },
            { field: 'email', label: 'Email', type: 'email', placeholder: 'you@example.com' },
            { field: 'phone', label: 'Phone (optional)', type: 'tel', placeholder: '+61 400 000 000' },
            { field: 'password', label: 'Password', type: 'password', placeholder: '••••••••' },
            { field: 'password_confirmation', label: 'Confirm Password', type: 'password', placeholder: '••••••••' },
          ].map(({ field, label, type, placeholder }) => (
            <div key={field} style={{ marginBottom: '14px' }}>
              <label style={labelStyle}>{label}</label>
              <input style={inputStyle} type={type} value={form[field as keyof typeof form]} onChange={set(field)} placeholder={placeholder} required={field !== 'phone'} />
            </div>
          ))}
          <button type="submit" disabled={loading} style={btnStyle}>
            {loading ? 'Creating account…' : 'Register'}
          </button>
        </form>
        <p style={{ textAlign: 'center', marginTop: '16px', color: '#666', fontSize: '14px' }}>
          Have an account? <Link to="/auth/login">Sign In</Link>
        </p>
      </div>
    </div>
  )
}

const pageStyle: React.CSSProperties = { minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: '#f5f5f5', padding: '16px' }
const cardStyle: React.CSSProperties = { background: '#fff', borderRadius: '16px', padding: '32px 24px', width: '100%', maxWidth: '400px', boxShadow: '0 2px 24px rgba(0,0,0,0.08)' }
const labelStyle: React.CSSProperties = { display: 'block', fontWeight: 600, marginBottom: '6px', fontSize: '14px', color: '#1a1a2e' }
const inputStyle: React.CSSProperties = { width: '100%', padding: '12px 14px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '15px', outline: 'none', boxSizing: 'border-box' }
const btnStyle: React.CSSProperties = { width: '100%', padding: '14px', background: '#1a1a2e', color: '#fff', border: 'none', borderRadius: '10px', fontSize: '16px', fontWeight: 600, marginTop: '8px' }
const errorStyle: React.CSSProperties = { background: '#fee2e2', color: '#991b1b', padding: '10px 14px', borderRadius: '8px', marginBottom: '16px', fontSize: '14px' }
