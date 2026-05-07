<?php

namespace App\Filament\Pages;

use App\Models\PlatformSetting;
use App\Models\TitanAccessibilityReport;
use App\Services\Accessibility\AccessibilityAudit;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class UiStudio extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'UI Studio';

    protected static ?string $title = 'UI Studio';

    protected static ?int $navigationSort = 901;

    protected static ?string $slug = 'ui-studio';

    protected string $view = 'filament.pages.ui-studio';

    public array $audit = [];

    public ?int $latestReportId = null;

    public function mount(AccessibilityAudit $accessibilityAudit): void
    {
        $this->audit = $accessibilityAudit->audit();

        $latestReport = TitanAccessibilityReport::query()->latest('created_at')->first();

        if (! $latestReport) {
            $latestReport = $accessibilityAudit->record(PlatformSetting::current(), $this->audit);
        }

        $this->latestReportId = $latestReport?->id;
    }

    public function rerunAudit(AccessibilityAudit $accessibilityAudit): void
    {
        $report = $this->persistAudit($accessibilityAudit);

        Notification::make()
            ->title('Accessibility audit refreshed')
            ->body("Stored report #{$report->id}.")
            ->success()
            ->send();
    }

    public function autoFix(AccessibilityAudit $accessibilityAudit): void
    {
        $result = $accessibilityAudit->autoFix();

        $this->audit = $result['audit'];
        $this->latestReportId = $result['report']->id;

        Notification::make()
            ->title('Accessibility fixes applied')
            ->body(
                empty($result['fixes'])
                    ? 'The current theme tokens already satisfy the managed accessibility checks.'
                    : implode(' ', array_values($result['fixes']))
            )
            ->success()
            ->send();
    }

    public function dismissWarning(string $checkKey, AccessibilityAudit $accessibilityAudit): void
    {
        $settings = PlatformSetting::current();
        $dismissedChecks = array_values(array_unique(array_filter((array) ($settings->accessibility_dismissals ?? []))));

        if (! in_array($checkKey, $dismissedChecks, true)) {
            $dismissedChecks[] = $checkKey;
            sort($dismissedChecks);

            $settings->forceFill([
                'accessibility_dismissals' => $dismissedChecks,
            ])->save();

            cache()->forget('platform_settings');
        }

        $report = $this->persistAudit($accessibilityAudit);

        Notification::make()
            ->title('Warning dismissed')
            ->body("Stored report #{$report->id} with the accepted trade-off.")
            ->success()
            ->send();
    }

    public function restoreWarning(string $checkKey, AccessibilityAudit $accessibilityAudit): void
    {
        $settings = PlatformSetting::current();
        $dismissedChecks = array_values(array_filter(
            (array) ($settings->accessibility_dismissals ?? []),
            fn (string $dismissedCheck): bool => $dismissedCheck !== $checkKey
        ));

        $settings->forceFill([
            'accessibility_dismissals' => $dismissedChecks,
        ])->save();

        cache()->forget('platform_settings');

        $report = $this->persistAudit($accessibilityAudit);

        Notification::make()
            ->title('Warning restored')
            ->body("Stored report #{$report->id} with the warning active again.")
            ->success()
            ->send();
    }

    public function getViewData(): array
    {
        return [
            'themeManagerUrl' => url('/admin/theme-manager'),
            'recentReports' => TitanAccessibilityReport::query()
                ->latest('created_at')
                ->limit(5)
                ->get(),
        ];
    }

    private function persistAudit(AccessibilityAudit $accessibilityAudit): TitanAccessibilityReport
    {
        $settings = PlatformSetting::current();
        $this->audit = $accessibilityAudit->audit($settings);

        $report = $accessibilityAudit->record($settings, $this->audit);
        $this->latestReportId = $report->id;

        return $report;
    }
}
