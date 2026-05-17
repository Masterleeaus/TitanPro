# Filament Plugin Sprawl Audit (Baseline)

## Scope
- Source: `/home/runner/work/TitanPro/TitanPro/composer.json`
- Focus: reducing upgrade risk and package overlap before future major framework upgrades.

## Findings
- The project currently carries a high number of Filament plugins across navigation, themes, dashboards, media/settings, and UX helpers.
- Some packages use broad ecosystem overlap (multiple UI/UX/navigation enhancers) that can raise upgrade friction when Filament or Laravel major versions change.
- Wildcard constraints were present on several Filament-adjacent packages and have been replaced with explicit semver ranges to reduce surprise updates.

## Current risk controls added
- Explicit constraints now applied for:
  - `bezhansalleh/filament-panel-switch`
  - `jeffersongoncalves/filament-topbar`
  - `novadaemon/filament-combobox`
  - `osamaatef/filament-drilldown-sidebar`
- Automated grouped dependency upgrades are now enabled via Dependabot (`.github/dependabot.yml`).

## Next removal candidates (manual validation required)
- Navigation overlap candidates:
  - `bezhansalleh/filament-panel-switch`
  - `osamaatef/filament-drilldown-sidebar`
  - `jeffersongoncalves/filament-topbar`
- Theme overlap candidates:
  - `openplain/filament-shadcn-theme`
  - `osamanagi/filament-abyss-theme`
  - `alizharb/filament-themes-manager`
- Dashboard/widget overlap candidates:
  - `lara-zeus/dynamic-dashboard`
  - `eightynine/filament-advanced-widgets`

## Removal policy
- Remove only packages with no runtime usage in panel providers/resources/views.
- For each removal candidate:
  1. locate provider/plugin registration references,
  2. remove package and registration,
  3. run panel smoke tests and production-check workflow,
  4. keep only one package per capability class when possible.
