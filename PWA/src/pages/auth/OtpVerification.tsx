import { useState } from 'react'
import { useNavigate, useSearchParams } from 'react-router-dom'
import { authApi } from '../../api/auth'

export default function OtpVerification() {
  const [otp, setOtp] = useState('')
  const [error, setError] = useState('')
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()
  const [params] = useSearchParams()
  const type = params.get('type') ?? 'sms'

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError('')
    try {
      await authApi.verifyOtp(otp, type)
      navigate('/dashboard', { replace: true })
    } catch (err: unknown) {
      const message = err instanceof Error ? err.message : 'OTP verification failed'
      setError(message)
    } finally {
      setLoading(false)
    }
  }

  return (
    <div style={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', background: '#f5f5f5', padding: '16px' }}>
      <div style={{ background: '#fff', borderRadius: '16px', padding: '32px 24px', width: '100%', maxWidth: '400px', boxShadow: '0 2px 24px rgba(0,0,0,0.08)', textAlign: 'center' }}>
        <div style={{ fontSize: '48px', marginBottom: '16px' }}>📱</div>
        <h2 style={{ color: '#1a1a2e', marginTop: 0 }}>OTP Verification</h2>
        <p style={{ color: '#666', marginBottom: '24px' }}>Enter the {type === 'sms' ? 'SMS' : 'email'} code sent to you.</p>
        {error && <div style={{ background: '#fee2e2', color: '#991b1b', padding: '10px 14px', borderRadius: '8px', marginBottom: '16px', fontSize: '14px' }}>{error}</div>}
        <form onSubmit={handleSubmit}>
          <input
            style={{ width: '100%', padding: '12px 14px', border: '1.5px solid #e0e0e0', borderRadius: '8px', fontSize: '24px', textAlign: 'center', letterSpacing: '8px', boxSizing: 'border-box', marginBottom: '16px' }}
            type="text" inputMode="numeric" pattern="[0-9]*" maxLength={6}
            value={otp} onChange={e => setOtp(e.target.value)} required placeholder="000000"
          />
          <button type="submit" disabled={loading} style={{ width: '100%', padding: '14px', background: '#1a1a2e', color: '#fff', border: 'none', borderRadius: '10px', fontSize: '16px', fontWeight: 600 }}>
            {loading ? 'Verifying…' : 'Verify OTP'}
          </button>
        </form>
      </div>
    </div>
  )
}
