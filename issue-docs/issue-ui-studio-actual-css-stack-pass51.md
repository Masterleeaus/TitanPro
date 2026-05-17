# PASS51 UI Studio Actual CSS Stack Fix

## Files changed
- resources/views/filament/pages/ui-studio.blade.php
- scripts/install-ui-studio-actual-css-stack.sh

## What was wrong
Previous patches changed Tailwind grid classes, but this UI Studio layout is controlled by the embedded `.ui-studio-shell` CSS grid at the top of the Blade file.

## Fix applied
- Changed `.ui-studio-shell` from 3 CSS grid columns to one stacked column.
- Areas now render as catalogue, editor, preview vertically.
- Removed fixed viewport height clipping.
- Made panels full-width and non-clipped.
- Added visible `PASS51 STACKED` badge for confirmation.

## Next steps
- Upload to app root.
- Run install script.
- Hard refresh `/titanpro/ui-studio`.
