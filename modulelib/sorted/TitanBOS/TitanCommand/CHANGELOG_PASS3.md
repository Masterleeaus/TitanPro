# Pass 3 — Dispatch/Schedule + Menu Seed

- Added idempotent migration to seed Jobs Manager sidebar menu items (parent + children) into whichever menu table exists (menus/menu_items/sidebar_menus).
- Implemented DB-backed dispatch, assignment, unassignment, scheduling, and rescheduling endpoints in JobsController.
- Added tenant helper and extended JSON ok() payloads to include data.

