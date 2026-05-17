# Real Pass D - Security Hardening

This pass adds executable hardening components instead of scaffold-only files.

## Implemented

- Central input sanitizer for safe filenames and integer coercion.
- Work-permit file upload request validation.
- Upload MIME/extension/size enforcement.
- Upload-specific route throttling.
- API-specific rate limiter registration.
- Sensitive value redaction utility for audit-safe payload handling.
- Configurable upload and rate-limit settings.

## Hardened paths

- `WorkPermitsFileController::store`
- `WorkPermitsFileController::storeMultiple`
- `Routes/web.php` upload endpoints
- `Routes/api.php` module status endpoints
- `Config/config.php`
- `SecurityServiceProvider`

## Remaining for next pass

- Apply request objects to all permit/card create/update flows.
- Add ownership checks around downloads and destructive operations.
- Add policy middleware to every web route.
- Add SQL/static taint scan report for controllers and datatables.
