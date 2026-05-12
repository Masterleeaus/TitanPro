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
        return app(AnalyseAuditDriftTool::class)->execute($input);
    }

    public function suggestCorrection(array $input): array
    {
        return app(SuggestCorrectionTool::class)->execute($input);
    }
}
