# Responsive UI Studio Hotfix

## Files changed
- app/Filament/Pages/UiStudio.php
- scripts/install-ui-studio-customcss-hotfix.sh

## Fix applied
- Added missing Livewire public property `$customCss`.
- Added safe defaults for preview mode and viewport width if absent.

## Next steps
- Upload delta to app root.
- Run install script.
- Reopen `/titanpro/ui-studio`.
