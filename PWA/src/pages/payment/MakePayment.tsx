import { useEffect, useRef, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { BrowserQRCodeReader } from '@zxing/browser'
import type { QrPayload } from '../../api/sessions'

export default function MakePayment() {
  const videoRef = useRef<HTMLVideoElement>(null)
  const [error, setError] = useState('')
  const [scanning, setScanning] = useState(true)
  const navigate = useNavigate()
  const readerRef = useRef<BrowserQRCodeReader | null>(null)
  const processedRef = useRef(false)

  useEffect(() => {
    const reader = new BrowserQRCodeReader()
    readerRef.current = reader
    processedRef.current = false

    if (!videoRef.current) return

    reader.decodeFromVideoDevice(undefined, videoRef.current, (result, _err) => {
      if (!result || processedRef.current) return
      processedRef.current = true
      setScanning(false)
      try {
        const payload = JSON.parse(result.getText()) as QrPayload
        const now = Math.floor(Date.now() / 1000)
        if (payload.expiry_timestamp && payload.expiry_timestamp < now) {
          setError('This QR code has expired.')
          processedRef.current = false
          return
        }
        navigate('/pay/summary', { state: { payload } })
      } catch {
        // Try as a URL
        const text = result.getText()
        const match = text.match(/\/pay\/session\/([^/?#]+)/)
        if (match) {
          navigate(`/pay/session/${match[1]}`)
        } else {
          setError('Invalid QR code. Please scan a ZeroPay QR.')
          processedRef.current = false
          setScanning(true)
        }
      }
    }).catch((e: unknown) => {
      setError('Camera access denied. Please allow camera permissions.')
      console.error(e)
    })

    return () => {
      BrowserQRCodeReader.releaseAllStreams()
    }
  }, [navigate])

  return (
    <div style={{ maxWidth: '480px', margin: '0 auto', padding: '16px' }}>
      <h2 style={{ color: '#1a1a2e', marginBottom: '16px' }}>📷 Scan QR to Pay</h2>
      {error && (
        <div style={{ background: '#fee2e2', color: '#991b1b', padding: '12px', borderRadius: '8px', marginBottom: '16px' }}>
          {error}
          {!scanning && <button onClick={() => { setError(''); processedRef.current = false; setScanning(true) }} style={{ marginLeft: '12px', background: 'none', border: 'none', color: '#e94560', cursor: 'pointer' }}>Retry</button>}
        </div>
      )}
      <div style={{ position: 'relative', borderRadius: '16px', overflow: 'hidden', background: '#000', aspectRatio: '1' }}>
        <video ref={videoRef} style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
        <div style={{
          position: 'absolute', inset: 0, border: '2px solid rgba(255,255,255,0.3)',
          pointerEvents: 'none',
        }}>
          <div style={{
            position: 'absolute', top: '50%', left: '50%',
            transform: 'translate(-50%,-50%)',
            width: '60%', height: '60%',
            border: '2px solid #e94560',
            borderRadius: '8px',
          }} />
        </div>
      </div>
      <p style={{ color: '#666', textAlign: 'center', marginTop: '16px', fontSize: '14px' }}>
        Point your camera at a ZeroPay QR code
      </p>
    </div>
  )
}
