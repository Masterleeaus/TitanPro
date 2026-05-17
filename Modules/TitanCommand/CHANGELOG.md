# Changelog

## 1.1.0 - 2026-05-12

- Refactored `TitanCommandServiceProvider` to a structured bootstrap pattern (`registerConfig`, `registerTranslations`, `registerViews`).
- Added defensive checks before loading migrations, routes, views, and translations.
- Added module lifecycle metadata files: `version.txt` and `README.md`.
- Bumped module version in `module.json` from `1.0.0` to `1.1.0`.
