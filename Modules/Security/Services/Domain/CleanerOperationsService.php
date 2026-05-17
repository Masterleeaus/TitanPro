<?php

namespace Modules\Security\Services\Domain;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Security\Contracts\Services\CleanerOperationsServiceInterface;
use Modules\Security\Entities\Cleaner;
use Modules\Security\Entities\CleanerSite;
use Modules\Security\Entities\CleanerSiteLog;
use Modules\Security\Entities\TrAccessCard;
use Modules\Security\Entities\TrInOutPermit;
use Modules\Security\Entities\WorkPermits;

class CleanerOperationsService implements CleanerOperationsServiceInterface
{
    public function register(array $data): Cleaner
    {
        $data['status'] = $data['status'] ?? config('security_cleaners.default_status', 'pending');
        $data['cleaner_code'] = $data['cleaner_code'] ?? $this->nextCleanerCode();

        if (empty($data['site_id']) && ! empty($data['site_name'])) {
            $data['site_id'] = $this->firstOrCreateSite($data['site_name'], $data['company_id'] ?? null)->id;
        }

        return Cleaner::create($data);
    }

    public function registerSite(array $data): CleanerSite
    {
        return CleanerSite::query()->firstOrCreate(
            [
                'company_id' => $data['company_id'] ?? null,
                'name' => $data['name'],
            ],
            [
                'site_code' => $data['site_code'] ?? $this->nextSiteCode(),
                'address' => $data['address'] ?? null,
                'supervisor_name' => $data['supervisor_name'] ?? null,
                'supervisor_phone' => $data['supervisor_phone'] ?? null,
                'active' => $data['active'] ?? true,
                'meta' => $data['meta'] ?? null,
            ]
        );
    }

    public function approve(Cleaner $cleaner, ?int $userId = null): Cleaner
    {
        $cleaner->forceFill([
            'status' => 'active',
            'approved_by' => $userId,
            'approved_at' => Carbon::now(),
            'rejected_by' => null,
            'rejected_at' => null,
            'suspended_by' => null,
            'suspended_at' => null,
        ])->save();

        return $cleaner->refresh();
    }



    public function decide(Cleaner $cleaner, string $decision, array $data = [], ?int $userId = null): Cleaner
    {
        $now = Carbon::now();
        $reason = $data['reason'] ?? null;
        $meta = array_merge($cleaner->meta ?? [], [
            'last_decision' => [
                'decision' => $decision,
                'reason' => $reason,
                'user_id' => $userId,
                'decided_at' => $now->toIso8601String(),
            ],
        ]);

        $attributes = match ($decision) {
            'approve', 'reactivate' => [
                'status' => 'active',
                'approved_by' => $userId,
                'approved_at' => $now,
                'rejected_by' => null,
                'rejected_at' => null,
                'suspended_by' => null,
                'suspended_at' => null,
                'decision_reason' => $reason,
                'access_expires_at' => $data['expires_at'] ?? $cleaner->access_expires_at,
                'meta' => $meta,
            ],
            'reject' => [
                'status' => 'rejected',
                'rejected_by' => $userId,
                'rejected_at' => $now,
                'decision_reason' => $reason,
                'meta' => $meta,
            ],
            'suspend' => [
                'status' => 'suspended',
                'suspended_by' => $userId,
                'suspended_at' => $now,
                'decision_reason' => $reason,
                'meta' => $meta,
            ],
            default => throw new \InvalidArgumentException('Unsupported cleaner decision.'),
        };

        $cleaner->forceFill($attributes)->save();

        return $cleaner->refresh();
    }

