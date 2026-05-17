<?php

namespace App\Extensions\TitanCommand\System\Console\Commands;

use App\Extensions\TitanCommand\System\Models\TitanCommand;
use App\Extensions\TitanCommand\System\Services\Analysis\AnalysisRecorder;
use App\Extensions\TitanCommand\System\Services\Analysis\OpenAiAnalysisClient;
use App\Helpers\Classes\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

abstract class AbstractAgentAnalysisCommand extends Command
{
    public function __construct(
        protected OpenAiAnalysisClient $analysisClient,
        protected AnalysisRecorder $analysisRecorder
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        if (Helper::appIsDemo()) {
            return 1;
        }

        $analysisType = $this->analysisType();
        $agentOption = $this->option('agent');

        Log::info("titancommand:{$analysisType} started", [
            'agent_option' => $agentOption,
        ]);

        $agents = $this->resolveAgents();

        if ($agents->isEmpty()) {
            $this->info('No matching agents found.');
            Log::info("titancommand:{$analysisType} finished", [
                'status'       => 'no_agents',
                'agents_count' => 0,
            ]);

            return self::SUCCESS;
        }

        $processed = 0;
        $storedAnalyses = 0;

        foreach ($agents as $agent) {
            if (! $this->shouldRunForAgent($agent)) {
                continue;
            }

            $messages = $this->buildMessages($agent);

            if (empty($messages)) {
                Log::info('Skipping analysis due to empty context', [
                    'analysis_type' => $this->analysisType(),
                    'agent_id'      => $agent->id,
                ]);

                continue;
            }

            $processed++;
            $this->info(sprintf('Generating %s for agent #%d', $this->analysisType(), $agent->id));

            $result = $this->analysisClient->generateReport($messages, $this->temperature(), $this->maxTokens());

            if (! $result['success']) {
                Log::warning('Analysis generation failed', [
                    'analysis_type' => $this->analysisType(),
                    'agent_id'      => $agent->id,
                    'error'         => $result['error'] ?? 'unknown',
                ]);

                continue;
            }

            /** @var \App\Extensions\TitanCommand\System\Models\TitanCommandAnalysis $analysis */
            $analysis = $this->analysisRecorder->recordForAgent(
                $this->analysisType(),
                $agent,
                $result['content']
            );

            $this->info(sprintf('Stored analysis #%d for agent #%d', $analysis->id, $agent->id));
            $storedAnalyses++;
            Log::info("titancommand:{$analysisType} analysis stored", [
                'agent_id'    => $agent->id,
                'analysis_id' => $analysis->id,
            ]);
        }

        Log::info("titancommand:{$analysisType} finished", [
            'agents_count'     => $agents->count(),
            'agents_processed' => $processed,
            'analyses_stored'  => $storedAnalyses,
        ]);

        return self::SUCCESS;
    }

    abstract protected function analysisType(): string;

    /**
     * @return array<int, array{role: string, content: string}>
     */
    abstract protected function buildMessages(TitanCommand $agent): array;

    protected function shouldRunForAgent(TitanCommand $agent): bool
    {
        return $agent->user !== null;
    }

    protected function temperature(): float
    {
        return 0.4;
    }

    protected function maxTokens(): int
    {
        return 900;
    }

    protected function resolveAgents(): Collection
    {
        $query = TitanCommand::query()->active()->with('user');

        $agentId = $this->option('agent');

        if ($agentId) {
            $query->whereKey($agentId);
        }

        return $query->get();
    }

    protected function encodeContext(array $context): string
    {
        $json = json_encode($context, JSON_THROW_ON_ERROR|JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

        return $json === false ? '' : $json;
    }

    protected function responseLanguage(TitanCommand $agent): string
    {
        return $agent->language ?: config('app.locale', 'en');
    }
}
