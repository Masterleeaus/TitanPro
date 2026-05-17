<?php

namespace Modules\Payroll\Console\Commands;

use Illuminate\Console\Command;
use Modules\Payroll\Contracts\Services\PayrollTaxServiceContract;

class PayrollDiagnosticsCommand extends Command
{
    protected $signature = 'payroll:diagnostics {--country=AU}';
    protected $description = 'Run Payroll module diagnostics for configuration, tax bands, and storage readiness.';

    public function handle(PayrollTaxServiceContract $tax): int
    {
        $country = strtoupper((string) $this->option('country'));
        $this->info('Payroll diagnostics');
        $this->line('Country: '.$country);
        $this->line('Tax sample: '.json_encode($tax->estimate(75000, $country)));
        $this->line('Compliance config loaded: '.(config('payroll.compliance') ? 'yes' : 'no'));
        return self::SUCCESS;
    }
}
