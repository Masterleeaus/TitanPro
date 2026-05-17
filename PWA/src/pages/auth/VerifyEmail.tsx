import { useState, useEffect } from 'react'
import { useNavigate, useSearchParams, Link } from 'react-router-dom'
import { authApi } from '../../api/auth'

type Step = 'pending' | 'success' | 'error'

export default function VerifyEmail() {
  const [searchParams] = useSearchParams()
  const navigate = useNavigate()
  const [step, setStep] = useState<Step>('pending')
  const [message, setMessage] = useState('')

  // Auto-verify if a token is present in the URL query string
  useEffect(() => {
    const token = searchParams.get('token')
    if (!token) return

    authApi
      .verifyEmail(token)
      .then(() => {
        setStep('success')
        setTimeout(() => navigate('/auth/kyc', { replace: true }), 1500)
      })
      .catch((err: unknown) => {
        setStep('error')
        setMessage(
          err instanceof Error ? err.message : 'Verification failed. The link may have expired.',
        )
      })
  }, [searchParams, navigate])

  const handleResend = async () => {
    setMessage('')
    try {
      const raw = localStorage.getItem('user')
      const email: string = raw ? (JSON.parse(raw) as { email: string }).email : ''
      await authApi.resendVerification(email)
      setMessage('A new verification email has been sent.')
    } catch {
      setMessage('Failed to resend. Please try again later.')
    }
  }

  return (
    <div style={pageStyle}>
      <div style={cardStyle}>
        {step === 'success' ? (
          <div style={{ textAlign: 'center' }}>
            <div style={{ fontSize: '56px', marginBottom: '16px' }}>✅</div>
            <h2 style={{ fontSize: '22px', fontWeight: 700, color: '#1a1a2e' }}>Email verified!</h2>
            <p style={{ color: '#666' }}>Redirecting you to complete your profile…</p>
          </div>
        ) : (
          <>
            <div style={{ textAlign: 'center', marginBottom: '28px' }}>
              <div style={{ fontSize: '56px' }}>📧</div>
              <h1 style={{ fontSize: '22px', fontWeight: 700, color: '#1a1a2e', margin: '8px 0 4px' }}>
                Verify your email
              </h1>
              <p style={{ color: '#666', fontSize: '14px', lineHeight: 1.5 }}>
                We sent a verification link to your registered email address.
                Click the link in that email to continue.
              </p>
            </div>

            {step === 'error' && message && (
              <div style={errorStyle}>{message}</div>
            )}
            {step !== 'error' && message && (
              <div style={successStyle}>{message}</div>
            )}

            <button onClick={handleResend} style={btnStyle}>
              Resend verification email
            </button>

            <p style={{ textAlign: 'center', marginTop: '20px', fontSize: '14px' }}>
              <Link to="/auth/login" style={linkStyle}>← Back to Sign In</Link>
            </p>
          </>
        )}
      </div>
    </div>
  )
}

const pageStyle: React.CSSProperties = {
  minHeight: '100vh',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  background: '#f5f5f5',
  padding: '16px',
}

const cardStyle: React.CSSProperties = {
  background: '#fff',
  borderRadius: '16px',
  padding: '32px 24px',
  width: '100%',
  maxWidth: '400px',
  boxShadow: '0 2px 24px rgba(0,0,0,0.08)',
}

const btnStyle: React.CSSProperties = {
  width: '100%',
  background: '#e94560',
  color: '#fff',
  border: 'none',
  borderRadius: '10px',
  padding: '14px',
  fontSize: '16px',
  fontWeight: 600,
  cursor: 'pointer',
}

const errorStyle: React.CSSProperties = {
  background: '#fff0f0',
  border: '1px solid #ffcccc',
  borderRadius: '8px',
  padding: '10px 14px',
  color: '#cc0000',
  fontSize: '14px',
  marginBottom: '16px',
}

const successStyle: React.CSSProperties = {
  background: '#f0fff4',
  border: '1px solid #b2f5c8',
  borderRadius: '8px',
  padding: '10px 14px',
  color: '#1a7a3a',
  fontSize: '14px',
  marginBottom: '16px',
}

const linkStyle: React.CSSProperties = {
  color: '#e94560',
  textDecoration: 'none',
  fontWeight: 600,
}
