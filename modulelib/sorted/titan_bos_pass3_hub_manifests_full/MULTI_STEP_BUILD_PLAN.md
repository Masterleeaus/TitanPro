# Titan BOS Multi-Step Build Plan

This plan assumes the latest full BOS bundle as the base and targets **substantial passes only**.
Each pass should merge or edit **40–50 files minimum** unless a refactor or system-hardening pass makes that impossible.

## Pass 3 — Home + Connect hardening
Target: 45–55 files
- unify Command Centre sections with shared card system
- finish Connect Core omni inbox + campaigns + conversations
- merge sleek spacing + social feed patterns into canonical hub files
- normalize shared runtime asset imports
- clean duplicate/legacy connect/work card names

## Pass 4 — Service Console completion
Target: 40–50 files
- complete Service Wizard shells + service cards
- embed schedule, proposals, quotes, client portal patterns
- integrate TimeEngine views/services into service support layer
- wire service-specific chatbot maps + activity feeds
- normalize service tables, tabs, and support partials

## Pass 5 — Team Portal + Supply Depot foundations
Target: 45–60 files
- create team cards, staffing widgets, availability strips
- create supply cards, stock alerts, purchase/supplier tables
- repurpose trust, docs, and time widgets where relevant
- standardize team/supply support folders and partial naming
- merge role-aware hero/tool rows for both hubs

## Pass 6 — Money Manager + Trust Vault
Target: 40–55 files
- build money collections, payments, overdue, cashflow cards
- build trust evidence, incidents, inspections, overview cards
- merge docs-proof artifacts into trust vault support layer
- align activity feeds and signal severity styles
- create canonical tabbed workspaces for both hubs

## Pass 7 — Academy + Nexus surface layer
Target: 45–55 files
- add Academy dashboard shell + queue/playbook/training cards
- add Nexus runtime entry surfaces across hub dashboards
- standardize assistant panels and signal action strips
- prepare lifecycle-launch card patterns in shared runtime
- document hub-to-core visual mappings for later data wiring

## Pass 8 — Shared runtime + navigation consolidation
Target: 40–50 files
- consolidate titan runtime partials/assets into one shared source
- finalize large menu + slim rail coexistence
- remove duplicated blade fragments now superseded by shared components
- normalize SCSS naming and spacing tokens across all hubs
- produce final hub support inventory and route/include audit

## Pass 9 — Lifecycle launch and table seriousness
Target: 40–60 files
- harden table workspaces with sticky tabs/headers/row actions
- add lifecycle launch placeholders to buttons and quick actions
- standardize drill-in card expansions across hubs
- connect activity strip patterns to every hub
- add support docs and merge notes for dev handoff

## Delivery rule for every pass
- rescan the latest full bundle first
- use `dashboard_support/extracted_usable_code/` as the first source for reusable imports
- preserve existing code whenever possible
- return the full updated bundle, not deltas only


## Pass 3 completed
- Added per-hub manifests, KPI/action/tab/assistant/lifecycle maps
- Added shared hub-system loader files
- Updated all canonical blades to consume hub manifests
- Set groundwork for controller-driven rendering and Nexus lifecycle routing
