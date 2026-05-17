/// ZeroPay MVP feature flags.
///
/// Set a flag to `false` to hide a feature from the current build.
/// Set to `true` to enable it.
///
/// MVP-visible features are `true` by default.
/// Features hidden for MVP are `false` by default — they still exist in code
/// but are excluded from navigation until they're ready.
class FeatureFlags {
  // ── MVP screens (visible) ──────────────────────────────────────────────────
  static const bool authOnboarding        = true;
  static const bool dashboard             = true;
  static const bool paymentSession        = true;  // payment QR / session view
  static const bool paymentQr             = true;
  static const bool requestMoney          = true;
  static const bool receiveMoney          = true;
  static const bool makePayment           = true;
  static const bool paymentLog            = true;
  static const bool transactionHistory    = true;
  static const bool notifications         = true;
  static const bool sharePaymentLink      = true;
  static const bool merchantPaymentFlows  = true;
  static const bool sendMoney             = true;
  static const bool profile               = true;
  static const bool settings              = true;

  // ── Hidden from MVP (keep but do not show in nav/dashboard) ───────────────
  static const bool addMoney              = false;
  static const bool withdraw              = false;
  static const bool remittance            = false;
  static const bool agentMoneyOut         = false;
  static const bool giftCards             = false;
  static const bool billPay               = false;
  static const bool mobileTopup           = false;
  static const bool virtualCards          = false;
}
