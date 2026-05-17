# ZeroPayModule

Domain-first, tenant-safe, Filament-thin Titan module.

## AI-controllable module standard

This module follows the Titan AI-native module pattern:

- one Filament sidebar link
- one Filament module command page
- top half: quick cards, widgets, module AI chatbot, shortcuts, settings
- bottom half: one tabbed table card for all module tables
- PWA/channel/voice control through the same agent and action map
- TitanZero supervises system AI; TitanAgents runs the trained module agent; TitanCore provides AI infrastructure

See:

- `Docs/AI_CONTROL_AND_CHANNELS.md`
- `Docs/FILAMENT_ONE_PAGE_STANDARD.md`
- `AI/Control/control.manifest.json`
- `PWA/pwa.manifest.json`
