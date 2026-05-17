<?php

namespace App\Filament\ZeroPay\Pages;

use App\Models\OrganizationSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class StripeSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $title = 'Stripe Settings';

    protected static ?string $navigationLabel = 'Stripe Integration';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 90;

    protected string $view = 'filament.zeropay.pages.stripe-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $orgId = auth()->user()?->organization_id;
        $settings = $orgId ? OrganizationSetting::where('organization_id', $orgId)->first() : null;

        $this->form->fill([
            'stripe_publishable_key' => $settings?->stripe_publishable_key ?? '',
            'stripe_secret_key' => $settings?->getRawOriginal('stripe_secret_key') ? '••••••••' : '',
            'stripe_webhook_secret' => $settings?->getRawOriginal('stripe_webhook_secret') ? '••••••••' : '',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Stripe Integration')
                    ->description('Configure your Stripe account to accept card payments, issue checkout sessions, and process refunds.')
                    ->schema([
                        TextInput::make('stripe_publishable_key')
                            ->label('Publishable Key')
                            ->placeholder('pk_live_…')
                            ->maxLength(200),
                        TextInput::make('stripe_secret_key')
                            ->label('Secret Key')
                            ->password()
                            ->revealable()
                            ->placeholder('sk_live_…')
                            ->maxLength(200)
                            ->helperText('Your secret key is stored encrypted. Leave blank to keep the existing value.'),
                        TextInput::make('stripe_webhook_secret')
                            ->label('Webhook Signing Secret')
                            ->password()
                            ->revealable()
                            ->placeholder('whsec_…')
                            ->maxLength(200)
                            ->helperText('Used to verify incoming Stripe webhook events. Leave blank to keep the existing value.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $orgId = auth()->user()?->organization_id;

        if (! $orgId) {
            Notification::make()
                ->title('No organization found')
                ->danger()
                ->send();

            return;
        }

        $settings = OrganizationSetting::firstOrCreate(['organization_id' => $orgId]);

        $update = [
            'stripe_publishable_key' => $data['stripe_publishable_key'] ?? '',
        ];

        // Only overwrite secrets when the user supplies a new non-placeholder value.
        if (! empty($data['stripe_secret_key']) && $data['stripe_secret_key'] !== '••••••••') {
            $update['stripe_secret_key'] = $data['stripe_secret_key'];
        }

        if (! empty($data['stripe_webhook_secret']) && $data['stripe_webhook_secret'] !== '••••••••') {
            $update['stripe_webhook_secret'] = $data['stripe_webhook_secret'];
        }

        $settings->fill($update)->save();

        Notification::make()
            ->title('Stripe settings saved')
            ->success()
            ->send();
    }
}
