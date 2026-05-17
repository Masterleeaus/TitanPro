import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App'
import './index.css'
import { registerSW } from 'virtual:pwa-register'

// Show a non-blocking update banner instead of window.confirm (accessibility)
function showUpdateBanner(updateSW: () => Promise<void>) {
  const banner = document.createElement('div')
  banner.setAttribute('role', 'status')
  banner.setAttribute('aria-live', 'polite')
  Object.assign(banner.style, {
    position: 'fixed', bottom: '16px', left: '50%', transform: 'translateX(-50%)',
    background: '#1a1a2e', color: '#fff', padding: '12px 20px', borderRadius: '8px',
    display: 'flex', alignItems: 'center', gap: '12px', zIndex: '9999',
    fontFamily: 'sans-serif', fontSize: '14px', boxShadow: '0 2px 8px rgba(0,0,0,0.3)',
  })

  const msg = document.createElement('span')
  msg.textContent = 'A new version of ZeroPay is available.'

  const btn = document.createElement('button')
  btn.textContent = 'Update'
  btn.setAttribute('aria-label', 'Reload to apply the new version')
  Object.assign(btn.style, {
    background: '#fff', color: '#1a1a2e', border: 'none',
    padding: '6px 14px', borderRadius: '4px', cursor: 'pointer', fontWeight: '600',
  })
  btn.addEventListener('click', () => { banner.remove(); updateSW() })

  banner.appendChild(msg)
  banner.appendChild(btn)
  document.body.appendChild(banner)
}

// Register service worker with accessible update prompt
const updateSW = registerSW({
  onNeedRefresh() {
    showUpdateBanner(updateSW)
  },
  onOfflineReady() {
    console.info('ZeroPay is ready for offline use.')
  },
})

ReactDOM.createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>,
)
