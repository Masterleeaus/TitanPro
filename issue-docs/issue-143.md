## Issue Summary
Implemented a configurable UI motion layer for TitanNexus UI Studio settings with preset, speed, and easing controls, generated CSS output, reduced-motion accessibility handling, and live preview metadata.

## Root Cause
The UI Studio theme scaffolding existed but motion configuration was not implemented. Core token definitions, generated animation classes, and Motion tab schema were empty, so no animation options could be configured or surfaced to the UI engine.

## Changes Made
- Implemented `Modules/TitanNexus/UI/Themes/UiTokens.php` with:
  - Motion token defaults (`--motion-preset`, `--motion-speed`, `--motion-ease`)
  - Required preset catalog (`none`, `fade-in`, `slide-up`, `smooth-sidebar`, `hover-lift`, `blur-overlay`, `shimmer-load`, `scale-press`)
  - Speed and easing token maps
  - Generated CSS for each preset and keyframes
  - `prefers-reduced-motion` override to disable motion effects
- Implemented `Modules/TitanNexus/UI/Forms/ModuleSettingsForm.php` with a `Motion` tab schema including:
  - Preset selector bound to `--motion-preset`
  - Speed selector bound to `--motion-speed`
  - Easing selector bound to `--motion-ease`
  - Live preview metadata and generated CSS wiring
- Updated `Modules/TitanNexus/UI/Tests/UI/UiKitStructureTest.php` with focused assertions validating:
  - Required motion tokens and preset options
  - Presence of generated preset CSS and reduced-motion media query
  - Motion tab schema fields and live preview config

## Tests Added or Updated
- Updated: `Modules/TitanNexus/UI/Tests/UI/UiKitStructureTest.php`
  - Added tests for motion token output and preset coverage
  - Added tests for generated CSS including accessibility handling
  - Added tests for Motion tab schema and live preview controls

## Next Steps
- Connect this schema into the runtime UI Studio renderer so the generated CSS is injected into active panel themes.
- Add end-to-end browser validation for real-time preview once a PHP 8.4-compatible runtime is available in CI.
