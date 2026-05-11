v1 kernel pass
- Added consumer-only TitanPulse extension.
- Reused AiSocialMedia provider/command shape.
- Reused Workflow idempotency, lock, rate-limit and condition patterns.
- Reads tz_signals and writes tz_ai_suggestions, tz_ai_pending_actions, tz_ai_agent_runs, tz_automation_runs.


## Pass 3
- Added pack catalog and pack resolver.
- Added per-tenant pack toggles via titan:pulse-packs.
- Filtered signal and sweep rules by enabled packs.
- Seeded built-in pack metadata through existing tz_automation_rule_sets.
