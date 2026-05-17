<?php

namespace Modules\ZeroPayModule\Settings;

use Spatie\LaravelSettings\Settings;

/**
 * ZeroPay persistent settings stored via spatie/laravel-settings.
 *
 * Publish and run the settings migration before use:
 *   php artisan vendor:publish --tag="settings"
 *   php artisan migrate
 */
class ZeroPaySettings extends Settings
{
    // -----------------------------------------------------------------------
    // Gateway toggles
    // -----------------------------------------------------------------------
    public bool $gateway_payid_enabled = false;

    public bool $gateway_bank_transfer_enabled = false;

    public bool $gateway_stripe_enabled = false;

    public bool $gateway_paypal_enabled = false;

    public bool $gateway_cryptomus_enabled = false;

    public bool $gateway_cash_enabled = true;

    // -----------------------------------------------------------------------
    // Per-gateway credentials
    // -----------------------------------------------------------------------

    /** PayID email or phone */
    public string $payid_identifier = '';

    /** Stripe publishable key */
    public string $stripe_key = '';

    /** Stripe secret key */
    public string $stripe_secret = '';

    /** Stripe webhook signing secret */
    public string $stripe_webhook_secret = '';

    /** PayPal client ID */
    public string $paypal_client_id = '';

    /** PayPal client secret */
    public string $paypal_client_secret = '';

    /** PayPal mode: sandbox | live */
    public string $paypal_mode = 'sandbox';

    /** PayPal webhook ID */
    public string $paypal_webhook_id = '';

    /** Cryptomus merchant ID */
    public string $cryptomus_merchant_id = '';

    /** Cryptomus API key */
    public string $cryptomus_api_key = '';

    /** Cryptomus webhook secret */
    public string $cryptomus_webhook_secret = '';

    // -----------------------------------------------------------------------
    // Session settings
    // -----------------------------------------------------------------------

    /** Payment session TTL in minutes */
    public int $session_ttl_minutes = 15;

    /** Minutes before expiry to show QR warning */
    public int $qr_expiry_warning_minutes = 2;

    /** Auto-expire sessions when TTL is reached */
    public bool $session_auto_expire = true;

    // -----------------------------------------------------------------------
    // Bank transfer settings
    // -----------------------------------------------------------------------

    /** Receiving BSB (format: 000-000) */
    public string $bank_bsb = '';

    /** Receiving account number */
    public string $bank_account_number = '';

    /** Minimum confidence threshold for automatic deposit matching (0–1) */
    public float $bank_matching_confidence = 0.8;

    /** Notify admin when a deposit enters pending_review */
    public bool $bank_notify_admin_on_pending_review = true;

    public static function group(): string
    {
        return 'zeropay';
    }
}
