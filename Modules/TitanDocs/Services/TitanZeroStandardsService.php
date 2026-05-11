<?php

namespace Modules\TitanDocs\Services;

use Illuminate\Support\Facades\Log;

class TitanZeroStandardsService
{
    public function suggest(array $input): array
    {
        try {
            if (function_exists('titan_zero_standards_suggest')) {
                return (array) titan_zero_standards_suggest($input);
            }

            if (class_exists('Modules\\TitanZero\\Services\\StandardsLibraryService')) {
                $svc = app('Modules\\TitanZero\\Services\\StandardsLibraryService');
                if (method_exists($svc, 'suggest')) {
                    return (array) $svc->suggest($input);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[TitanDocs] TitanZeroStandardsService unavailable: '.$e->getMessage());
        }

        return [];
    }
}
