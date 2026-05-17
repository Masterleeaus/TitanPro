import { useEffect, useRef, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import QRCode from 'qrcode';
import './Page.css';

export default function PaymentQR() {
  const navigate = useNavigate();
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [mode, setMode] = useState<'show' | 'scan'>('show');
  // Placeholder — real user ID/payment link comes from auth context
  const paymentLink = `zeropay://pay?to=user_placeholder&t=${Date.now()}`;

  useEffect(() => {
    if (mode === 'show' && canvasRef.current) {
      QRCode.toCanvas(canvasRef.current, paymentLink, { width: 240 }).catch(
        console.error,
      );
    }
  }, [mode, paymentLink]);

  return (
    <div className="page">
      <header className="page-header">
        <button className="back-btn" onClick={() => navigate(-1)} aria-label="Back">←</button>
        <h1 className="page-title">QR Pay</h1>
      </header>

      <div className="page-content page-content--centered">
        <div className="tab-bar">
          <button
            className={`tab-btn${mode === 'show' ? ' tab-btn--active' : ''}`}
            onClick={() => setMode('show')}
          >
            My QR
          </button>
          <button
            className={`tab-btn${mode === 'scan' ? ' tab-btn--active' : ''}`}
            onClick={() => setMode('scan')}
          >
            Scan QR
          </button>
        </div>

        {mode === 'show' && (
          <div className="qr-wrapper">
            <canvas ref={canvasRef} aria-label="Your ZeroPay QR code" />
            <p className="qr-hint">Show this code to receive payment</p>
          </div>
        )}

        {mode === 'scan' && (
          <div className="qr-wrapper">
            <p className="qr-hint">Camera scanner — requires HTTPS in production.</p>
            <p className="qr-hint muted">
              Integrate <code>@zxing/browser</code> BrowserQRCodeReader here.
            </p>
          </div>
        )}
      </div>
    </div>
  );
}
