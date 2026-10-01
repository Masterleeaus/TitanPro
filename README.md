<div align="center">

# TitanPro

**A Laravel and Vue application workspace in the Titan product family.**

</div>

> **Status: project identity and relationship under review.** The repository contains a substantial Laravel application and Mobile source tree. Its current product role and relationship to Titan Zero Field Service Workforce were not established by the inspected default-branch files.

## Repository overview

- Primary application: Laravel
- Frontend tooling: Vite, Vue/Inertia, Tailwind CSS
- Test tooling: Vitest
- Root scripts are defined in `package.json`.

This landing page does not claim that the application builds, passes tests, or is production-ready; those checks were not run during this portfolio review.

## Local development

Review `.env.example` and the Composer manifests, configure a local environment, then use the checked-in scripts:

```bash
composer install
npm install
npm run dev
```

For production assets, the repository defines `npm run build`. The available npm checks include:

```bash
npm test
npm run format:check
```

Verify each command against the current source and deployment environment before relying on it.

## Portfolio classification

**Needs owner and lineage confirmation.** Compare against [Titan BOS](https://github.com/Masterleeaus/Titan-BOS), [cleanly](https://github.com/Masterleeaus/cleanly), [modules](https://github.com/Masterleeaus/modules), and the [current workforce platform](https://github.com/Masterleeaus/Titan-Zero-Field-Service-Workforce). Keep it as a separate portfolio project only if it has a distinct purpose or valuable, attributable implementation.

## Security and provenance

Never commit production secrets, customer data, or local environment files. Confirm the license and upstream provenance for imported code before redistribution.

## Banner

A verified TitanPro banner was not identified in this first-pass review. The centered title is a typographic placeholder until a project-specific visual is confirmed.
