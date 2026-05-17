<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroFussPortal\Actions\CreateReferralAction;
use Modules\ZeroFussPortal\Actions\SubmitFeedbackAction;
use Modules\ZeroFussPortal\Services\LoyaltyService;
use Modules\ZeroFussPortal\Services\ReferralService;

Route::prefix(config('zerofussportal.routes.web_prefix', 'zerofuss/portal'))
    ->middleware(['web', 'auth:customer'])
    ->group(function (): void {
        Route::get('/dashboard', function (LoyaltyService $loyaltyService) {
            $user = auth('customer')->user();
            $companyId = (int) ($user->company_id ?? $user->organization_id ?? 0);
            $customerId = (int) $user->getAuthIdentifier();

            return response()->json([
                'loyalty_points_balance' => $loyaltyService->balanceForCustomer($companyId, $customerId),
                'bookings' => $loyaltyService->bookingHistoryForCustomer($companyId, $customerId),
                'invoices' => $loyaltyService->invoiceHistoryForCustomer($companyId, $customerId),
            ]);
        })->name('zerofussportal.dashboard');

        Route::post('/feedback', function (SubmitFeedbackAction $action) {
            $user = auth('customer')->user();

            $feedback = $action->execute(
                companyId: (int) ($user->company_id ?? $user->organization_id ?? 0),
                customerId: (int) $user->getAuthIdentifier(),
                message: (string) request()->string('message'),
                rating: request()->integer('rating'),
                metadata: request()->array('metadata')
            );

            return response()->json(['data' => $feedback], 201);
        })->name('zerofussportal.feedback.submit');

        Route::post('/referrals', function (CreateReferralAction $action) {
            $user = auth('customer')->user();

            $referral = $action->execute(
                companyId: (int) ($user->company_id ?? $user->organization_id ?? 0),
                customerId: (int) $user->getAuthIdentifier(),
                referredEmail: (string) request()->string('referred_email'),
                referredName: request()->string('referred_name')->toString() ?: null,
                metadata: request()->array('metadata')
            );

            return response()->json(['data' => $referral], 201);
        })->name('zerofussportal.referrals.create');

        Route::get('/referrals', function (ReferralService $service) {
            $user = auth('customer')->user();
            $companyId = (int) ($user->company_id ?? $user->organization_id ?? 0);
            $customerId = (int) $user->getAuthIdentifier();

            return response()->json([
                'data' => $service->listForCustomer($companyId, $customerId),
            ]);
        })->name('zerofussportal.referrals.index');
    });
