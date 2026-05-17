<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;
use Modules\TitanGoField\Models\FieldJob;

class ProofOfWork extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanGo';
    protected static ?string $navigationLabel = 'Photos';
    protected static ?string $title = 'Photos';
    protected static ?int $navigationSort = 5;
    protected static ?string $slug = 'proof';
    protected string $view = 'filament.titango.pages.proof-of-work';

    protected function getViewData(): array
    {
        $user = auth()->user();
        $companyId = $user?->company_id ?? $user?->organization_id;
        $jobs = collect();

        if ($user && $companyId && class_exists(FieldJob::class)) {
            $jobs = FieldJob::query()
                ->where('company_id', $companyId)
                ->where(function ($query) use ($user) {
                    $query->whereNull('technician_id')->orWhere('technician_id', $user->id);
                })
                ->whereNotIn('status', [FieldJob::STATUS_CANCELLED])
                ->orderByRaw('scheduled_start IS NULL')
                ->orderBy('scheduled_start')
                ->limit(20)
                ->get(['id', 'reference', 'status', 'description', 'scheduled_start']);
        }

        return [
            'jobs' => $jobs,
            'proofEndpoint' => route('titango.proof.store', absolute: false),
        ];
    }
}
