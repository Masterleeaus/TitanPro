# QA Test Plan
## Visibility & Plans
- Switch tenant plan to include/exclude `aicopilot` → menu/widgets appear/disappear
## Permissions
- Remove `view_aicopilot` → menu hides; route 403
- Keep `view_*` but not `manage_*` → reads OK, writes blocked
## Routes
- `php artisan route:list` shows your module routes and `aicopilot` API routes
## Facade
- Use `AICopilot::ask()` in a controller; verify response is non-empty
## API
- POST `/api/aicopilot/assistant` returns JSON with `reply`
## Widgets
- `@include('aicopilot::widgets.render', ['location'=>'main_dashboard'])` renders without errors
## DB
- Migrations inserted permissions + assigned to Admin; packages updated
