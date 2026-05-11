<?php

namespace App\Filament\GroundZero\Pages;

use App\Models\OrganizationSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = 'Panel Settings';

    protected static ?int $navigationSort = 90;

    protected static string $view = 'filament.groundzero.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $orgId = auth()->user()?->organization_id;
        $settings = $orgId ? OrganizationSetting::where('organization_id', $orgId)->first() : null;

        $this->form->fill([
            'company_name'    => $settings?->company_name ?? '',
            'company_phone'   => $settings?->company_phone ?? '',
            'company_email'   => $settings?->company_email ?? '',
            'company_address' => $settings?->company_address ?? '',
            'default_tax_rate' => $settings?->default_tax_rate ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Business Details')
                    ->description('Configure your organization profile visible on invoices and estimates.')
                    ->columns(['sm' => 1, 'lg' => 2])
                    ->schema([
                        TextInput::make('company_name')
                            ->label('Business Name')
                            ->maxLength(255),
                        TextInput::make('company_phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('company_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('company_address')
                            ->label('Address')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        TextInput::make('default_tax_rate')
                            ->label('Default Tax Rate (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
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

        $settings->fill(array_filter([
            'company_name'    => $data['company_name'] ?? null,
            'company_phone'   => $data['company_phone'] ?? null,
            'company_email'   => $data['company_email'] ?? null,
            'company_address' => $data['company_address'] ?? null,
            'default_tax_rate' => ($data['default_tax_rate'] !== '' && $data['default_tax_rate'] !== null) ? $data['default_tax_rate'] : null,
        ], fn ($v) => $v !== null))->save();

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
