# ZeroPay Mobile Base — v1 Adaptation Notes

Adapted from: QRPay_Mobile_Full_Clean_Base_v1.zip
Flutter package renamed: `qrpay` → `zeropay`
App display name: QRPay → ZeroPay

---

## Feature Classification

### ✅ MVP — Visible and Active
| Feature | Screen path |
|---|---|
| Auth / Onboarding | lib/views/auth/, lib/views/onboard/ |
| Dashboard | lib/views/navbar/dashboard_screen.dart |
| Payment QR / Session | lib/views/categories/make_payment/ |
| Request Money | lib/views/categories/request_money/ |
| Receive Money | lib/views/categories/received_money/ |
| Payment Log | lib/views/categories/payments_screen/payment_log/ |
| Share Payment Link | lib/views/categories/payments_screen/share_link/ |
| Payment Link (merchant) | lib/views/categories/payments_screen/ |
| Send Money | lib/views/categories/send_money/ |
| Transaction History | lib/views/drawer/transaction_logs_screen.dart |
| Notifications | lib/views/navbar/notification_screen.dart |
| Profile / KYC | lib/views/profile/ |
| Settings | lib/views/drawer/setting_screen.dart |

### 🚧 Hidden from MVP (preserved, not deleted)
Controlled by `FeatureFlags` in `lib/utils/feature_flags.dart`:

| Feature | Flag | Screen path |
|---|---|---|
| Add Money | `FeatureFlags.addMoney` | lib/views/categories/add_money/ |
| Withdraw | `FeatureFlags.withdraw` | lib/views/categories/withdraw/ |
| Remittance | `FeatureFlags.remittance` | lib/views/categories/remittance/ |
| Agent Money Out | `FeatureFlags.agentMoneyOut` | lib/views/categories/agent_moneyout/ |
| Gift Cards | `FeatureFlags.giftCards` | lib/views/gift_card/ |
| Bill Pay | `FeatureFlags.billPay` | lib/views/categories/bill_pay/ |
| Mobile Topup | `FeatureFlags.mobileTopup` | lib/views/categories/mobile_topup/ |
| Virtual Cards | `FeatureFlags.virtualCards` | lib/views/categories/virtual_card/ |

---

## API Surface Remap

### Backend host
- **Old**: `PUT_YOUR_OWN_DOMAIN` (QRPay placeholder)
- **New**: `https://api.zeropay.com.au` (ZeroPay backend — update before release)
- File: `lib/backend/services/api_endpoint.dart`

### API route surface (all routes confirmed present in backend extraction)
| Feature | Mobile endpoint | Backend controller |
|---|---|---|
| Auth login | `/user/login` | Api/User/Auth/LoginController |
| Auth register | `/user/register` | Api/User/Auth/LoginController |
| Forgot password | `/user/forget/password` | Api/User/Auth/ForgotPasswordController |
| Dashboard | `/user/dashboard` | Api/User/UserController |
| Profile | `/user/profile` | Api/User/UserController |
| Notifications | `/user/notifications` | Api/User/UserController |
| Send Money | `/user/send-money/*` | Api/User/SendMoneyController |
| Receive Money | `/user/receive-money` | Api/User/ReceiveMoneyController |
| Make Payment | `/user/make-payment/*` | Api/User/MakePaymentController |
| Payment Links | `/user/payment-links/*` | Api/User/PaymentLinkController |
| Request Money | `/user/request-money/*` | Api/User/RequestMoneyController |
| Transaction Log | `/user/transactions` | Api/User/TransactionController |
| Add Money (hidden MVP) | `/user/add-money/*` | Api/User/AddMoneyController |
| Withdraw (hidden MVP) | `/user/withdraw/*` | Api/User/MoneyOutController |
| Remittance (hidden MVP) | `/user/remittance/*` | Api/User/RemittanceController |
| Bill Pay (hidden MVP) | `/user/bill-pay/*` | Api/User/BillPayController |
| Mobile Topup (hidden MVP) | `/user/mobile-topup/*` | Api/User/MobileTopupController |
| Agent Money Out (hidden MVP) | `/user/money-out/*` | Api/User/AgentMoneyOutController |
| Gift Cards (hidden MVP) | `/user/gift-card/*` | Api/User/GiftCardController |
| Virtual Cards (hidden MVP) | `/user/my-card/*` | Api/User/VirtualCardController |
| App Settings | `/app-settings` | Api/AppSettingsController |
| KYC | `/user/kyc/submit` | Api/User/AuthorizationController |
| 2FA | `/user/security/google-2fa` | Api/User/SecurityController |
| Pusher Beams auth | `/user/pusher/beams-auth` | Api/User/AuthorizationController |

### ZeroPay-specific additions (not yet in mobile — future pass)
- ZeroPay payment session endpoints (when backend implements)
- PayID / bank transfer rail hooks
- Zero-fee-first rail selection UI
- Stripe/payment callback surfaces (webhook)
- Twilio/notification-related flows
- Org/subscription/account boundary surfaces

---

## Branding Changes Made
- Package name: `qrpay` → `zeropay`
- App display name: `QRPay` → `ZeroPay`
- Android package ID: `com.qrpay` → `com.zeropay`
- iOS bundle prefix: updated
- API host: updated placeholder

## Known Blockers / Next Steps
1. `mainDomain` in `api_endpoint.dart` must be updated to the real ZeroPay backend URL before connecting
2. Firebase config (`google-services.json`, `GoogleService-Info.plist`) still holds QRPay Firebase project — needs ZeroPay Firebase project
3. Pusher Beams instance ID must be updated to ZeroPay Pusher instance
4. App launcher icon still uses QRPay icon (`assets/logo/app_launcher.png`) — needs ZeroPay brand icon
5. Stripe/PayPal/Flutterwave keys are placeholders — backend-driven, but confirm with ZeroPay backend config
6. KYC flow matches backend schema — verify field mapping with ZeroPay backend KYC setup
7. Hidden MVP features (add money, withdraw, remittance, etc.) are preserved in code behind `FeatureFlags` — enable per-feature as backend confirms readiness
