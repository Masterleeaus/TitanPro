<?php
namespace Modules\EInvoice\Console;
use Illuminate\Console\Command;
class ProcessLateInvoicesCommand extends Command { protected $signature='titan-money:process-late-invoices'; protected $description='Runs the Titan Money late invoice automation ladder.'; public function handle(): int { $this->info('Late invoice automation is ready. Bind invoice query source in host app scheduler.'); return self::SUCCESS; } }
