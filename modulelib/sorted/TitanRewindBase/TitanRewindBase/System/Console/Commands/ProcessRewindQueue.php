<?php

namespace App\Extensions\TitanRewind\System\Console\Commands;

use Illuminate\Console\Command;
use App\Extensions\TitanRewind\System\Services\RewindFixService;

class ProcessRewindQueue extends Command
{
    protected $signature = 'titanrewind:process {--limit=50 : Max fixes to process}';
    protected $description = 'Process TitanRewind queued fixes (apply those confirmed and ready).';

    public function handle(RewindFixService $fixService): int
    {
        $limit = (int)$this->option('limit');
        $processed = $fixService->processQueue($limit);
        $this->info("TitanRewind: processed {$processed} queued fix(es).");
        return self::SUCCESS;
    }
}
