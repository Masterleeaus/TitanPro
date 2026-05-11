<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Console\Commands\TitanZero;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Registry\PluginRegistry;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Chat\AI\AIClientFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * TitanZeroChat — Health Check Command
 *
 * Usage: php artisan tzc:health
 *
 * Checks:
 *  - All plugins booted without failure
 *  - All required DB tables exist
 *  - AI provider API keys are configured
 *  - Feature flags are set in the settings table
 */
class HealthCheck extends Command
{
    protected $signature   = 'tzc:health {--verbose : Show all checks including passing ones}';
    protected $description = 'Run TitanZeroChat health checks (plugins, DB schema, AI providers, feature flags)';

    protected int $passed  = 0;
    protected int $warned  = 0;
    protected int $failed  = 0;

    public function handle(PluginRegistry $registry): int
    {
        $this->line('');
        $this->line('<fg=cyan>TitanZeroChat Health Check</>');
        $this->line(str_repeat('─', 50));

        $this->checkPlugins($registry);
        $this->checkSchema();
        $this->checkAiProviders();
        $this->checkFeatureFlags();

        $this->line('');
        $this->line(str_repeat('─', 50));
        $this->line(
            sprintf(
                'Result: <fg=green>%d passed</> | <fg=yellow>%d warnings</> | <fg=red>%d failed</>',
                $this->passed,
                $this->warned,
                $this->failed
            )
        );
        $this->line('');

        return $this->failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    // ── Checks ────────────────────────────────────────────────────────

    protected function checkPlugins(PluginRegistry $registry): void
    {
        $this->section('Plugins');

        $bootFailed = $registry->failed();

        if (empty($bootFailed)) {
            $this->pass('All ' . count($registry->all()) . ' plugins booted successfully');
        } else {
            foreach ($bootFailed as $id) {
                $this->fail("Plugin [{$id}] failed to boot");
            }
        }

        $disabled = $registry->disabledPlugins();
        foreach ($disabled as $plugin) {
            $this->warn("Plugin [{$plugin->id()}] is DISABLED (feature flag off)");
        }
    }

    protected function checkSchema(): void
    {
        $this->section('Database Schema');

        $required = [
            'user_openai_chat'          => ['is_guest', 'website_url', 'folder_id'],
            'ai_chat_pro_folders'       => ['id', 'created_by', 'name'],
            'user_chat_instructions'    => ['id', 'user_id', 'openai_chat_category_id', 'ip_address', 'instructions'],
            'user_tiptap_contents'      => ['id', 'user_id', 'save_contentable_id', 'save_contentable_type', 'title', 'input', 'output'],
            'share_links'               => ['id', 'url', 'category', 'chat', 'message'],
        ];

        foreach ($required as $table => $columns) {
            if (!Schema::hasTable($table)) {
                $this->fail("Table [{$table}] does not exist — run: php artisan migrate");
                continue;
            }

            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column)) {
                    $this->fail("Column [{$table}.{$column}] missing — run: php artisan migrate");
                } elseif ($this->option('verbose')) {
                    $this->pass("Column [{$table}.{$column}] exists");
                }
            }

            if ($this->option('verbose')) {
                $this->pass("Table [{$table}] exists");
            } else {
                $this->passed++;
            }
        }

        // Optional: pgvector
        if (config('titan_operator.zero-chat.ai.pgvector.enabled')) {
            if (!Schema::hasTable('tzc_embeddings')) {
                $this->warn('pgvector enabled but [tzc_embeddings] table missing — run: php artisan migrate');
            } else {
                $this->pass('[tzc_embeddings] table exists');
            }
        }
    }

    protected function checkAiProviders(): void
    {
        $this->section('AI Providers');

        try {
            $results = AIClientFactory::healthAll();
            foreach ($results as $provider => $result) {
                if ($result['ok']) {
                    $this->pass("Provider [{$provider}] API key configured");
                } else {
                    $this->warn("Provider [{$provider}] — " . ($result['reason'] ?? 'No API key'));
                }
            }
        } catch (\Throwable $e) {
            $this->fail('AI provider health check failed: ' . $e->getMessage());
        }

        $defaultProvider = config('titan_operator.zero-chat.ai.default_provider', 'openai');
        if ($this->option('verbose')) {
            $this->line("  Default provider: {$defaultProvider}");
        }
    }

    protected function checkFeatureFlags(): void
    {
        $this->section('Feature Flags');

        $expected = [
            'ai_chat_pro_suggestions',
            'ai_chat_pro_canvas',
            'ai_chat_pro_image_generation_feature',
            'chatpro_file_chat_allowed',
            'chatpro-temp-chat-allowed',
            'ai_chat_pro_default_screen',
            'ai_chat_display_type',
            'guest_user_daily_message_limit',
        ];

        try {
            foreach ($expected as $key) {
                $exists = DB::table('settings')->where('key', $key)->exists();
                if ($exists) {
                    if ($this->option('verbose')) {
                        $val = DB::table('settings')->where('key', $key)->value('value');
                        $this->pass("Setting [{$key}] = {$val}");
                    } else {
                        $this->passed++;
                    }
                } else {
                    $this->warn("Setting [{$key}] not found — run: php artisan tzc:seed-settings");
                }
            }
        } catch (\Throwable $e) {
            $this->warn('Could not check settings table: ' . $e->getMessage());
        }
    }

    // ── Output helpers ────────────────────────────────────────────────

    protected function section(string $title): void
    {
        $this->line('');
        $this->line("<fg=cyan>{$title}</>");
    }

    protected function pass(string $msg): void
    {
        $this->passed++;
        if ($this->option('verbose')) {
            $this->line("  <fg=green>✓</> {$msg}");
        }
    }

    protected function warn(string $msg): void
    {
        $this->warned++;
        $this->line("  <fg=yellow>⚠</> {$msg}");
    }

    protected function fail(string $msg): void
    {
        $this->failed++;
        $this->line("  <fg=red>✗</> {$msg}");
    }
}
