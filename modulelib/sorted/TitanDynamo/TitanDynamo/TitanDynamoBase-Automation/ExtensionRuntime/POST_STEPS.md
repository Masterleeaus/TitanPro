1. Add extension row for slug titan-pulse.
2. Ensure tz_automation_runs has idempotency_key if desired.
3. php artisan optimize:clear
4. php artisan db:seed --class="App\Extensions\TitanPulse\Database\Seeders\TitanPulsePackSeeder"
5. php artisan titan:pulse-run --limit=200


## Pass 3 commands
- php artisan titan:pulse-packs seed
- php artisan titan:pulse-packs list
- php artisan titan:pulse-packs disable MarketingPack --team_id=123
- php artisan titan:pulse-run --limit=200
