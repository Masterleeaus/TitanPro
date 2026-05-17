# PWA Surface

Each module can expose the same command center in a PWA shell. The PWA uses the same agent manifest and action map as Filament, so chat/voice/channel behavior stays consistent.

Minimum PWA requirements:

- authenticated tenant context
- module permission checks
- chat endpoint bound to TitanAgents
- system-intent handoff to TitanZero
- confirmation UI for write actions
- citation panel for knowledge-backed answers
- offline-safe read-only cache for selected summaries
