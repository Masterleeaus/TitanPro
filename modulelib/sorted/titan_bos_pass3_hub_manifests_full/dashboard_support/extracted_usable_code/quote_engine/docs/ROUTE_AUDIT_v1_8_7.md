# Route Audit v1.8.7

This pass hardens QuoteMaker routing so the extension stays inside the dashboard/user context.

What was checked:
- Route file uses `dashboard.user.quotemaker.*` names
- Blade CTAs use `route(...)`
- Controller redirect targets use named routes
- No remaining intended hardcoded `/quotemaker/*` navigation in app flow

Recommended menu route keys:
- dashboard.user.quotemaker.index
- dashboard.user.quotemaker.builder
- dashboard.user.quotemaker.templates
- dashboard.user.quotemaker.gallery
