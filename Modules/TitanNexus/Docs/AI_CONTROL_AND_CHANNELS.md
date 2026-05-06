# AI Control, PWA, Channel, and Voice Enablement

Every Titan module should be operable from the Filament one-page module command center, PWA chat, PWA voice, connected customer/staff channels, and API clients.

## Required pieces

1. `Agents/ModuleAgent/agent.manifest.json`
2. `Knowledge/` plus `AI/Indexing/indexing.manifest.json`
3. `AI/Actions/action-map.json` and `AI/Control/control.manifest.json`
4. `AI/Channels/channel.manifest.json`
5. `AI/Voice/voice.manifest.json`
6. `PWA/pwa.manifest.json`
7. `Filament/Pages/*ModulePage.php`
8. `Filament/PageManifest/module-page.json`
9. `Filament/Tables/table-tabs.json`

## Safety model

Read actions can execute after policy checks. Draft actions do not save until confirmed. Write actions require user confirmation. Destructive actions require explicit confirmation and may require elevated permission. All actions are tenant-scoped and audited.

## Routing model

TitanZero handles system configuration, cross-module orchestration, diagnostics, and governance. TitanAgents handles the module-trained AI and module tools. TitanCore handles providers, embeddings, vector search, memory, usage, policy, audit, and prompt registry.
