<?php

namespace Modules\ZeroFussPortal\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\ZeroFussPortal\Actions\AwardLoyaltyPointsAction;
use Modules\ZeroFussPortal\Models\LoyaltyPoint;

class LoyaltyService
{
    public function __construct(private readonly AwardLoyaltyPointsAction $awardLoyaltyPointsAction)
    {
    }

    public function awardForInvoicePayment(int $companyId, int $customerId, int|string $invoiceId, ?int $points = null): LoyaltyPoint
    {
        $resolvedPoints = $points ?? (int) config('zerofussportal.loyalty.default_award_points', 10);

        return $this->awardLoyaltyPointsAction->execute(
            companyId: $companyId,
            customerId: $customerId,
            points: max(0, $resolvedPoints),
            reason: 'Invoice paid',
            sourceType: 'invoice_payment',
            sourceId: $invoiceId,
            metadata: ['trigger' => 'EInvoice.InvoicePaid']
        );
    }

    public function balanceForCustomer(int $companyId, int $customerId): int
    {
        $earned = (int) DB::table('zerofuss_loyalty_points')
            ->where('company_id', $companyId)
            ->where('customer_id', $customerId)
            ->where('direction', 'earn')
            ->sum('points');

        $spent = (int) DB::table('zerofuss_loyalty_points')
            ->where('company_id', $companyId)
            ->where('customer_id', $customerId)
            ->whereIn('direction', ['redeem', 'adjustment'])
            ->sum('points');

        return max(0, $earned - $spent);
    }

    public function bookingHistoryForCustomer(int $companyId, int $customerId): Collection
    {
        if (! Schema::hasTable('bookings')) {
            return collect();
        }

        return DB::table('bookings')
            ->select(['id', 'booking_status', 'service_schedule', 'created_at'])
            ->where('company_id', $companyId)
            ->where('customer_id', (string) $customerId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function invoiceHistoryForCustomer(int $companyId, int $customerId): Collection
    {
        if (! Schema::hasTable('einvoice_invoices')) {
            return collect();
        }

        $query = DB::table('einvoice_invoices')
            ->select(['id', 'status', 'due_date', 'grand_total', 'created_at'])
            ->where('company_id', $companyId);

        if (Schema::hasColumn('einvoice_invoices', 'customer_id')) {
            $query->where('customer_id', $customerId);
        } elseif (Schema::hasColumn('einvoice_invoices', 'client_id')) {
            $query->where('client_id', $customerId);
        } else {
            return collect();
        }

        return $query->orderByDesc('created_at')->get();
    }
}
