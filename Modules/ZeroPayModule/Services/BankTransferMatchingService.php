<?php

namespace Modules\ZeroPayModule\Services;

use Carbon\Carbon;
use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;
use Modules\ZeroPayModule\ValueObjects\MatchResult;

class BankTransferMatchingService
{
    private const CUSTOMER_MATCH_THRESHOLD = 0.8;

    private const AMOUNT_TOLERANCE = 0.01;

    private const DEPOSIT_REFERENCE_PREFIX = 'bank_deposit_';

    public function match(ZeroPayBankDeposit $deposit): MatchResult
    {
        $candidates = $this->findCandidateSessions($deposit);
        $best = null;
        $bestCriteria = $this->emptyCriteria();
        $bestConfidence = 0.0;

        foreach ($candidates as $session) {
            $criteria = $this->evaluateCriteria($deposit, $session);
            $criteriaCount = count($criteria);
            $confidence = $criteriaCount > 0
                ? (float) (array_sum(array_map(static fn (bool $ok): int => $ok ? 1 : 0, $criteria)) / $criteriaCount)
                : 0.0;

            if ($confidence > $bestConfidence) {
                $best = $session;
                $bestCriteria = $criteria;
                $bestConfidence = $confidence;
            }

            if ($confidence >= 1.0) {
                return $this->applyMatch($deposit, $session, $criteria, 1.0);
            }
        }

        $this->markPendingReview($deposit, $bestConfidence);

        return new MatchResult(
            matched: false,
            session: $best,
            confidence: $bestConfidence,
            matchedCriteria: $bestCriteria,
            status: 'needs_review'
        );
    }

    public function processIncomingDeposit(array $rawDepositData): ZeroPayBankDeposit
    {
        $companyId = (int) ($rawDepositData['company_id'] ?? 0);
        $amount = round((float) ($rawDepositData['amount'] ?? 0), 2);
        $reference = trim((string) ($rawDepositData['reference'] ?? $rawDepositData['remittance_reference'] ?? ''));
        $depositorAccount = $rawDepositData['depositor_account'] ?? $rawDepositData['account'] ?? null;
        $depositedAt = isset($rawDepositData['deposited_at']) ? Carbon::parse($rawDepositData['deposited_at']) : null;

        $query = ZeroPayBankDeposit::query()
            ->where('company_id', $companyId)
            ->where('amount', $amount);

        if ($reference !== '') {
            $query->where('reference', $reference);
        }

        if ($depositorAccount !== null && $this->hasColumn('zeropay_bank_deposits', 'depositor_account')) {
            $query->where('depositor_account', $depositorAccount);
        }

        if ($depositedAt !== null && $this->hasColumn('zeropay_bank_deposits', 'deposited_at')) {
            $query->where('deposited_at', $depositedAt);
        }

        $existing = $query->first();
        if ($existing !== null) {
            return $existing;
        }

        $payload = [
            'company_id' => $companyId,
            'reference' => $reference !== '' ? $reference : null,
            'amount' => $amount,
            'currency' => strtoupper((string) ($rawDepositData['currency'] ?? 'AUD')),
            'depositor_name' => $rawDepositData['depositor_name'] ?? $rawDepositData['sender_name'] ?? null,
            'depositor_account' => $depositorAccount,
            'deposited_at' => $depositedAt,
            'status' => 'pending_review',
        ];

        if ($this->hasColumn('zeropay_bank_deposits', 'raw_data')) {
            $payload['raw_data'] = $rawDepositData;
        } elseif ($this->hasColumn('zeropay_bank_deposits', 'meta')) {
            $payload['meta'] = $rawDepositData;
        }

        return ZeroPayBankDeposit::create($this->filterColumns('zeropay_bank_deposits', $payload));
    }

    public function confirmMatch(int $depositId, int $sessionId): void
    {
        $deposit = ZeroPayBankDeposit::query()->findOrFail($depositId);
        $session = ZeroPaySession::query()->findOrFail($sessionId);

        if ((int) $deposit->company_id !== (int) $session->company_id) {
            throw new \InvalidArgumentException('Deposit and session must belong to the same company.');
        }

        $criteria = $this->evaluateCriteria($deposit, $session);
        $this->applyMatch($deposit, $session, $criteria, 1.0);
    }

