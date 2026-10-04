![TitanPro Business Operations Console — LARAVEL + VUE WORKSPACE](docs/images/portfolio-banner.svg)

<div align="center">

# TitanPro

**Business Operations Console**

</div>

TitanPro brings customer, field-service and back-office workflows into one modular operations workspace. Built with Laravel, Filament and Vue, it combines role-specific interfaces with governed AI orchestration, provider failover and reviewable tool execution. It is designed for operations teams that need more than a collection of disconnected admin screens.

## What teams can do

- **Run the operation:** CRM, booking, dispatch, cleaning jobs, quoting, payroll, accounting, supply chain, payments, messaging, and customer-portal workflows live in one modular application.
- **Serve different roles:** Filament operator screens, Inertia/Vue pages, Web, Mobile, and PWA surfaces share the same product workspace while keeping their presentation concerns separate.
- **Use governed AI capabilities:** TitanCore and TitanZero provide manifest-backed orchestration, retrieval, tool execution, citations, provider failover, and agent evaluation; TitanNexus adds approval-aware lead, outreach, payment-assist, and job-handoff workflows.

## The engineering story

TitanPro’s distinctive design choice is separation of concerns. Business modules own domain behavior; the Laravel application and Filament shell provide the operational surface; AI passes through explicit guardrail, retrieval, tool, and evidence boundaries instead of being treated as an unbounded chat feature.

### Implemented AI boundaries

- `Modules/TitanCore/AI/AIOrchestratorPipeline.php` runs the ordered path **guardrail → retrieval → tool execution → citation**. A failed guardrail returns a blocked result before later stages run.
- `Modules/TitanCore/AI/ToolExecutor.php` provides manifest-resolved tools with configurable allowlists, optional permission and audit hooks, required-field checks, dry-run support, and elapsed-time checks.
- `Modules/TitanCore/Services/ProviderFailoverChain.php` tries ordered chat or embedding providers, fails over for missing status and configured 429/5xx responses, and stops on non-retryable errors.
- `Modules/TitanZero/Evaluation/AgentEvaluator.php` scores task completion, tool accuracy, hallucination flags, and latency using caller-supplied expectations and heuristics, then persists a weighted composite score with the response snapshot.
- `Modules/TitanNexus/README.md` documents approval gates around external outreach, payment nudges, payment plans, and booking/job handoffs; tenant policy must explicitly permit automation before those actions proceed.

These are concrete implementation boundaries: they support an auditable workflow around AI-assisted work without claiming that every module in the repository is AI-powered.

## Architecture and code map

| Area | Responsibility |
| --- | --- |
| `app/` | Shared Laravel application services, tenancy, policies, HTTP, jobs, and Filament integration. |
| `Modules/TitanCore/` | AI orchestration, provider adapters, tool permissions, manifests, citations, retrieval, and platform contracts. |
| `Modules/TitanZero/` | AI assistant flows, manifests, guardrails, retrieval policy, citations, and agent evaluation. |
| `Modules/TitanNexus/` | Lead-generation, marketing orchestration, payment-assist, and job/customer handoff workflows. |
| `Web/`, `Mobile/`, `PWA/`, `resources/` | Operator, field, mobile, progressive-web, and Vue/Inertia presentation surfaces. |
| `database/`, `routes/`, `tests/` | Persistence, application boundaries, and PHPUnit/Pest/Vitest/Dusk-oriented verification. |

At the platform level, the repository contains more than a simple CRUD shell: the challenge is keeping many domain modules coherent while making authorization, tenant scope, provider behavior, and AI side effects visible.

## Local development

Use the checked-in setup path to prepare environment, database, dependencies, and the full local process set:

```bash
cp .env.example .env
composer install
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate
npm install
composer run dev
```

`composer run dev` starts the Laravel server, queue listener, log viewer, and Vite together. `npm run dev` is the Vite frontend process only. For production assets and focused frontend checks:

```bash
npm run build
npm test
npm run format:check
```

The maintenance helpers have a dependency-free portability check:

```bash
node scripts/check-portable-paths.mjs
```

Verify environment, service credentials, and deployment assumptions against the current source before relying on any command.

## Evidence you can inspect

Focused tests and contracts show where the strongest verification effort lives:

- `Modules/TitanCore/Tests/Unit/ToolExecutorTest.php`
- `Modules/TitanCore/Tests/Unit/ManifestValidatorTest.php`
- `Modules/TitanCore/Tests/Unit/ProviderAdaptersTest.php`
- `Modules/TitanZero/Tests/Unit/AgentEvaluatorTest.php`
- `Modules/TitanZero/Tests/Unit/CrossTenantGuardTest.php`
- `Modules/TitanZero/Tests/Feature/EvaluationScoringTest.php`

The repository also carries a dependency-free portability evaluator for the two maintenance helpers:

```bash
node scripts/check-portable-paths.mjs
```

The [Portable Shell Paths workflow](.github/workflows/portable-shell-paths.yml) runs that focused check on relevant pull requests and pushes. It proves repository-relative script paths; it does not prove that the Laravel application boots or that a deployment host is configured.

## Maturity and provenance

TitanPro is an active portfolio codebase; focused source checks do not constitute a blanket production-readiness claim. The relationship to [Titan Zero Field Service Workforce](https://github.com/Masterleeaus/Titan-Zero-Field-Service-Workforce) is intentionally separate until lineage evidence establishes otherwise.

The root `LICENSE` identifies MIT terms and names Michael Stoffer as copyright holder. Preserve that attribution, confirm ownership and upstream provenance, and never commit production secrets, customer data, or local environment files.

For the detailed code map, evidence boundaries, and retained-artifact decisions, see [docs/PORTFOLIO.md](docs/PORTFOLIO.md).

## Continue exploring

- [Engineering guide](docs/PORTFOLIO.md) — detailed architecture map, quickstart, evidence boundaries, and limitations.
- [TitanCore AI orchestration](Modules/TitanCore/AI/AIOrchestratorPipeline.php)
- [TitanZero evaluation](Modules/TitanZero/Evaluation/AgentEvaluator.php)
- [TitanNexus approval model](Modules/TitanNexus/README.md)
