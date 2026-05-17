import { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'

export default function Splash() {
  const navigate = useNavigate()

  useEffect(() => {
    const token = localStorage.getItem('zeropay_token')
    if (token) {
      navigate('/dashboard', { replace: true })
      return
    }
    const onboardingDone = localStorage.getItem('onboarding_complete')
    const timer = setTimeout(() => {
      navigate(onboardingDone ? '/auth/login' : '/onboard', { replace: true })
    }, 2000)
    return () => clearTimeout(timer)
  }, [navigate])

  return (
    <div style={{
      display: 'flex', flexDirection: 'column', alignItems: 'center',
      justifyContent: 'center', minHeight: '100vh',
      background: 'linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%)',
      color: '#fff', animation: 'splashFadeIn 0.6s ease-out both',
    }}>
      <div style={{ animation: 'splashPulse 1.5s ease-in-out infinite', marginBottom: '20px' }}>
        <svg width="88" height="88" viewBox="0 0 88 88" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect width="88" height="88" rx="22" fill="#e94560" />
          <text x="44" y="57" textAnchor="middle" fontSize="42" fontFamily="system-ui">💳</text>
        </svg>
      </div>
      <h1 style={{ fontSize: '2.5rem', fontWeight: 800, margin: 0, letterSpacing: '-1px' }}>ZeroPay</h1>
      <p style={{ color: 'rgba(255,255,255,0.65)', marginTop: '8px', fontSize: '15px' }}>
        Fast, secure mobile payments
      </p>
      <div style={{ position: 'absolute', bottom: '48px', display: 'flex', gap: '8px' }}>
        {[0, 1, 2].map(i => (
          <span key={i} style={{
            width: 8, height: 8, borderRadius: '50%',
            background: 'rgba(255,255,255,0.5)',
            animation: `splashDot 1.2s ease-in-out ${i * 0.2}s infinite`,
            display: 'inline-block',
          }} />
        ))}
      </div>
      <style>{`
        @keyframes splashFadeIn {
          from { opacity: 0; transform: translateY(16px); }
          to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes splashPulse {
          0%, 100% { transform: scale(1); }
          50%       { transform: scale(1.06); }
        }
        @keyframes splashDot {
          0%, 80%, 100% { opacity: 0.3; transform: scale(0.8); }
          40%            { opacity: 1;   transform: scale(1.2); }
        }
      `}</style>
    </div>
  )
}
