# Cleaning Payroll MVP

This pass adds a cleaning-business payroll layer without replacing the core Payroll engine.

## Supported MVP rules

- Site-based cleaning shifts
- Weekday, night, Saturday, Sunday, and public holiday loading hooks
- Travel, site, and equipment allowance hooks
- Contractor payout mode with optional withholding
- Roster variance warnings for missing sites, unapproved shifts, and long shifts without breaks
- API preview endpoints for cleaner payroll and roster variance

## API

`POST /api/payroll/cleaning/preview`

```json
{
  "company_id": 1,
  "user_id": 9,
  "is_contractor": false,
  "shifts": [
    {
      "site_code": "CLIENT-A",
      "started_at": "2026-05-13T20:00:00+10:00",
      "ended_at": "2026-05-13T23:00:00+10:00",
      "hourly_rate": 32,
      "requires_travel_allowance": true
    }
  ]
}
```

`POST /api/payroll/cleaning/variance`

Returns warnings before a payroll run is approved.
