<?php

namespace Modules\ZeroPayModule\Services;

use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\ValueObjects\QrPayload;

class QrPayloadValidator
{
    /** Required top-level keys in the QR payload JSON. */
    private const REQUIRED_KEYS = [
        'payid',
        'merchant_name',
        'currency',
        'reference',
        'session_token',
        'expiry_timestamp',
    ];

    /**
     * Decode and validate a QR payload JSON string, returning a populated QrPayload.
     *
     * @throws \InvalidArgumentException When the JSON is malformed or missing required fields.
     */
    public function validate(string $json): QrPayload
    {
        $data = json_decode($json, true);

        if (! is_array($data)) {
            throw new \InvalidArgumentException('QR payload is not valid JSON.');
        }

        foreach (self::REQUIRED_KEYS as $key) {
            if (! array_key_exists($key, $data)) {
                throw new \InvalidArgumentException("QR payload is missing required field: {$key}");
            }
        }

        if (! is_int($data['expiry_timestamp']) && ! is_numeric($data['expiry_timestamp'])) {
            throw new \InvalidArgumentException('QR payload expiry_timestamp must be a numeric Unix timestamp.');
        }

        $payload = new QrPayload;
        $payload->payid = (string) $data['payid'];
        $payload->merchantName = (string) $data['merchant_name'];
        $payload->amount = isset($data['amount']) && $data['amount'] !== null
            ? (float) $data['amount']
            : null;
        $payload->currency = (string) $data['currency'];
        $payload->reference = (string) $data['reference'];
        $payload->sessionToken = (string) $data['session_token'];
        $payload->expiryTimestamp = (int) $data['expiry_timestamp'];

        return $payload;
    }

    /**
     * Return true when the payload's expiry_timestamp is in the past.
     */
    public function isExpired(QrPayload $payload): bool
    {
        return $payload->expiryTimestamp < time();
    }

    /**
     * Return true when the payload's session_token matches the given ZeroPaySession.
     */
    public function matchesSession(QrPayload $payload, ZeroPaySession $session): bool
    {
        return $payload->sessionToken === $session->session_token;
    }
}
