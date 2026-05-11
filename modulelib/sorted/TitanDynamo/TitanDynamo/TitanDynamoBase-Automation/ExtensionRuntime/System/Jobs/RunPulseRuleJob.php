<?php

declare(strict_types=1);

namespace App\Extensions\TitanPulse\System\Jobs;

use App\Extensions\TitanPulse\System\Models\AutomationRule;
use App\Extensions\TitanPulse\System\Models\Signal;
use App\Extensions\TitanPulse\System\Services\PulseRuleEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunPulseRuleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $ruleId,
        public ?int $signalId = null,
        public array $context = [],
    ) {
    }

    public function handle(PulseRuleEngine $engine): void
    {
        $rule = AutomationRule::query()->find($this->ruleId);
        if (! $rule) {
            return;
        }

        $signal = $this->signalId ? Signal::query()->find($this->signalId) : null;
        $engine->evaluateRule($rule, $signal, $this->context);
    }
}
