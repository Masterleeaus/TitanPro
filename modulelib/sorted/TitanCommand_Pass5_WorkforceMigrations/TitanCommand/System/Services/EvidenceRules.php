<?php

namespace App\Extensions\TitanCommand\System\Services;

use App\Extensions\TitanCommand\System\Models\Work\WorkJobTemplate;
use App\Extensions\TitanCommand\System\Models\Work\WorkJobTemplateItem;

class EvidenceRules
{
    /**
     * Returns the active evidence requirements for this tenant.
     * Falls back to global defaults (company_id/user_id = 0).
     */
    public static function requirements(int $companyId, int $userId): array
    {
        $tpl = WorkJobTemplate::query()
            ->where('template_type', 'evidence_rule')
            ->where('status', 'active')
            ->where(function($q) use ($companyId, $userId) {
                $q->where(function($q2) use ($companyId, $userId) {
                    $q2->where('company_id', $companyId)->where('user_id', $userId);
                })->orWhere(function($q2) {
                    $q2->where('company_id', 0)->where('user_id', 0);
                });
            })
            ->orderByRaw("CASE WHEN company_id = ? AND user_id = ? THEN 0 ELSE 1 END", [$companyId, $userId])
            ->first();

        if (!$tpl) return [];

        return WorkJobTemplateItem::query()
            ->where('template_id', $tpl->id)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get()
            ->map(fn($row) => [
                'label' => $row->label,
                'schema' => $row->schema_json ?? [],
            ])
            ->toArray();
    }

    public static function validateForCompletion(array $job, array $evidenceTypes, array $requirements): array
    {
        $missing = [];
        foreach ($requirements as $r) {
            $schema = $r['schema'] ?? [];
            if (($schema['when_job_status'] ?? null) === 'completed') {
                $requires = $schema['requires'] ?? [];
                foreach ($requires as $req) {
                    if (!in_array($req, $evidenceTypes, true)) {
                        $missing[] = $req;
                    }
                }
            }
        }
        return array_values(array_unique($missing));
    }
}
