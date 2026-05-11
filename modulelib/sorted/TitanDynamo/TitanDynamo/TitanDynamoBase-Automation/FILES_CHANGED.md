## v2.0 (Titan Pulse kernel rename + neutral tables)

- Renamed extension TitanWorkAutomation → TitanPulse
- Renamed command titan:automation-run → titan:pulse-run
- Introduced neutral tz_* Pulse tables and safe cutover migration
- Updated namespaces, provider, seeders, docs

---

# FILES_CHANGED.md — TitanPulse v1.2 (Quality & Retention Rule Pack)

## Modified
- Automation/Rules/RuleEngine.php
  - Added payload enrichment helpers (evidence_quality_low, duration_ratio)
  - Added `contains_any` condition operator
- Automation/Workers/AutomationRunner.php
  - Added weekly sweep support (`schedule.weekly`)
  - Added Absent Client Pattern subject query
- database/seeders/TitanPulseSeeder.php
  - Added Rules 11–20 (Quality & Retention pack)
- README.md
  - Documented Quality & Retention pack
- CHANGELOG.md
- POST_INSTALL.txt
- extension.json (version bump)
