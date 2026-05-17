# Security Health Checks

Run diagnostics from the host Laravel app:

```bash
php artisan security:health
php artisan security:health --json
```

Expected database tables:
- `tr_in_out_permit`
- `tr_workpermits`
- `tr_workpermit_files`
- `tr_access_card`
- `tr_access_card_items`

A degraded result usually means migrations have not been run or the tenant/company migration flow did not execute for this module.
