<?php

namespace Modules\ZeroPayModule\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;
use Modules\ZeroPayModule\Models\ZeroPayGatewayLog;
use Modules\ZeroPayModule\Models\ZeroPayWebhookEvent;
use Modules\ZeroPayModule\Settings\ZeroPaySettings;

class ZeroPayControlPanel extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'ZeroPay';

    protected static ?string $slug = 'zeropay/settings';

    protected static ?string $title = 'ZeroPay Control Panel';

    protected static ?int $navigationSort = 99;

    protected static string $view = 'zeropay-module::filament.pages.zeropay-control-panel';

    // -----------------------------------------------------------------------
    // Form state (mirrors ZeroPaySettings fields)
    // -----------------------------------------------------------------------
    public bool $gateway_payid_enabled = false;

    public bool $gateway_bank_transfer_enabled = false;

    public bool $gateway_stripe_enabled = false;

    public bool $gateway_paypal_enabled = false;

    public bool $gateway_cryptomus_enabled = false;

    public bool $gateway_cash_enabled = true;

    public string $payid_identifier = '';

    public string $stripe_key = '';

    public string $stripe_secret = '';

    public string $stripe_webhook_secret = '';

    public string $paypal_client_id = '';

    public string $paypal_client_secret = '';

    public string $paypal_mode = 'sandbox';

    public string $paypal_webhook_id = '';

    public string $cryptomus_merchant_id = '';

    public string $cryptomus_api_key = '';

    public string $cryptomus_webhook_secret = '';

    public int $session_ttl_minutes = 15;

    public int $qr_expiry_warning_minutes = 2;

    public bool $session_auto_expire = true;

    public string $bank_bsb = '';

    public string $bank_account_number = '';

    public float $bank_matching_confidence = 0.8;

    public bool $bank_notify_admin_on_pending_review = true;

    // -----------------------------------------------------------------------
    // Health metrics (populated in mount)
    // -----------------------------------------------------------------------
    public int $webhookQueueDepth = 0;

    public int $pendingReviewCount = 0;

    public array $recentErrors = [];

    public array $lastGatewayChecks = [];

    public function mount(): void
    {
        $settings = $this->resolveSettings();

        if ($settings) {
            $this->fill([
                'gateway_payid_enabled' => $settings->gateway_payid_enabled,
                'gateway_bank_transfer_enabled' => $settings->gateway_bank_transfer_enabled,
                'gateway_stripe_enabled' => $settings->gateway_stripe_enabled,
                'gateway_paypal_enabled' => $settings->gateway_paypal_enabled,
                'gateway_cryptomus_enabled' => $settings->gateway_cryptomus_enabled,
                'gateway_cash_enabled' => $settings->gateway_cash_enabled,
                'payid_identifier' => $settings->payid_identifier,
                'stripe_key' => $settings->stripe_key,
                'stripe_secret' => $settings->stripe_secret,
                'stripe_webhook_secret' => $settings->stripe_webhook_secret,
                'paypal_client_id' => $settings->paypal_client_id,
                'paypal_client_secret' => $settings->paypal_client_secret,
                'paypal_mode' => $settings->paypal_mode,
                'paypal_webhook_id' => $settings->paypal_webhook_id,
                'cryptomus_merchant_id' => $settings->cryptomus_merchant_id,
                'cryptomus_api_key' => $settings->cryptomus_api_key,
                'cryptomus_webhook_secret' => $settings->cryptomus_webhook_secret,
                'session_ttl_minutes' => $settings->session_ttl_minutes,
                'qr_expiry_warning_minutes' => $settings->qr_expiry_warning_minutes,
                'session_auto_expire' => $settings->session_auto_expire,
                'bank_bsb' => $settings->bank_bsb,
                'bank_account_number' => $settings->bank_account_number,
                'bank_matching_confidence' => $settings->bank_matching_confidence,
                'bank_notify_admin_on_pending_review' => $settings->bank_notify_admin_on_pending_review,
            ]);
        }

        $this->form->fill($this->toSettingsArray());

        $this->loadHealthMetrics();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // -----------------------------------------------------------
                // Gateway Settings
                // -----------------------------------------------------------
                Section::make('Gateway Settings')
                    ->description('Toggle payment gateways on or off and configure their credentials.')
                    ->schema([
                        Section::make('PayID')
                            ->schema([
                                Toggle::make('gateway_payid_enabled')
                                    ->label('Enable PayID')
                                    ->reactive(),
                                TextInput::make('payid_identifier')
                                    ->label('PayID Identifier (email or phone)')
                                    ->visible(fn ($get) => $get('gateway_payid_enabled')),
                            ])
                            ->collapsible(),

                        Section::make('Bank Transfer')
                            ->schema([
                                Toggle::make('gateway_bank_transfer_enabled')
                                    ->label('Enable Bank Transfer'),
                            ])
                            ->collapsible(),

                        Section::make('Stripe')
                            ->schema([
                                Toggle::make('gateway_stripe_enabled')
                                    ->label('Enable Stripe')
                                    ->reactive(),
                                TextInput::make('stripe_key')
                                    ->label('Publishable Key')
                                    ->visible(fn ($get) => $get('gateway_stripe_enabled')),
                                TextInput::make('stripe_secret')
                                    ->label('Secret Key')
                                    ->password()
                                    ->visible(fn ($get) => $get('gateway_stripe_enabled')),
                                TextInput::make('stripe_webhook_secret')
                                    ->label('Webhook Signing Secret')
                                    ->password()
                                    ->visible(fn ($get) => $get('gateway_stripe_enabled')),
                            ])
                            ->collapsible(),

                        Section::make('PayPal')
                            ->schema([
                                Toggle::make('gateway_paypal_enabled')
                                    ->label('Enable PayPal')
                                    ->reactive(),
                                TextInput::make('paypal_client_id')
                                    ->label('Client ID')
                                    ->visible(fn ($get) => $get('gateway_paypal_enabled')),
                                TextInput::make('paypal_client_secret')
                                    ->label('Client Secret')
                                    ->password()
                                    ->visible(fn ($get) => $get('gateway_paypal_enabled')),
                                TextInput::make('paypal_mode')
                                    ->label('Mode (sandbox | live)')
                                    ->visible(fn ($get) => $get('gateway_paypal_enabled')),
                                TextInput::make('paypal_webhook_id')
                                    ->label('Webhook ID')
                                    ->visible(fn ($get) => $get('gateway_paypal_enabled')),
                            ])
                            ->collapsible(),

                        Section::make('Cryptomus')
                            ->schema([
                                Toggle::make('gateway_cryptomus_enabled')
                                    ->label('Enable Cryptomus')
                                    ->reactive(),
                                TextInput::make('cryptomus_merchant_id')
                                    ->label('Merchant ID')
                                    ->visible(fn ($get) => $get('gateway_cryptomus_enabled')),
                                TextInput::make('cryptomus_api_key')
                                    ->label('API Key')
                                    ->password()
                                    ->visible(fn ($get) => $get('gateway_cryptomus_enabled')),
                                TextInput::make('cryptomus_webhook_secret')
                                    ->label('Webhook Secret')
                                    ->password()
                                    ->visible(fn ($get) => $get('gateway_cryptomus_enabled')),
                            ])
                            ->collapsible(),

                        Section::make('Cash')
                            ->schema([
                                Toggle::make('gateway_cash_enabled')
                                    ->label('Enable Cash Payments'),
                            ])
                            ->collapsible(),
                    ]),

                // -----------------------------------------------------------
                // Session Settings
                // -----------------------------------------------------------
                Section::make('Session Settings')
                    ->description('Control session lifecycle and QR code behaviour.')
                    ->schema([
                        TextInput::make('session_ttl_minutes')
                            ->label('Session TTL (minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->default(15),
                        TextInput::make('qr_expiry_warning_minutes')
                            ->label('QR Expiry Warning Threshold (minutes before expiry)')
                            ->numeric()
                            ->minValue(0)
                            ->default(2),
                        Toggle::make('session_auto_expire')
                            ->label('Auto-expire sessions'),
                    ]),

                // -----------------------------------------------------------
                // Bank Transfer Settings
                // -----------------------------------------------------------
                Section::make('Bank Transfer Settings')
                    ->description('Configure the receiving bank account and deposit matching.')
                    ->schema([
                        TextInput::make('bank_bsb')
                            ->label('BSB')
                            ->placeholder('000-000'),
                        TextInput::make('bank_account_number')
                            ->label('Account Number'),
                        TextInput::make('bank_matching_confidence')
                            ->label('Matching Confidence Threshold (0–1)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(1)
                            ->default(0.8),
                        Toggle::make('bank_notify_admin_on_pending_review')
                            ->label('Notify admin on pending_review deposits'),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Save settings action wired to the form submit button.
     */
    public function save(): void
    {
        $data = $this->form->getState();

        $settings = $this->resolveSettings();

        if (! $settings) {
            Notification::make()
                ->title('Settings driver not available')
                ->body('spatie/laravel-settings is not configured. Settings cannot be persisted.')
                ->warning()
                ->send();

            return;
        }

        foreach ($data as $property => $value) {
            if (property_exists($settings, $property)) {
                $settings->{$property} = $value;
            }
        }

        $settings->save();

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('zeropay.settings.manage') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    // -----------------------------------------------------------------------
    // Health metrics
    // -----------------------------------------------------------------------

    protected function loadHealthMetrics(): void
    {
        try {
            $this->webhookQueueDepth = ZeroPayWebhookEvent::query()
                ->whereNotIn('status', ['processed', 'failed'])
                ->count();

            $this->pendingReviewCount = ZeroPayBankDeposit::query()
                ->where('status', 'pending_review')
                ->count();

            $this->recentErrors = ZeroPayGatewayLog::query()
                ->whereIn('http_status', range(400, 599))
                ->orderByDesc('created_at')
                ->limit(10)
                ->get(['id', 'gateway', 'event', 'http_status', 'created_at'])
                ->toArray();

            $this->lastGatewayChecks = ZeroPayGatewayLog::query()
                ->select('gateway')
                ->selectRaw('MAX(created_at) as last_check')
                ->groupBy('gateway')
                ->get()
                ->pluck('last_check', 'gateway')
                ->toArray();
        } catch (\Throwable) {
            // Tables may not exist yet during first boot — silently skip.
        }
    }

    // -----------------------------------------------------------------------
    // Internal helpers
    // -----------------------------------------------------------------------

    protected function resolveSettings(): ?ZeroPaySettings
    {
        try {
            if (app()->bound(ZeroPaySettings::class)) {
                return app(ZeroPaySettings::class);
            }
        } catch (\Throwable) {
            // Not available — fall through.
        }

        return null;
    }

    protected function toSettingsArray(): array
    {
        return [
            'gateway_payid_enabled' => $this->gateway_payid_enabled,
            'gateway_bank_transfer_enabled' => $this->gateway_bank_transfer_enabled,
            'gateway_stripe_enabled' => $this->gateway_stripe_enabled,
            'gateway_paypal_enabled' => $this->gateway_paypal_enabled,
            'gateway_cryptomus_enabled' => $this->gateway_cryptomus_enabled,
            'gateway_cash_enabled' => $this->gateway_cash_enabled,
            'payid_identifier' => $this->payid_identifier,
            'stripe_key' => $this->stripe_key,
            'stripe_secret' => $this->stripe_secret,
            'stripe_webhook_secret' => $this->stripe_webhook_secret,
            'paypal_client_id' => $this->paypal_client_id,
            'paypal_client_secret' => $this->paypal_client_secret,
            'paypal_mode' => $this->paypal_mode,
            'paypal_webhook_id' => $this->paypal_webhook_id,
            'cryptomus_merchant_id' => $this->cryptomus_merchant_id,
            'cryptomus_api_key' => $this->cryptomus_api_key,
            'cryptomus_webhook_secret' => $this->cryptomus_webhook_secret,
            'session_ttl_minutes' => $this->session_ttl_minutes,
            'qr_expiry_warning_minutes' => $this->qr_expiry_warning_minutes,
            'session_auto_expire' => $this->session_auto_expire,
            'bank_bsb' => $this->bank_bsb,
            'bank_account_number' => $this->bank_account_number,
            'bank_matching_confidence' => $this->bank_matching_confidence,
            'bank_notify_admin_on_pending_review' => $this->bank_notify_admin_on_pending_review,
        ];
    }
}
