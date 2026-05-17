<?php

namespace Modules\ZeroPayModule\ValueObjects;

/**
 * Represents the structured payload embedded in a ZeroPay dynamic QR code.
 *
 * All fields map directly to the PayID QR spec defined in
 * docs/zeropay/09_payid_qr_spec.md.
 */
class QrPayload
{
    /** Merchant PayID address (e.g. "merchant@zeropay.io"). */
    public string $payid;

    /** Human-readable merchant display name (e.g. "Acme Pty Ltd"). */
    public string $merchantName;

    /** Payment amount, or null for open/variable-amount sessions. */
    public ?float $amount;

    /** ISO 4217 currency code (e.g. "AUD"). */
    public string $currency;

    /** Merchant invoice or order reference (e.g. "INV-2026-001"). */
    public string $reference;

    /** Unique session identifier used to correlate the scan with a ZeroPaySession. */
    public string $sessionToken;

    /** Unix timestamp after which this QR code must be considered expired. */
    public int $expiryTimestamp;
}
