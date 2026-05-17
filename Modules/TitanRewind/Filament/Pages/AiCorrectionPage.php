<?php

namespace Modules\TitanRewind\Filament\Pages;

use Filament\Pages\Page;
use Modules\TitanRewind\AI\Tools\AnalyseAuditDriftTool;
use Modules\TitanRewind\AI\Tools\SuggestCorrectionTool;

class AiCorrectionPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static string|\UnitEnum|null $navigationGroup = 'Titan Rewind';

    protected static ?string $navigationLabel = 'AI Correction';

    protected static ?int $navigationSort = 14;

    public function analyseDrift(array $input): array
    {
        $this->assertTenantBoundary($input);

        return app(AnalyseAuditDriftTool::class)->execute($input);
    }

    public function suggestCorrection(array $input): array
    {
        $this->assertTenantBoundary($input);

        return app(SuggestCorrectionTool::class)->execute($input);
    }

    private function assertTenantBoundary(array $input): void
    {
        $actorCompanyId = auth()->user()?->company_id;

        if ($actorCompanyId === null) {
            throw new \RuntimeException('Authenticated tenant context is required.');
        }

        if (isset($input['company_id']) && (int) $input['company_id'] !== (int) $actorCompanyId) {
            throw new \RuntimeException('Cross-tenant AI requests are not allowed.');
        }
    }
}
