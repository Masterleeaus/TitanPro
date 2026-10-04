# Portfolio engineering guide

This guide records what is visible in the TitanPro default branch. It is intentionally narrower than a product pitch: TitanPro is a broad Laravel business-operations workspace, and only some modules are AI systems.

## Problem and architecture

TitanPro brings field service, CRM, finance, workforce, communications, and customer-facing surfaces into one modular Laravel application. The engineering challenge is integrating many domain modules without turning the Filament shell into a second business-logic layer.

The checked-in structure is:

- `app/` — application services, tenancy, policies, HTTP, jobs, Filament, and shared Laravel concerns.
- `Modules/` — nWidart-style domain modules, including `TitanCore`, `TitanZero`, `TitanNexus`, CRM, booking, cleaning, dispatch, payroll, quoting, and supply-chain modules.
- `resources/`, `Web/`, `Mobile/`, and `PWA/` — operator, web, mobile, and progressive-web surfaces.
- `database/` and `routes/` — persistence and application boundaries.
- `tests/` and module-local test trees — PHPUnit/Pest/Vitest/Dusk-oriented verification.
- `docs/01-PWA` through `docs/09-communications` — architecture and domain documentation; `docs/04-AI` contains AI-specific design notes.

The root `CLAUDE.md` records the important invariants: `company_id` is the tenant boundary, modules own domain data/actions, Filament is an operator shell, AI uses declared tools/manifests, and state-changing flows should be auditable and approval-capable where risk requires it.

## AI capabilities that are actually represented in code

AI is concentrated in named modules rather than being attributed to every business feature:

- `Modules/TitanCore/AI/AIOrchestratorPipeline.php` orders guardrail evaluation, retrieval, tool execution, and citation resolution. `ToolExecutor.php`, `ToolPermissionGate.php`, and `ManifestValidator.php` provide the adjacent tool/policy boundaries.
- `Modules/TitanCore/Services/ProviderFailoverChain.php` tries ordered chat or embedding providers and fails over for missing status or configured 429/5xx responses; it is not evidence of universal provider routing or production resilience.
- `Modules/TitanZero/` contains manifest references for guardrails, retrieval policy, citations, and AI tools. Its `Evaluation/AgentEvaluator.php` records task completion, hallucination flag, tool accuracy, latency, and a weighted composite score.
- `Modules/TitanNexus/` contains a marketing/lead-generation and payment-assist module. Its README describes agent tools, prompts, memory, retrieval, guardrails, telemetry, and approval gates for external outreach and payment nudges.
- Ordinary CRM, booking, accounting, UI, and infrastructure modules remain ordinary application engineering; this guide does not re-label them as AI.

The trade-off is modularity: manifests and contracts make ownership visible, but the repository's breadth increases dependency, install, and cross-module verification cost.

## Quickstart

The checked-in Composer scripts define the supported setup path:

```bash
composer run setup
composer run dev
```

For focused checks:

```bash
composer run test
npm run build
npm run format:check
npm test
```

`composer run setup` creates local environment/database state and installs dependencies. These commands were inspected from the manifests and `CLAUDE.md`, not executed in this portfolio pass.

## Evidence and tests

The source tree contains focused tests for the AI boundaries, including:

- `Modules/TitanCore/Tests/Unit/ToolExecutorTest.php`
- `Modules/TitanCore/Tests/Unit/ManifestValidatorTest.php`
- `Modules/TitanCore/Tests/Unit/ProviderAdaptersTest.php`
- `Modules/TitanCore/Tests/Feature/ValidateManifestsCommandTest.php`
- `Modules/TitanZero/Tests/Unit/AgentEvaluatorTest.php`
- `Modules/TitanZero/Tests/Unit/BlockedTermGuardrailTest.php`
- `Modules/TitanZero/Tests/Unit/CrossTenantGuardTest.php`
- `Modules/TitanZero/Tests/Feature/AIChatProTest.php` and `EvaluationScoringTest.php`

Their presence demonstrates intended verification scope; no test pass is claimed here because the repository was not mounted for execution. The default branch also contains a checked-in `LICENSE` and a number of archive/zip artifacts. The root README preserves the recorded provenance warning: the MIT file names Michael Stoffer as copyright holder, so attribution must remain intact.

## Limitations and cleanup decisions

- The relationship between TitanPro and Titan Zero Field Service Workforce is not established by the inspected default branch; do not present them as one canonical implementation without lineage work.
- The repository contains large ZIPs, extracted trees, and historical reports. They were retained because provenance, package identity, and references were not proven safe to delete.
- AI manifests and module declarations should continue to be checked against implementation before calling a capability complete.
- No build, test, release, or production-readiness claim is made by this guide.
