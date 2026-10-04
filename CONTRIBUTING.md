# Contributing to TitanPro

## Evidence first

Keep implementation claims aligned with what the repository can reproduce.

Use these labels precisely:

- **Implemented** — source is present and wired.
- **Tested** — relevant checks actually executed successfully.
- **Evaluated** — a reproducible scenario set and metric exist.
- **Experimental** — implementation exists but verification is incomplete.
- **Planned** — no implementation claim is made.

## Before a change

Read the README and relevant architecture/development documentation. Preserve existing trust boundaries, attribution, and licensing. Prefer focused changes over unrelated cleanup.

## Verification

Run the repository's documented test, lint, build, validation, and evaluation commands that apply to the change.

If a full run cannot complete, record the exact command and failure and state which targeted checks did run. Do not describe unexecuted tests as passing.

## High-risk changes

Changes involving authorization, tenancy, credentials, AI-triggered actions, payments, external side effects, file access, destructive operations, or persistent-state mutation should include negative and boundary cases where practical.

## Documentation

Update public documentation when a change affects capability claims, architecture, evaluation status, installation steps, security posture, or known limitations.
