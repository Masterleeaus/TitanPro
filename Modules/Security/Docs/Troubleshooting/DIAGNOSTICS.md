# Security Diagnostics

Run:

```bash
php artisan security:health
php artisan security:repair --dry-run
```

API checks are available at:

```text
GET /api/security/diagnostics
```

The diagnostics pass verifies database tables, API route names, and config registration. It intentionally avoids destructive auto-repair.