    public function rejectMatch(int $depositId, string $reason): void
    {
        $deposit = ZeroPayBankDeposit::query()->findOrFail($depositId);

        $update = ['status' => 'rejected'];
        if ($this->hasColumn('zeropay_bank_deposits', 'raw_data')) {
            $rawData = (array) ($deposit->raw_data ?? []);
            $rawData['rejection_reason'] = $reason;
            $update['raw_data'] = $rawData;
        } elseif ($this->hasColumn('zeropay_bank_deposits', 'meta')) {
            $meta = (array) ($deposit->meta ?? []);
            $meta['rejection_reason'] = $reason;
            $update['meta'] = $meta;
        }

        $deposit->update($this->filterColumns('zeropay_bank_deposits', $update));
    }

    private function applyMatch(
        ZeroPayBankDeposit $deposit,
        ZeroPaySession $session,
        array $criteria,
        float $confidence
    ): MatchResult {
        return ZeroPayBankDeposit::query()->getConnection()->transaction(function () use ($deposit, $session, $criteria, $confidence): MatchResult {
            $transactionQuery = ZeroPayTransaction::query()
                ->where('company_id', $session->company_id)
                ->where('session_id', $session->id)
                ->where('gateway', 'bank_transfer');

            if (! empty($deposit->reference)) {
                $transactionQuery->where('gateway_reference', $deposit->reference);
            }

            $transaction = $transactionQuery->first();

            if ($transaction === null) {
                $transaction = ZeroPayTransaction::create($this->filterColumns('zeropay_transactions', [
                    'company_id' => $session->company_id,
                    'session_id' => $session->id,
                    'user_id' => $session->user_id,
                    'gateway' => 'bank_transfer',
                    'gateway_reference' => $deposit->reference ?: $this->fallbackGatewayReference($deposit),
                    'amount' => $deposit->amount,
                    'currency' => $deposit->currency ?: $session->currency ?: 'AUD',
                    'status' => 'completed',
                    'fee' => 0,
                    'net_amount' => $deposit->amount,
                    'meta' => ['source' => 'bank_transfer_matching'],
                ]));
            } else {
                $transaction->update(['status' => 'completed']);
            }

            $session->update($this->filterColumns('zeropay_sessions', [
                'status' => ZeroPaySession::STATUS_COMPLETED,
                'completed_at' => Carbon::now(),
            ]));

            $depositUpdate = [
                'status' => 'matched',
                'match_score' => (int) round($confidence * 100),
                'match_method' => 'all_criteria',
            ];

            if ($this->hasColumn('zeropay_bank_deposits', 'transaction_id')) {
                $depositUpdate['transaction_id'] = $transaction->id;
            } elseif ($this->hasColumn('zeropay_bank_deposits', 'matched_transaction_id')) {
                $depositUpdate['matched_transaction_id'] = $transaction->id;
            }

            $deposit->update($this->filterColumns('zeropay_bank_deposits', $depositUpdate));

            return new MatchResult(
                matched: true,
                session: $session->fresh(),
                confidence: $confidence,
                matchedCriteria: $criteria,
                status: 'auto_matched'
            );
        });
    }

    private function findCandidateSessions(ZeroPayBankDeposit $deposit)
    {
        $query = ZeroPaySession::query()
            ->where('company_id', $deposit->company_id);

        if ($deposit->deposited_at !== null) {
            $query->whereBetween('created_at', [
                $deposit->deposited_at->copy()->subHours(72),
                $deposit->deposited_at->copy()->addHours(72),
            ]);
        }

        if ($deposit->amount !== null) {
            $query->whereBetween('amount', [
                (float) $deposit->amount - self::AMOUNT_TOLERANCE,
                (float) $deposit->amount + self::AMOUNT_TOLERANCE,
            ]);
        }

        if (! empty($deposit->reference)) {
            $query->where(function ($nested) use ($deposit): void {
                if ($this->hasColumn('zeropay_sessions', 'reference')) {
                    $nested->orWhere('reference', $deposit->reference);
                }
                if ($this->hasColumn('zeropay_sessions', 'session_token')) {
                    $nested->orWhere('session_token', $deposit->reference);
                }
            });
        }

        return $query->orderBy('created_at', 'desc')->limit(20)->get();
    }

