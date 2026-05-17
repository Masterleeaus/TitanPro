# Verification Checklist

1. Run `composer dump-autoload` in the host application.
2. Run migrations for the Security module.
3. Run `php artisan security:health`.
4. Visit `/account/security-transfer`, `/account/trinoutpermit`, `/account/work-permits`, and `/account/card-access` with an authorized user.
5. Validate `/api/security/health` with an authenticated API token.
