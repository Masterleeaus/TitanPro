<?php

use Modules\ZeroPayModule\Adapters\DefaultGatewayAdapter;

return [

    /*
    |--------------------------------------------------------------------------
    | ZeroPayModule Configuration
    |--------------------------------------------------------------------------
    |
    | Module-level configuration values for ZeroPay. Override these values
    | in your application's config/zeropay-module.php after publishing.
    |
    */

    'name' => 'ZeroPayModule',

    /*
    |--------------------------------------------------------------------------
    | Session TTL
    |--------------------------------------------------------------------------
    |
    | Default lifetime (in minutes) for a ZeroPay payment session / QR code.
    | Override via ZEROPAY_SESSION_TTL environment variable.
    |
    */
    'session_ttl_minutes' => env('ZEROPAY_SESSION_TTL', 15),

    /*
    |--------------------------------------------------------------------------
    | Merchant PayID & Name
    |--------------------------------------------------------------------------
    |
    | These values are embedded into every QR payload when the session meta
    | does not carry its own payid / merchant_name keys.
    |
    */
    'payid' => env('ZEROPAY_PAYID', ''),
    'merchant_name' => env('ZEROPAY_MERCHANT_NAME', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Gateway
    |--------------------------------------------------------------------------
    |
    | The gateway adapter that will be resolved when GatewayContract is
    | injected. Must implement Modules\ZeroPayModule\Services\Contracts\GatewayContract.
    |
    */
    'gateway' => DefaultGatewayAdapter::class,

    /*
    |--------------------------------------------------------------------------
    | Gateway Configuration
    |--------------------------------------------------------------------------
    */
    'gateways' => [
        'payid' => [
            'enabled' => env('ZEROPAY_PAYID_ENABLED', true),
            'pay_id' => env('ZEROPAY_PAYID', 'payments@merchant.com'),
            'merchant_name' => env('ZEROPAY_MERCHANT_NAME', 'ZeroPay Merchant'),
        ],
        'bank_transfer' => [
            'enabled' => env('ZEROPAY_BANK_TRANSFER_ENABLED', true),
        ],
        'stripe' => [
            'enabled' => env('ZEROPAY_STRIPE_ENABLED', false),
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
            'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
        'paypal' => [
            'enabled' => env('ZEROPAY_PAYPAL_ENABLED', false),
            'mode' => env('PAYPAL_MODE', 'sandbox'),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'client_secret' => env('PAYPAL_CLIENT_SECRET'),
            'app_id' => env('PAYPAL_APP_ID'),
            'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        ],
        'cryptomus' => [
            'enabled' => env('ZEROPAY_CRYPTOMUS_ENABLED', false),
            'base_url' => env('CRYPTOMUS_BASE_URL', 'https://api.cryptomus.com'),
            'merchant_id' => env('CRYPTOMUS_MERCHANT_ID'),
            'api_key' => env('CRYPTOMUS_API_KEY'),
            'webhook_secret' => env('CRYPTOMUS_WEBHOOK_SECRET'),
        ],
        'cash' => [
            'enabled' => env('ZEROPAY_CASH_ENABLED', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    */
    'rate_limit' => 60,

    /*
    |--------------------------------------------------------------------------
    | VAPID Keys — Web Push Notifications
    |--------------------------------------------------------------------------
    |
    | Generate a key pair with:
    |   vendor/bin/web-push generate-vapid-keys
    | or any online VAPID key generator.
    |
    | Store the values in your application .env file:
    |   VAPID_PUBLIC_KEY=
    |   VAPID_PRIVATE_KEY=
    |   VAPID_SUBJECT=mailto:admin@zeropay.io
    |
    */
    'vapid' => [
        'subject' => env('VAPID_SUBJECT', 'mailto:admin@zeropay.io'),
        'public_key' => env('VAPID_PUBLIC_KEY', ''),
        'private_key' => env('VAPID_PRIVATE_KEY', ''),
    ],

];
