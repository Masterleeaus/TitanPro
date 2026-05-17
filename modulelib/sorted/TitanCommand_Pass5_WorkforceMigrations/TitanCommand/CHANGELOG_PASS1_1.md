# Pass 1.1 — Command Jobs Routes

- Registered a new Command workspace route group: `/dashboard/user/command/*`.
- Added Jobs route surface under `/dashboard/user/command/jobs/*` as provided.
- Added a stub `JobsController` so all routes resolve without 500s (data wiring lands in Pass 2).
- Removed the previous placeholder `/dashboard/user/jobs/*` route group to avoid collisions.
