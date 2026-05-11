# Issue 350 — Install TitanDynamo / TitanPulse — signal-driven automation bus

## Files Changed

- `bootstrap/providers.php`
- `app/Providers/AppServiceProvider.php`
- `Modules/TitanZero/Services/ZeroGateway.php`
- `app/Extensions/TitanPulse/Providers/TitanPulseServiceProvider.php`
- `app/Extensions/TitanPulse/Console/Commands/TitanPulseRunCommand.php`
- `app/Extensions/TitanPulse/Services/SignalBus/SignalEmitter.php`
- `app/Extensions/TitanPulse/Automation/Rules/RuleEngine.php`
- `app/Extensions/TitanPulse/Automation/Workers/AutomationRunner.php`
- `app/Extensions/TitanPulse/Automation/Actions/CreateSuggestionAction.php`
- `app/Extensions/TitanPulse/Automation/Actions/QueuePendingAction.php`
- `app/Extensions/TitanPulse/Automation/Actions/RunAnalysisAction.php`
- `app/Extensions/TitanPulse/database/migrations/2026_03_04_000000_pulse_neutral_table_cutover.php`
- `app/Extensions/TitanPulse/database/migrations/2026_03_04_000001_create_tz_automation_rules_table.php`
- `app/Extensions/TitanPulse/database/migrations/2026_03_04_000002_create_tz_ai_suggestions_table.php`
- `app/Extensions/TitanPulse/database/migrations/2026_03_04_000003_create_tz_pending_actions_table.php`
- `app/Extensions/TitanPulse/database/migrations/2026_03_04_000004_create_tz_analyses_table.php`
- `app/Extensions/TitanPulse/database/migrations/2026_03_04_000005_create_tz_automation_runs_table.php`
- `app/Extensions/TitanPulse/database/migrations/2026_05_11_182934_create_tz_signals_table.php`
- `tests/Feature/TitanPulseSignalPipelineTest.php`

## Fixes Applied

- Installed TitanPulse under `app/Extensions/TitanPulse` using the `App\Extensions` namespace and added:
  - signal bus emitter (`SignalEmitter`)
  - automation rule engine and signal runner
  - pending action/suggestion/analysis actions
  - console command (`titan:pulse-run`)
  - service provider loading migrations + command registration
- Registered `App\Extensions\TitanPulse\Providers\TitanPulseServiceProvider` in `bootstrap/providers.php`.
- Added TitanPulse migrations for automation tables and added `tz_signals` table migration.
- Wired TitanZero signal ingestion to TitanPulse by emitting to `tz_signals` from `Modules\TitanZero\Services\ZeroGateway::ingestSignal()`.
- Connected job lifecycle module events by emitting `work.job.status_changed` signals from `AppServiceProvider` on `JobStatusChanged`.
- Added integration feature test for signal emission → rule match → pending action creation via `titan:pulse-run`.

## Next Steps

- Run `php artisan migrate` in an environment with dependencies installed.
- Run `php artisan test --filter=TitanPulseSignalPipelineTest` in an environment with Composer dependencies installed.
- Re-run CI after dependencies/environment are available to confirm full acceptance criteria in workflow checks.
