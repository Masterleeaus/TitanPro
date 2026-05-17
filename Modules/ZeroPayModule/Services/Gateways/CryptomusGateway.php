<?php

namespace Modules\ZeroPayModule\Services\Gateways;

use Illuminate\Support\Facades\Http;
use Modules\ZeroPayModule\Contracts\GatewayContract;
use RuntimeException;

class CryptomusGateway extends AbstractGateway implements GatewayContract
{
    public function __construct(
        ?array $config = null,
        protected mixed $requestHandler = null
    ) {
        parent::__construct($config);
    }

    protected function gatewayKey(): string
    {
        return 'cryptomus';
    }

    public function createPayment(array $session): array
    {
        $this->requireAvailability();

        $payload = [
            'amount' => number_format($this->amount($session), 2, '.', ''),
            'currency' => $this->currency($session),
            'order_id' => $this->reference($session, 'crypto_'),
            'network' => $session['network'] ?? null,
            'callback_url' => $session['callback_url'] ?? null,
            'success_url' => $session['success_url'] ?? null,
            'fail_url' => $session['fail_url'] ?? null,
            'payer_email' => $session['receipt_email'] ?? null,
        ];

        $payload = array_filter($payload, static fn ($value): bool => $value !== null && $value !== '');
        $response = $this->requestHandler !== null
            ? ($this->requestHandler)('/v1/invoice/create', $payload, $this->buildHeaders($payload))
            : $this->sendCryptomusRequest('/v1/invoice/create', $payload);

        $response = $this->toArray($response);
        $result = is_array($response['result'] ?? null) ? $response['result'] : $response;
        $invoiceUrl = $result['url'] ?? $result['invoice_url'] ?? $result['pay_url'] ?? null;

        if ($invoiceUrl === null) {
            throw new RuntimeException('Cryptomus invoice URL was not returned.');
        }

        return [
            'status' => 'pending',
            'gateway' => 'cryptomus',
            'reference' => (string) ($result['uuid'] ?? $payload['order_id']),
            'order_id' => $payload['order_id'],
            'invoice_url' => $invoiceUrl,
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'cryptomus'];
    }

    public function handleWebhook(array $payload): array
    {
        $rawPayload = (string) ($payload['payload'] ?? $payload['body'] ?? '');
        $signature = (string) ($payload['signature'] ?? $payload['sign'] ?? '');
        $secret = (string) ($this->gatewayConfig()['webhook_secret'] ?? $this->gatewayConfig()['api_key'] ?? '');

        if ($rawPayload === '' || $signature === '' || $secret === '') {
            return [
                'processed' => false,
                'validated' => false,
                'gateway' => 'cryptomus',
                'status' => 'failed',
            ];
        }

        $expected = hash_hmac('sha256', $rawPayload, $secret);
        if (! hash_equals($expected, $signature)) {
            return [
                'processed' => false,
                'validated' => false,
                'gateway' => 'cryptomus',
                'status' => 'failed',
            ];
        }

        $event = json_decode($rawPayload, true);
        $event = is_array($event) ? $event : [];
        $status = $this->statusFromMap($event['status'] ?? $event['payment_status'] ?? null, [
            'PAID' => 'completed',
            'PAID_OVER' => 'completed',
            'CONFIRM_CHECK' => 'processing',
            'PROCESS' => 'processing',
            'FAIL' => 'failed',
            'CANCEL' => 'failed',
            'SYSTEM_FAIL' => 'failed',
            'WRONG_AMOUNT' => 'failed',
        ]);

        return [
            'processed' => true,
            'validated' => true,
            'gateway' => 'cryptomus',
            'reference' => $event['uuid'] ?? $event['order_id'] ?? null,
            'status' => $status,
            'event_type' => $event['type'] ?? null,
            'payload' => $event,
        ];
    }

    public function calculateFee(float $amount): float
    {
        return round($amount * 0.01, 2);
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'cryptomus'];
    }

    protected function sendCryptomusRequest(string $endpoint, array $payload): array
    {
        $baseUrl = rtrim((string) ($this->gatewayConfig()['base_url'] ?? 'https://api.cryptomus.com'), '/');

        return Http::withHeaders($this->buildHeaders($payload))
            ->post($baseUrl.$endpoint, $payload)
            ->throw()
            ->json();
    }

    protected function buildHeaders(array $payload): array
    {
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $apiKey = (string) ($this->gatewayConfig()['api_key'] ?? '');

        return [
            'merchant' => (string) ($this->gatewayConfig()['merchant_id'] ?? ''),
            'sign' => $body === false ? '' : hash_hmac('sha256', $body, $apiKey),
            'Content-Type' => 'application/json',
        ];
    }
}
