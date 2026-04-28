# CRMCore Full-Fat Filament v4 Canonical Module

This build preserves the full original CRMCore module while also adding a safe Filament v4 bridge layer for the upgraded site.

## What was fixed

The previous ZIP was too small because it only included a thin bridge. This build includes:

- Full original CRMCore source preserved under `_original_source/`
- Original CRMCore migrations preserved under `_legacy_original_migrations/`
- Canonical Titan module tree populated
- Filament v4 plugin/resources/widgets/pages
- Safe active migration that avoids collisions with the upgraded Worksuite tables
- Bridge layer for existing site models:
  - `App\Models\ClientDetails`
  - `App\Models\Lead`
  - `App\Models\Deal`
  - `App\Models\Project`
  - `App\Models\Product`

## Why original migrations are legacy-only

The upgraded site already owns tables like `leads`, `deals`, `companies`, and `lead_sources`.
Running the original CRMCore migrations directly would collide with those tables.
They are preserved for migration/reference under `_legacy_original_migrations/`.

## Active owned table

CRMCore actively owns only:

- `crmcore_activity_logs`

## Upload

Extract this ZIP into:

```text
/home/saassmar/domains/titanzero.pro/public_html
```

## Commands

```bash
cd /home/saassmar/domains/titanzero.pro/public_html && unzip -o crmcore_fullfat_filament_v4_delta.zip
cd /home/saassmar/domains/titanzero.pro/public_html && composer dump-autoload
cd /home/saassmar/domains/titanzero.pro/public_html && /usr/local/php84/bin/php artisan migrate
cd /home/saassmar/domains/titanzero.pro/public_html && /usr/local/php84/bin/php artisan optimize:clear
```
