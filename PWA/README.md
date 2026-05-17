# ZeroPay PWA

Progressive Web App for ZeroPay — mirrors the mobile MVP feature set.

## Tech Stack
- React 18 + Vite + TypeScript
- vite-plugin-pwa (Workbox service worker)
- react-router-dom v6
- idb (IndexedDB)
- @zxing/browser (QR scanner)
- qrcode (QR generation)
- axios (API)

## Setup

```bash
npm install
cp .env.example .env
# Edit .env with your API base URL and VAPID public key
npm run dev
```

## Build

```bash
npm run build
# Output: dist/
```

## Screens
| Route | Screen |
|-------|--------|
| `/splash` | Splash / loading |
| `/onboard` | Onboarding carousel |
| `/auth/login` | Login |
| `/auth/register` | Register |
| `/auth/forgot-password` | Forgot password |
| `/auth/verify-email` | Email verification |
| `/auth/otp` | OTP verification |
| `/dashboard` | Dashboard (balance + quick actions) |
| `/pay/scan` | Make Payment — QR scanner |
| `/pay/summary` | Payment summary + rail selection |
| `/pay/session/:token` | Pay via session link |
| `/request` | Request Money / Get Paid |
| `/receive` | Receive Money (static PayID QR) |
| `/receive/confirm/:id` | Payment confirmation |
| `/transactions` | Transaction history |
| `/transactions/:id` | Transaction detail |
| `/notifications` | Notifications |

## Offline Mode
- IndexedDB stores: `pending_payments`, `cached_transactions`, `cached_sessions`
- Background sync via service worker `sync` event (`sync-payments` tag)
- Offline payment submissions queued and retried on reconnect

## Push Notifications
Set `VITE_VAPID_PUBLIC_KEY` and configure ZeroPayModule backend VAPID keys.
