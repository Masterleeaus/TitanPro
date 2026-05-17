<?php

namespace Modules\ZeroPayModule\Services;

use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\ValueObjects\QrPayload;

class QrPayloadBuilder
{
    /**
     * @param  string  $defaultPayId  Merchant PayID (e.g. "merchant@zeropay.io"), used when the
     *                                session meta does not carry a "payid" key.
     * @param  string  $defaultMerchantName  Merchant display name, used when session meta does not carry
     *                                       a "merchant_name" key.
     * @param  int  $sessionTtlMinutes  Default TTL when the session has no explicit expiry.
     * @param  callable|null  $qrRenderer  Optional QR-image renderer: fn(string $content, int $size): string.
     *                                     Must return a base64-encoded PNG string.
     *                                     When null the builder falls back to chillerlan/php-qrcode.
     */
    public function __construct(
        private string $defaultPayId = '',
        private string $defaultMerchantName = '',
        private int $sessionTtlMinutes = 15,
        /** @var callable|null */
        private mixed $qrRenderer = null,
    ) {}

    /**
     * Build a spec-compliant QrPayload from a ZeroPaySession.
     */
    public function build(ZeroPaySession $session): QrPayload
    {
        $meta = (array) ($session->meta ?? []);

        $payload = new QrPayload;
        $payload->payid = (string) ($meta['payid'] ?? $this->defaultPayId);
        $payload->merchantName = (string) ($meta['merchant_name'] ?? $this->defaultMerchantName);
        $payload->amount = $session->amount !== null ? (float) $session->amount : null;
        $payload->currency = (string) ($session->currency ?: 'AUD');
        $payload->reference = (string) ($meta['reference'] ?? $session->session_token);
        $payload->sessionToken = (string) $session->session_token;

        if ($session->expires_at !== null) {
            $payload->expiryTimestamp = $session->expires_at->getTimestamp();
        } else {
            $payload->expiryTimestamp = (int) (time() + $this->sessionTtlMinutes * 60);
        }

        return $payload;
    }

    /**
     * Build the fallback URL for a given session token.
     */
    public function buildFallbackUrl(string $token): string
    {
        return '/pay/session/'.$token;
    }

    /**
     * Encode a QrPayload as a spec-compliant JSON string.
     */
    public function toJson(QrPayload $payload): string
    {
        return json_encode([
            'payid' => $payload->payid,
            'merchant_name' => $payload->merchantName,
            'amount' => $payload->amount,
            'currency' => $payload->currency,
            'reference' => $payload->reference,
            'session_token' => $payload->sessionToken,
            'expiry_timestamp' => $payload->expiryTimestamp,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Generate a base64-encoded PNG QR code image for the given payload.
     *
     * @param  int  $size  Desired pixel size of the output image.
     * @return string Base64-encoded PNG (without the data-URI prefix).
     *
     * @throws \RuntimeException When no QR renderer is available.
     */
    public function toQrImage(QrPayload $payload, int $size = 400): string
    {
        $content = $this->toJson($payload);

        if ($this->qrRenderer !== null) {
            return ($this->qrRenderer)($content, $size);
        }

        if (! class_exists(QRCode::class)) {
            throw new \RuntimeException(
                'chillerlan/php-qrcode is required for QR image generation. '.
                'Add it with: composer require chillerlan/php-qrcode'
            );
        }

        $options = new QROptions([
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'scale' => max(1, (int) ($size / 21)),
            'imageBase64' => true,
        ]);

        return (new QRCode($options))->render($content);
    }
}