    private function evaluateCriteria(ZeroPayBankDeposit $deposit, ZeroPaySession $session): array
    {
        $sessionReference = $this->sessionReference($session);
        $depositReference = trim((string) $deposit->reference);
        $referenceMatch = $depositReference !== '' && $sessionReference !== ''
            && mb_strtolower($depositReference) === mb_strtolower($sessionReference);

        $amountMatch = $deposit->amount !== null
            && $session->amount !== null
            && abs((float) $deposit->amount - (float) $session->amount) <= self::AMOUNT_TOLERANCE;

        $sessionOwnerName = $this->sessionOwnerName($session);
        $depositName = trim((string) $deposit->depositor_name);
        $customerMatch = $this->fuzzyNameMatch($depositName, $sessionOwnerName);

        $timestampMatch = false;
        if ($deposit->deposited_at !== null && $session->created_at !== null) {
            $seconds = abs($deposit->deposited_at->diffInSeconds($session->created_at));
            $timestampMatch = $seconds <= (72 * 60 * 60);
        }

        return [
            'reference' => $referenceMatch,
            'amount' => $amountMatch,
            'customer' => $customerMatch,
            'timestamp' => $timestampMatch,
        ];
    }

    private function fuzzyNameMatch(string $left, string $right): bool
    {
        $normalize = static function (string $name): string {
            $lowercase = mb_strtolower($name);
            $stripped = preg_replace('/[^a-z0-9 ]/i', ' ', $lowercase) ?? '';
            $singleSpaced = preg_replace('/\s+/', ' ', $stripped) ?? '';

            return trim($singleSpaced);
        };

        $left = $normalize($left);
        $right = $normalize($right);

        if ($left === '' || $right === '') {
            return false;
        }

        if ($left === $right) {
            return true;
        }

        similar_text($left, $right, $percent);

        return ($percent / 100) >= self::CUSTOMER_MATCH_THRESHOLD;
    }

    private function sessionOwnerName(ZeroPaySession $session): string
    {
        $meta = (array) ($session->meta ?? []);

        return (string) ($session->merchant_name
            ?? $session->getAttribute('owner_name')
            ?? $session->getAttribute('name')
            ?? ($meta['merchant_name'] ?? null)
            ?? ($meta['owner_name'] ?? null)
            ?? ($meta['customer_name'] ?? null)
            ?? '');
    }

    private function sessionReference(ZeroPaySession $session): string
    {
        if ($this->hasColumn('zeropay_sessions', 'reference') && ! empty($session->reference)) {
            return (string) $session->reference;
        }

        return (string) ($session->session_token ?? '');
    }

    private function fallbackGatewayReference(ZeroPayBankDeposit $deposit): string
    {
        return self::DEPOSIT_REFERENCE_PREFIX.$deposit->id;
    }

    private function markPendingReview(ZeroPayBankDeposit $deposit, float $confidence): void
    {
        $update = [
            'status' => 'pending_review',
            'match_score' => (int) round($confidence * 100),
        ];

        $deposit->update($this->filterColumns('zeropay_bank_deposits', $update));
    }

    private function emptyCriteria(): array
    {
        return [
            'reference' => false,
            'amount' => false,
            'customer' => false,
            'timestamp' => false,
        ];
    }

    private function filterColumns(string $table, array $payload): array
    {
        $filtered = [];

        foreach ($payload as $column => $value) {
            if ($this->hasColumn($table, $column)) {
                $filtered[$column] = $value;
            }
        }

        return $filtered;
    }

    private function hasColumn(string $table, string $column): bool
    {
        static $cache = [];
        $key = $table.'.'.$column;

        if (! array_key_exists($key, $cache)) {
            $cache[$key] = ZeroPayBankDeposit::query()->getConnection()->getSchemaBuilder()->hasColumn($table, $column);
        }

        return $cache[$key];
    }
}
