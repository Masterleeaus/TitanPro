<?php

namespace Modules\Accountings\Tools;

use Modules\Accountings\Actions\LookupLedgerAction;

class LookupLedgerTool
{
    public function __construct(protected LookupLedgerAction $action) {}

    public function name(): string
    {
        return 'accounting.lookup_ledger';
    }

    public function description(): string
    {
        return 'Reads tenant-scoped ledger entries and balances.';
    }

    public function __invoke(array $filters = []): array
    {
        return $this->action->execute($filters)->toArray();
    }
}
