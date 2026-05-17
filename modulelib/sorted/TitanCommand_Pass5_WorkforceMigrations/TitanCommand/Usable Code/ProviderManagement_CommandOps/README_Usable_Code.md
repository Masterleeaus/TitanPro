# ProviderManagement deep scan -> Usable Code extract

This folder contains the code from ProviderManagement that is most reusable for managing a team of field operators and provider-led crews alongside a serviceman module / Titan Go field app.

## Best reusable capability areas
- Provider/company profile and lifecycle: create, edit, approve, suspend, activate, soft delete.
- Workforce ownership model: provider -> servicemen, subscribed services, zone assignment, service availability.
- Team administration: onboarding queues, provider detail pages, serviceman list, commission/subscription, collect cash.
- Operator portal/API: dashboard, profile, bank info, payout/withdraw flow, available services, time schedule, account overview.
- Reporting: booking, earnings, expenses, transactions, reviews.
- Messaging/ops emails: registration approved/denied, joining request, suspend/unsuspend.

## Most useful controller method clusters found
- `Http/Controllers/Web/Admin/ProviderController.php` -> __construct, index, create, store, details, updateAccountInfo, deleteAccountInfo, updateSubscription, edit, update, destroy, statusUpdate, serviceAvailability, suspendUpdate, commissionUpdate, onboardingRequest, onboardingDetails, updateApproval, download, reviewsDownload, availableProviderList, providerInfo, reassignProvider, updateBooking, updateRepeatBookings, updateBookingRepeat, sendProviderNotification, fetchProviders, getProviderInfo
- `Http/Controllers/Api/V1/Admin/ProviderController.php` -> __construct, overview, index, reviews, subscribedSubCategories, store, bookings, edit, update, updateSubscription, servicemanList, settingsUpdate, destroy, removeImage, statusUpdate, providerRequest, searchRequest, collectCash
- `Http/Controllers/Web/Provider/ProviderController.php` -> __construct, getUpdatedData, dashboard, updateDashboardEarningGraph, subscribedSubCategories, statusUpdate, accountInfo, adjust, bankInfo, updateBankInfo, availableServices, profileInfo, updateProfile, download, reviewsDownload, getPaymentMethods, deleteProvider, routeFullUrl, searchRouting, filterRoute, storeClickedRoute, recentSearch, setModalClosed, subscribeToTopic, refreshSetupGuideUI
- `Http/Controllers/Api/V1/Provider/ProviderController.php` -> __construct, dashboard, earningStatistics, index, getBankDetails, deleteProvider, updateBankDetails, updateProfile, updatePassword, forgotPassword, otpVerification, resetPassword, updateFcmToken, notifications, subscribedSubCategories, review, changeLanguage, adjust, transaction, updateTutorial
- `Http/Controllers/Api/V1/Provider/TimeScheduleController.php` -> __construct, getAvailableTimeSchedule, setAvailableTimeSchedule

## What was intentionally NOT extracted
- Customer favorite-provider code.
- Customer-facing provider browsing flows.
- Generic layout/theme shell files unless directly needed for team/operator views.
- Packaging/build files not directly relevant to field-operator team management.

## Suggested Titan reuse mapping
- `Provider` -> contractor company / crew owner / franchise node
- `Serviceman` relations -> field operators inside Titan Go
- `SubscribedService` -> skills / service capabilities / trade coverage
- `zone_id` + service availability -> coverage and dispatch eligibility
- `WithdrawRequest` + bank details -> contractor payouts / settlement
- onboarding + suspend/approve flows -> workforce governance

## Copy count
- Files copied: 91
- Missing from requested list: 0