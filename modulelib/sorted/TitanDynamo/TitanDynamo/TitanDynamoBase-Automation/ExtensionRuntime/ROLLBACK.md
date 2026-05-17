Remove app/Extensions/TitanPulse and the titan-pulse provider mapping in MarketplaceServiceProvider. No destructive DB migration included in this pass.


## Pass 3 rollback
- Remove pack resolver and packs command files.
- Remove PulseEngine pack filtering.
- Remove seeded tz_automation_rule_sets rows if needed.
