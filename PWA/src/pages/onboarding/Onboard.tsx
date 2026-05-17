import { useState, useRef } from 'react'
import { useNavigate } from 'react-router-dom'

interface Slide {
  img: string | null
  emoji: string
  title: string
  body: string
}

const slides: Slide[] = [
  {
    img: '/clipart/confirmation.svg',
    emoji: '✅',
    title: 'Get Paid Instantly',
    body: 'Generate a QR code and receive payments from anyone, anywhere.',
  },
  {
    img: null,
    emoji: '📷',
    title: 'Pay Anyone',
    body: 'Scan any ZeroPay QR and complete your payment in seconds.',
  },
  {
    img: '/clipart/wait_for_approval.svg',
    emoji: '📊',
    title: 'Track Everything',
    body: 'Full transaction history and real-time payment notifications.',
  },
]

export default function Onboard() {
  const [current, setCurrent] = useState(0)
  const [transitioning, setTransitioning] = useState(false)
  const touchStartX = useRef<number | null>(null)
  const navigate = useNavigate()

  const goTo = (index: number) => {
    if (transitioning || index === current) return
    setTransitioning(true)
    setCurrent(index)
    setTimeout(() => setTransitioning(false), 350)
  }

  const finish = () => {
    localStorage.setItem('onboarding_complete', '1')
    navigate('/auth/login', { replace: true })
  }

  const next = () => {
    if (current < slides.length - 1) goTo(current + 1)
    else finish()
  }

  const handleTouchStart = (e: React.TouchEvent) => {
    touchStartX.current = e.touches[0].clientX
  }

  const handleTouchEnd = (e: React.TouchEvent) => {
    if (touchStartX.current === null) return
    const delta = touchStartX.current - e.changedTouches[0].clientX
    if (delta > 50 && current < slides.length - 1) goTo(current + 1)
    else if (delta < -50 && current > 0) goTo(current - 1)
    touchStartX.current = null
  }

  const slide = slides[current]

  return (
    <div
      style={{
        display: 'flex', flexDirection: 'column', minHeight: '100vh',
        background: '#fff', overflowX: 'hidden', userSelect: 'none',
      }}
      onTouchStart={handleTouchStart}
      onTouchEnd={handleTouchEnd}
    >
      {/* Header */}
      <div style={{ display: 'flex', justifyContent: 'flex-end', padding: '20px 24px 0' }}>
        <button
          onClick={finish}
          style={{
            background: 'none', border: 'none', color: '#888', fontSize: '14px',
            fontWeight: 600, padding: '4px 8px', cursor: 'pointer',
          }}
        >
          Skip
        </button>
      </div>

      {/* Slide content */}
      <div
        style={{
          flex: 1, display: 'flex', flexDirection: 'column',
          alignItems: 'center', justifyContent: 'center', textAlign: 'center',
          padding: '0 32px',
          opacity: transitioning ? 0 : 1,
          transform: transitioning ? 'translateX(20px)' : 'translateX(0)',
          transition: 'opacity 0.3s ease, transform 0.3s ease',
        }}
      >
        <div style={{ marginBottom: '32px', height: '180px', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
          {slide.img
            ? <img src={slide.img} alt={slide.title} style={{ maxHeight: '180px', maxWidth: '220px' }} />
            : <span style={{ fontSize: '96px', lineHeight: 1 }}>{slide.emoji}</span>
          }
        </div>
        <h2 style={{
          fontSize: '1.75rem', fontWeight: 700, color: '#1a1a2e',
          margin: '0 0 12px', lineHeight: 1.2,
        }}>
          {slide.title}
        </h2>
        <p style={{ color: '#666', fontSize: '1rem', maxWidth: '280px', lineHeight: 1.7, margin: 0 }}>
          {slide.body}
        </p>
      </div>

      {/* Dots indicator */}
      <div style={{ display: 'flex', justifyContent: 'center', gap: '8px', marginBottom: '24px' }}>
        {slides.map((_, i) => (
          <button
            key={i}
            onClick={() => goTo(i)}
            aria-label={`Go to slide ${i + 1}`}
            style={{
              width: i === current ? 24 : 8,
              height: 8,
              borderRadius: '4px',
              background: i === current ? '#e94560' : '#ddd',
              border: 'none',
              padding: 0,
              cursor: 'pointer',
              transition: 'width 0.3s ease, background 0.3s ease',
            }}
          />
        ))}
      </div>

      {/* CTA button */}
      <div style={{ padding: '0 24px 40px' }}>
        <button
          onClick={next}
          style={{
            background: '#1a1a2e', color: '#fff', border: 'none',
            borderRadius: '14px', padding: '16px', fontSize: '16px',
            fontWeight: 600, width: '100%', cursor: 'pointer',
            transition: 'opacity 0.2s',
          }}
        >
          {current < slides.length - 1 ? 'Next' : 'Get Started'}
        </button>
      </div>
    </div>
  )
}
