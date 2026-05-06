# Titan Platform Documentation

This directory contains the canonical architecture and engineering documentation for the Titan platform.
**All agents and developers must consult the relevant docs before implementing features or making architectural decisions.**

## Folder Map

```
docs/
├── 01-PWA/                          System doctrine, reference architecture, PWA model, AI governance,
│                                    module/API contract, node runtime, build roadmap (read first)
├── 02-Signals/                      Signals engine, AI core, automation, workflow, scheduling,
│                                    communications, CMS manifests, sync/offline, security, observability
├── 03-modules/                      Module structure, manifests, routing, permissions, lifecycle,
│                                    UI integration, registry, health checks, tenant scoping
├── 04-AI/                           AI orchestration, memory architecture, model routing, Titan Zero,
│                                    AEGIS core, specialist cores, context packs, evaluation
├── 05-Node:PWA/                     Node architecture, service workers, sync engine, IndexedDB schema,
│                                    push subscriptions, auth/session, edge AI runtime, observability
├── 06-automation/                   Automation engines, trigger evaluation, lifecycle engine,
│                                    outbox/inbox relays, idempotency, dead-letter queues, escalation
├── 07-workflows/                    Workflow definitions, state machines, transitions, guards,
│                                    approvals, templates, metrics, stuck-state detection
├── 08-interfaces/                   Filament admin/user panels, dashboard system, widget architecture,
│                                    navigation shell, voice interface, conversational OS
├── 09-communications/               Omni-bridge, email/SMS/WhatsApp/Telegram/push engines,
│                                    unified inbox, channel permissions, delivery tracking, governance
├── Titan_Blueprints/                Canonical numbered blueprints — start with 00-INDEX.md
├── architecture/                    Engine-level architecture references
│                                    (AI tools, automation, manifest system, signals, workflow)
└── laravel_actual_page_extracts_micro/  Laravel reference PDFs (routing, Eloquent, auth, queues,
                                         testing, service container)
```

## Recommended Reading Order

### For new agents / first-time contributors
1. [`01-PWA/01-system-doctrine.md`](01-PWA/01-system-doctrine.md) — design laws and non-negotiables
2. [`01-PWA/02-reference-architecture.md`](01-PWA/02-reference-architecture.md) — target technical architecture
3. [`Titan_Blueprints/00-INDEX.md`](Titan_Blueprints/00-INDEX.md) — full blueprint index
4. [`Titan_Blueprints/02-PLATFORM-BLUEPRINT.md`](Titan_Blueprints/02-PLATFORM-BLUEPRINT.md) — platform overview
5. [`Titan_Blueprints/05-MODULE-BLUEPRINT.md`](Titan_Blueprints/05-MODULE-BLUEPRINT.md) — module development rules

### Domain-specific entry points
| Task | Start here |
|------|-----------|
| Building a module | `03-modules/module-structure.md`, `Titan_Blueprints/05-MODULE-BLUEPRINT.md` |
| AI / orchestration | `04-AI/orchestration.md`, `02-Signals/Titan_AI_Core_Architecture.md` |
| Signals / events | `02-Signals/Titan_Signals_Engine.md` |
| Automation | `06-automation/automation-engines.md` |
| Workflows / state machines | `07-workflows/workflow-definitions.md` |
| Communications / Omni | `09-communications/Titan_Omni_Bridge_Layer.md` |
| Filament panels | `08-interfaces/filament-admin.md`, `Titan_Blueprints/06-FILAMENT-PLUGIN-BLUEPRINT.md` |
| PWA / Node runtime | `05-Node:PWA/node-architecture.md`, `01-PWA/06-node-device-runtime.md` |
| Security / permissions | `02-Signals/Titan_Security_Permissions_Audit.md`, `Titan_Blueprints/22-SECURITY-PERMISSIONS-AUDIT-BLUEPRINT.md` |
| Testing & deployment | `Titan_Blueprints/18-TESTING-DEPLOYMENT-BLUEPRINT.md` |
| Routing / naming | `Titan_Blueprints/30-ROUTE-NAMING-AND-SURFACE-MATRIX.md` |
| DB naming | `Titan_Blueprints/31-DATABASE-TABLE-MATRIX-AND-NAMING.md` |
| Worked example | `Titan_Blueprints/33-GOLDEN-WORKED-EXAMPLE-BOOKING-MODULE.md` |

## Platform Invariants

These invariants are non-negotiable across all modules, engines, and surfaces:

- **`company_id` is the tenant boundary** — enforced at queries, events, signals, snapshots, and audit logs.
- **Platform engines provide shared runtime; modules own domain data and actions.**
- **Filament is an operator shell** — no business rules live in Filament resources or plugins.
- **AI uses declared tools and manifests only** — never arbitrary internal method calls.
- **All state-changing flows must be auditable, retry-aware, and approval-capable** where risk requires.
- **Modules communicate via signals** — never by calling each other's services directly.
- **Offline/sync shells replay bounded deltas** — not full database mirrors.
