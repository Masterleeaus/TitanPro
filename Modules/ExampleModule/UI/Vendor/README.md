# Vendor UI Runtime Slots

Place extracted usable UI code here:

- `A2UI/` for assistant message rendering, cards, tables, citations, progress blocks.
- `Tambo/` for assistant-driven UI actions, panel mutations, and tool feedback.

Do not call these directly from business logic. Use `UI/ZeroChat` adapters so PWA, Filament, WhatsApp, voice, and future channels can share one Zero runtime.
