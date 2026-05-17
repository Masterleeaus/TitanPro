<?php

namespace Modules\ZeroPayModule\Services\Gateways;

use Modules\ZeroPayModule\Contracts\GatewayContract;
use RuntimeException;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalGateway extends AbstractGateway implements GatewayContract
{
    public function __construct(
        ?array $config = null,
        protected ?array $paypalConfig = null,
        protected mixed $orderCreator = null,
        protected mixed $webhookVerifier = null
    ) {
        parent::__construct($config);
        $this->paypalConfig ??= (array) $this->configValue('paypal', []);
    }

    protected function gatewayKey(): string
    {
        return 'paypal';
    }

    public function createPayment(array $session): array
    {
        $this->requireAvailability();

        $providerConfig = $this->buildProviderConfig();
        $orderPayload = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => $session['return_url'] ?? '',
                'cancel_url' => $session['cancel_url'] ?? '',
            ],
            'purchase_units' => [[
                'reference_id' => $this->reference($session, 'pp_'),
                'amount' => [
                    'currency_code' => $this->currency($session, $providerConfig['currency'] ?? 'USD'),
                    'value' => number_format($this->amount($session), 2, '.', ''),
                ],
                'description' => $session['description'] ?? null,
            ]],
        ];

        $order = $this->orderCreator !== null
            ? ($this->orderCreator)($providerConfig, $orderPayload)
            : $this->createPayPalOrder($providerConfig, $orderPayload);

        $order = $this->toArray($order);
        $approvalUrl = $this->extractApprovalUrl($order['links'] ?? []);

        if ($approvalUrl === null) {
            throw new RuntimeException('PayPal approval URL was not returned.');
        }

        return [
            'status' => 'pending',
            'gateway' => 'paypal',
            'reference' => (string) ($order['id'] ?? $this->reference($session, 'pp_')),
            'order_id' => $order['id'] ?? null,
            'approval_url' => $approvalUrl,
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'paypal'];
    }

    public function handleWebhook(array $payload): array
    {
        $providerConfig = $this->buildProviderConfig();
        $validated = $this->webhookVerifier !== null
            ? (bool) ($this->webhookVerifier)($payload, $providerConfig)
            : $this->verifyWebhookPayload($payload, $providerConfig);

        $resource = is_array($payload['resource'] ?? null) ? $payload['resource'] : [];
        $status = $this->statusFromMap(
            $resource['status'] ?? $payload['payment_status'] ?? $payload['status'] ?? $payload['event_type'] ?? null,
            [
                'COMPLETED' => 'completed',
                'PAYMENT.CAPTURE.COMPLETED' => 'completed',
                'PAYMENT.CAPTURE.DENIED' => 'failed',
                'PAYMENT.CAPTURE.DECLINED' => 'failed',
                'CHECKOUT.ORDER.APPROVED' => 'processing',
                'PENDING' => 'pending',
                'FAILED' => 'failed',
                'DENIED' => 'failed',
            ]
        );

        return [
            'processed' => $validated,
            'validated' => $validated,
            'gateway' => 'paypal',
            'event_type' => $payload['event_type'] ?? $payload['txn_type'] ?? null,
            'reference' => $resource['id'] ?? $payload['txn_id'] ?? $payload['id'] ?? null,
            'status' => $status,
            'payload' => $payload,
        ];
    }

    public function calculateFee(float $amount): float
    {
        return round($amount * 0.034 + 0.49, 2);
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'paypal'];
    }

    protected function buildProviderConfig(): array
    {
        $providerConfig = $this->paypalConfig ?? [];
        $gatewayConfig = $this->gatewayConfig();
        $mode = (string) ($gatewayConfig['mode'] ?? $providerConfig['mode'] ?? 'sandbox');

        $providerConfig['mode'] = $mode;
        $providerConfig[$mode] = array_merge($providerConfig[$mode] ?? [], array_filter([
            'client_id' => $gatewayConfig['client_id'] ?? null,
            'client_secret' => $gatewayConfig['client_secret'] ?? null,
            'app_id' => $gatewayConfig['app_id'] ?? null,
        ], static fn ($value): bool => $value !== null && $value !== ''));

        if (! empty($gatewayConfig['webhook_id'])) {
            $providerConfig['webhook_id'] = $gatewayConfig['webhook_id'];
        }

        return $providerConfig;
    }

    protected function createPayPalOrder(array $providerConfig, array $orderPayload): array
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials($providerConfig);
        $provider->getAccessToken();

        return $provider->createOrder($orderPayload);
    }

    protected function verifyWebhookPayload(array $payload, array $providerConfig): bool
    {
        if (isset($payload['verified'])) {
            return (bool) $payload['verified'];
        }

        if (isset($payload['verification_status'])) {
            return in_array(strtoupper((string) $payload['verification_status']), ['SUCCESS', 'SUCCESSFUL', 'VERIFIED'], true);
        }

        if (isset($payload['verify_sign'])) {
            return strtoupper((string) $payload['verify_sign']) === 'VERIFIED';
        }

        $body = $payload['payload'] ?? $payload['body'] ?? null;
        $headers = $payload['headers'] ?? null;

        if (is_string($body) && is_array($headers)) {
            $provider = new PayPalClient;
            $provider->setApiCredentials($providerConfig);

            return (bool) $provider->verifyWebHook($headers, $body);
        }

        $ipnPayload = is_array($payload['ipn'] ?? null) ? $payload['ipn'] : $payload;

        if ($ipnPayload === []) {
            return false;
        }

        return $this->verifyIpn($ipnPayload, (string) ($providerConfig['mode'] ?? 'sandbox'));
    }

    protected function verifyIpn(array $payload, string $mode): bool
    {
        if (! function_exists('curl_init')) {
            return false;
        }

        $body = 'cmd=_notify-validate';
        foreach ($payload as $key => $value) {
            $body .= '&'.rawurlencode((string) $key).'='.rawurlencode((string) $value);
        }

        $endpoint = $mode === 'live'
            ? 'https://ipnpb.paypal.com/cgi-bin/webscr'
            : 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

        $handle = curl_init($endpoint);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['Connection: Close']);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);

        $response = curl_exec($handle);
        curl_close($handle);

        return trim((string) $response) === 'VERIFIED';
    }

    protected function extractApprovalUrl(array $links): ?string
    {
        foreach ($links as $link) {
            if (($link['rel'] ?? null) === 'approve') {
                return $link['href'] ?? null;
            }
        }

        return null;
    }
}
