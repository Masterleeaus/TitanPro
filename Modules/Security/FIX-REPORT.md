# Security Combined Module Fix Pass

## Scope
Full rescan and direct repair pass against the combined `Modules/Security` module.

## Fixed
- Removed duplicate PHP class names in merged company-created listeners.
- Registered all merged company-created listeners in one `EventServiceProvider`.
- Renamed duplicate/mismatched seeder classes to match their files.
- Added legacy translation namespace directories for `trinoutpermit`, `trworkpermits`, and `traccesscard` so existing `__('module::file.key')` calls resolve.
- Loaded legacy translation namespaces from `SecurityServiceProvider`.
- Converted string controller route actions to class-array actions.
- Removed stale `module:enable` calls for merged legacy submodules from permission migrations.
- Made permission migration rollback paths null-safe.
- Removed no-op TODO setup migrations that could create migration noise.
- Fixed wrong legacy `Modules\\Units\\Entities\\TrAccessCard` migration import.
- Disabled DomPDF PHP execution in generated PDFs.
- Fixed work-permit validation update bug caused by undefined `$WPata` variable.
- Fixed work-permit validation image field from `validate_img` to `validated_img`.
- Fixed work-permit approval methods to update approval status flags.
- Fixed work-permit PDF filename field from missing `name` to `company_name`.
- Fixed card-access delete redirect from unrelated `accountings.index` to `card-access.index`.
- Fixed work-permit file relation foreign key and owner permission check.

## Verification
- `php -l` passed across all PHP files.
- Duplicate class-name scan passed.
- Active old module PHP namespace scan passed.
