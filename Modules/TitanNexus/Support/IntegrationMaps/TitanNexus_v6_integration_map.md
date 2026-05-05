# Titan Nexus v6 Integrated Source Map

This pass integrates code extracted from TitanNexus, TitanNexusBase, and TitanLeadsBase into TitanNexus while preserving original source under `Support/ExtractedCode/*`.

## Integrated capability groups

| Source capability | New TitanNexus slot | Enables |
|---|---|---|
| Twilio webhook controllers | `Http/Controllers/Voice/TwilioWebhookBridgeController.php` | inbound voice/SMS webhooks routed through Nexus |
| Voice chatbot controllers/services | `Services/Voice/*`, `AI/Tools/VoiceLeadExtractionTool.php` | call-based lead capture, transcription handoff, AI lead extraction |
| Call sessions/events/recordings models | `Models/Legacy/*` + migration import index | preserves call history domain while Nexus models evolve |
| Callback request jobs | `Jobs/Voice/*` | async callback creation, recording fetch, voicemail summary requests |
| SMS/voice channels | `Services/Channels/*` | multi-channel outreach foundation |
| Lead module pipelines/stages | `Services/LeadBridge/*`, `Events/Lead/*` | pipeline/stage bridge for lead qualification and Ground Zero handoff |
| Marketing conversation/campaign tables | `Database/migrations/legacy_imported/*` | preserves campaign/conversation data model for migration |
| Lead events/listeners | `Listeners/Lead/*` | decoupled CRM-style updates, stage changes, assignment events |
| Bridge websocket server | `Support/ExtractedCode/TitanNexus*/.../bridge` | future real-time voice bridge compatibility |

## Integration policy

1. Original extracted code is preserved unchanged.
2. New TitanNexus adapters wrap or map legacy code instead of destructive rewrites.
3. Legacy migrations are copied to `Database/migrations/legacy_imported` for audit/import planning.
4. Runtime-facing adapters live in first-class TitanNexus folders.