    public function checkIn(Cleaner $cleaner, array $data = [], ?int $userId = null): CleanerSiteLog
    {
        if (! $cleaner->isApproved()) {
            throw new \RuntimeException('Cleaner must be active, approved, and not expired before check-in.');
        }

        $open = CleanerSiteLog::query()
            ->where('cleaner_id', $cleaner->id)
            ->open()
            ->latest('id')
            ->first();

        if ($open) {
            return $open;
        }

        $log = CleanerSiteLog::create([
            'company_id' => $cleaner->company_id ?? null,
            'cleaner_id' => $cleaner->id,
            'site_id' => $data['site_id'] ?? $cleaner->site_id,
            'site_name' => $data['site_name'] ?? optional($cleaner->site)->name ?? $cleaner->site_name,
            'checkpoint' => $data['checkpoint'] ?? null,
            'checked_in_at' => Carbon::now(),
            'checked_in_by' => $userId,
            'status' => 'open',
            'meta' => ['notes' => $data['notes'] ?? null],
        ]);

        $cleaner->forceFill(['last_check_in_at' => $log->checked_in_at])->save();

        return $log;
    }

    public function checkOut(Cleaner $cleaner, array $data = [], ?int $userId = null): CleanerSiteLog
    {
        $log = CleanerSiteLog::query()
            ->where('cleaner_id', $cleaner->id)
            ->open()
            ->latest('id')
            ->firstOrFail();

        $checkedOutAt = Carbon::now();

        $log->forceFill([
            'checked_out_at' => $checkedOutAt,
            'checked_out_by' => $userId,
            'status' => 'closed',
            'duration_minutes' => $log->checked_in_at ? $log->checked_in_at->diffInMinutes($checkedOutAt) : null,
            'meta' => array_merge($log->meta ?? [], ['checkout_notes' => $data['notes'] ?? null]),
        ])->save();

        $cleaner->forceFill(['last_check_out_at' => $log->checked_out_at])->save();

        return $log->refresh();
    }



    public function forceCheckOut(Cleaner $cleaner, array $data = [], ?int $userId = null): CleanerSiteLog
    {
        $log = CleanerSiteLog::query()
            ->where('cleaner_id', $cleaner->id)
            ->open()
            ->latest('id')
            ->firstOrFail();

        $checkedOutAt = Carbon::now();
        $log->forceFill([
            'checked_out_at' => $checkedOutAt,
            'checked_out_by' => $userId,
            'status' => 'closed',
            'forced_checkout' => true,
            'duration_minutes' => $log->checked_in_at ? $log->checked_in_at->diffInMinutes($checkedOutAt) : null,
            'meta' => array_merge($log->meta ?? [], [
                'forced_reason' => $data['reason'] ?? $data['notes'] ?? 'Supervisor force checkout',
            ]),
        ])->save();

        $cleaner->forceFill(['last_check_out_at' => $log->checked_out_at])->save();

        return $log->refresh();
    }

    public function dashboard(): array
    {
        return [
            'cleaners' => [
                'total' => Cleaner::query()->count(),
                'pending' => Cleaner::query()->pending()->count(),
                'active' => Cleaner::query()->active()->count(),
                'onsite' => CleanerSiteLog::query()->open()->distinct('cleaner_id')->count('cleaner_id'),
                'rejected' => Cleaner::query()->where('status', 'rejected')->count(),
                'suspended' => Cleaner::query()->where('status', 'suspended')->count(),
            ],
            'sites' => [
                'total' => CleanerSite::query()->count(),
                'active' => CleanerSite::query()->active()->count(),
            ],
            'operations' => [
                'access_cards' => TrAccessCard::query()->count(),
                'work_permits' => WorkPermits::query()->count(),
                'goods_movements' => TrInOutPermit::query()->count(),
            ],
        ];
    }

    public function activeSites(): array
    {
        return CleanerSite::query()
            ->active()
            ->orderBy('name')
            ->get(['id', 'site_code', 'name', 'address', 'supervisor_name', 'supervisor_phone'])
            ->all();
    }

    protected function firstOrCreateSite(string $name, ?int $companyId = null): CleanerSite
    {
        return $this->registerSite([
            'company_id' => $companyId,
            'name' => $name,
        ]);
    }

    protected function nextCleanerCode(): string
    {
        $prefix = config('security_cleaners.id_prefix', 'CLN');

        return $prefix . '-' . now()->format('ymd') . '-' . Str::upper(Str::random(5));
    }

    protected function nextSiteCode(): string
    {
        return 'SITE-' . now()->format('ymd') . '-' . Str::upper(Str::random(4));
    }
}
