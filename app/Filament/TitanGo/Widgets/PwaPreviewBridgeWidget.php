<?php

namespace App\Filament\TitanGo\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class PwaPreviewBridgeWidget extends Widget
{
    protected string $view = 'filament.titango.widgets.pwa-preview-bridge-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 6;

    protected function getViewData(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return ['technicians' => collect()];
        }

        return [
            'technicians' => User::query()
                ->where('organization_id', $organizationId)
                ->whereHas('roles', fn ($query) => $query->where('name', 'technician'))
                ->orderBy('name')
                ->get(['id', 'name']),
        ];
    }
}
