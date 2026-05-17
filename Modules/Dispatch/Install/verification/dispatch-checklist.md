# Dispatch Verification Checklist

- [ ] `php artisan dispatch:install --seed` succeeds.
- [ ] `php artisan route:list | grep dispatch` shows API routes.
- [ ] Shift, Technician Profile, Skill, Zone, Location, Route, Route Stop, and Status Log resources load in Filament.
- [ ] Scheduling a work order creates/updates `wo_service_appointments` and `assign_shifts`.
- [ ] Status changes create `dispatch_status_logs`.
- [ ] Route build creates `dispatch_routes` and `dispatch_route_stops`.
