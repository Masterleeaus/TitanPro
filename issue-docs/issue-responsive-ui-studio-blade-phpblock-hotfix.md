# Responsive UI Studio Blade PHP Block Hotfix

## Files changed
- resources/views/filament/pages/ui-studio.blade.php
- scripts/install-ui-studio-blade-phpblock-hotfix.sh

## Fix applied
- Replaced inline Blade `@php(...)` expressions with full `@php ... @endphp` blocks.
- Clears compiled Blade/cache after upload.

## Next steps
- Upload delta.
- Run install script.
- Reopen `/titanpro/ui-studio`.
