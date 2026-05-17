# Production Readiness Checklist

- [ ] `php artisan optimize:clear`
- [ ] `php artisan migrate --pretend` reviewed
- [ ] `php artisan security:install-verify`
- [ ] `php artisan security:cache-warm`
- [ ] `/api/security/health` returns expected status
- [ ] `/api/security/readiness` returns expected status
- [ ] queue worker configured for async jobs
- [ ] storage permissions verified
- [ ] upload MIME policy reviewed
- [ ] audit logs retained according to policy
