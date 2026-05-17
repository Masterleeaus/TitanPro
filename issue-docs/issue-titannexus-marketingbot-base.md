# TitanNexus MarketingBot Base Integration

## Files changed

- app/Providers/Filament/TitanNexusPanelProvider.php
- app/Filament/TitanNexus/Pages/VerticalGrowthEngine.php
- resources/views/filament/titan-nexus/pages/vertical-growth-engine.blade.php
- Modules/TitanNexus/Services/VerticalGrowthEngine.php
- Modules/TitanNexus/Docs/MARKETINGBOT_BASE_INTEGRATION.md
- Modules/TitanNexus/Reference/MarketingBotBase/**

## Fixes applied

- Added Growth Engine page to TitanNexus panel.
- Copied MarketingBot extension as a non-booted reference base inside TitanNexus.
- Mapped MarketingBot contacts, segments, campaigns, inbox, and training features to TitanNexus vertical-growth workflow.
- Added starter vertical packs including Medical Equipment Cleaning.

## Next steps

- Extract MarketingBot campaign/contact models into TitanNexus-native models.
- Add Filament resources for vertical leads, campaign sequences, contracts, and training packs.
- Add migrations only after naming conflicts are resolved.
- Connect TitanHello voice agent for call/follow-up and booking handoff.

## Validation

Run after deploy:

```bash
php artisan optimize:clear
php artisan route:clear
```

Then open `/titannexus` and check the `Growth Engine` navigation item.
