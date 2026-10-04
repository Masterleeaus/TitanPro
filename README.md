![Titan Pro Business Operations Console — LARAVEL + VUE WORKSPACE](docs/images/portfolio-banner.svg)

<div align="center">

# Titan Pro Business Operations Console

**A modular Laravel and Vue operations platform for teams coordinating field work, customers, finance, communications, and AI-assisted decisions in one workspace.**

</div>

Titan Pro is built for operations teams that need more than a collection of disconnected admin screens. It brings customer and workforce workflows into one Laravel application, then gives each surface—office, field, mobile, PWA, and customer-facing—a shared domain model and a consistent operator experience.

## What teams can do

- **Run the operation:** CRM, booking, dispatch, cleaning jobs, quoting, payroll, accounting, supply chain, payments, messaging, and customer-portal workflows live in one modular application.
- **Serve different roles:** Filament operator screens, Inertia/Vue pages, Web, Mobile, and PWA surfaces share the same product workspace while keeping their presentation concerns separate.
- **Use governed AI capabilities:** TitanCore and TitanZero provide manifest-backed orchestration, retrieval, tool execution, citations, provider failover, and agent evaluation; TitanNexus adds approval-aware lead, outreach, payment-assist, and job-handoff workflows.

## The engineering story

Titan Pro’s distinctive design choice is separation of concerns. Business modules own domain behavior; the Laravel application and Filament shell provide the operational surface; AI passes through explicit guardrail, retrieval, tool, and evidence boundaries instead of being treated as an unbounded chat feature.

### Implemented AI boundaries

- `Modules/TitanCore/AI/AIOrchestratorPipeline.php` runs the ordered path **guardrail → retrieval → tool execution → citation**. A failed guardrail returns a blocked result before later stages run.
- `Modules/TitanCore/AI/ToolExecutor.php` resolves handlers from a manifest and applies an allowlist, permission gate, declared input validation, dry-run mode, timeout controls, and audit writes. Runtime context includes `company_id` for the tenant boundary.
- `Modules/TitanCore/Services/ProviderFailoverChain.php` tries ordered chat or embedding providers, fails over for missing status and configured 429/5xx responses, and stops on non-retryable errors.
- `Modules/TitanZero/Evaluation/AgentEvaluator.php` records task completion, hallucination flags, tool accuracy, latency, and a weighted composite score against an evaluation snapshot.
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

## Local development

Review `.env.example` and the Composer/npm manifests, configure a local environment, then use the checked-in commands:

```bash
composer install
npm install
npm run dev
```

For production assets and available checks:

```bash
npm run build
npm test
npm run format:check
```

Verify environment, service credentials, and deployment assumptions against the current source before relying on any command.

## Portfolio status and provenance

Titan Pro is a substantial Laravel/Vue portfolio codebase and an active product-family workspace. The repository’s relationship to [Titan Zero Field Service Workforce](https://github.com/Masterleeaus/Titan-Zero-Field-Service-Workforce) is intentionally described separately; do not present the two repositories as one canonical implementation without lineage evidence.

This README highlights implemented source boundaries, not a blanket production-readiness claim. The full [engineering guide](docs/PORTFOLIO.md) records the repository-specific quickstart, evidence scope, limitations, and retained-artifact decisions.

The root `LICENSE` identifies MIT terms and names Michael Stoffer as copyright holder. Preserve that attribution, confirm ownership and upstream provenance, and never commit production secrets, customer data, or local environment files.

## Continue exploring

- [Engineering guide](docs/PORTFOLIO.md) — detailed architecture map, quickstart, evidence boundaries, and limitations.
- [TitanCore AI orchestration](Modules/TitanCore/AI/AIOrchestratorPipeline.php)
- [TitanZero evaluation](Modules/TitanZero/Evaluation/AgentEvaluator.php)
- [TitanNexus approval model](Modules/TitanNexus/README.md)
