
<?php

declare(strict_types=1);

namespace App\Extensions\TitanPulse\System\Console\Commands;

use Illuminate\Console\Command;

class PulseRuntimeInspectCommand extends Command
{
    protected $signature = 'titan:pulse-runtime:inspect';
    protected $description = 'Show imported Pulse runtime layers and source origins';

    public function handle(): int
    {
        $this->line('Titan Pulse Runtime');
        $this->line('- PulseKernel: imported from Workflow module');
        $this->line('- PulseEngine: imported from AiSocialMedia');
        $this->line('- Signal creation code: not included');
        return self::SUCCESS;
    }
}
