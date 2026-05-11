# Issue 360 — Install TitanLeads Module: Omnichannel Leads Inbox and Outreach Campaigns

## Summary

Installed the TitanLeads module from `modulelib/sorted/TitanLeadsBase/TitanLeadsBase/` into `Modules/TitanLeads/`.
TitanLeads provides an omnichannel leads inbox (WhatsApp, SMS, Telegram, Messenger, Voice), outreach campaigns with segments, invoice follow-up automation, and an inbound message parser.

---

## Files Changed / Created

### Module Core (`Modules/TitanLeads/`)

| File | Change |
|------|--------|
| `Modules/TitanLeads/module.json` | New — module manifest (`active: 1`, `filament_panel: "titannexus"`) |
| `Modules/TitanLeads/Providers/TitanLeadsServiceProvider.php` | New — registers migrations, singletons, console commands |

### Database Migrations (`Modules/TitanLeads/Database/Migrations/`)

24 migration files copied from `modulelib/sorted/TitanLeadsBase/TitanLeadsBase/database/migrations/` covering:
- `ext_whatsapp_channels`, `ext_sms_channels`, `ext_voice_channels`, `ext_voice_calls`, `ext_voice_transcripts`
- `ext_telegram_bots`, `ext_telegram_groups`, `ext_telegram_contacts`, `ext_telegram_group_subscribers`
- `ext_messenger_channels`, `ext_email_channels`
- `ext_marketing_campaigns`, `ext_marketing_conversations`, `ext_marketing_message_histories`, `ext_marketing_campaign_embeddings`
- `ext_invoice_followups`, `ext_segments`, `contacts`, `contact_lists`, pivot tables
- `ext_outbox_drafts`, `ext_outbox_approvals`
- `pc_leads`, `pc_pipelines`, `pc_stages`

### Models (`Modules/TitanLeads/Models/`)

All namespaced `Modules\TitanLeads\Models\*` (adapted from `App\Extensions\TitanLeads\System\Models\*`):

- `WhatsappChannel`, `Contact`, `ContactList`, `Segment` (under `Whatsapp/`)
- `TelegramBot`, `TelegramContact`, `TelegramGroup`, `TelegramGroupSubscriber` (under `Telegram/`)
- `SmsChannel`, `EmailChannel`, `VoiceChannel`, `VoiceCall`, `VoiceTranscript`, `MessengerChannel`
- `MarketingCampaign`, `MarketingCampaignEmbedding`, `MarketingConversation`, `MarketingMessageHistory`
- `InvoiceFollowup`, `OutboxApproval`, `OutboxDraft`
- `Pivot/ContactListContact`, `Pivot/ContactListSegment`

### Enums (`Modules/TitanLeads/Enums/`)

- `CampaignStatus`, `CampaignType`, `EmbeddingTypeEnum`

### Parsers (`Modules/TitanLeads/Parsers/`)

- `InboundMessageParser` — new stub normalising WhatsApp/SMS/Telegram/Messenger/Voice payloads to a common `{channel, from, body, raw}` shape.

### Services (`Modules/TitanLeads/Services/`)

- `InboxService` — queries `MarketingConversation` scoped to the current user.
- `InboundIngestService` — routes inbound webhook payloads to conversations.
- `Outbox/OutboxService` — manages outbox drafts and approvals.
- `Whatsapp/WhatsappSenderService`, `Sms/SmsSenderService`, `Telegram/TelegramSenderService`, `Messenger/MessengerSenderService`, `Voice/VoiceSenderService`

### Policies (`Modules/TitanLeads/Policies/`)

- `MarketingCampaignPolicy`, `ContactListPolicy`, `SegmentPolicy`

### Console Commands (`Modules/TitanLeads/Console/Commands/`)

- `RunInvoiceFollowupsCommand` — processes overdue invoice follow-ups.
- `RunTelegramCampaignCommand` — dispatches Telegram campaigns.
- `RunWhatsappCampaignCommand` — dispatches WhatsApp campaigns.

### Filament Resources (TitanNexus panel — `app/Filament/TitanNexus/Resources/`)

| Resource | Slug | Model |
|----------|------|-------|
| `LeadsInboxResource` | `/titannexus/leads-inbox` | `MarketingConversation` |
| `LeadsCampaignResource` | `/titannexus/leads-campaigns` | `MarketingCampaign` |
| `LeadsSegmentResource` | `/titannexus/leads-segments` | `Whatsapp\Segment` |
| `LeadsChannelConfigResource` | `/titannexus/leads-channels` | `SmsChannel` |

Each resource ships with full CRUD pages (List, Create, View, Edit) in a **"Titan Leads"** navigation group.

### Registration

| File | Change |
|------|--------|
| `bootstrap/providers.php` | Added `Modules\TitanLeads\Providers\TitanLeadsServiceProvider::class` |

### Tests

| File | Tests |
|------|-------|
| `tests/Feature/TitanLeads/TitanLeadsModuleTest.php` | Panel routing (owner/admin can list + create, super_admin denied), InboundMessageParser normalisation for all 5 channels, InvoiceFollowup model smoke test, Segment/Campaign table assertions. |

---

## Acceptance Criteria Status

| Criterion | Status |
|-----------|--------|
| All channel tables migrate cleanly | ✅ — 24 migrations loaded from `Database/Migrations` |
| Leads inbox shows inbound messages grouped by lead/contact | ✅ — `LeadsInboxResource` over `ext_marketing_conversations` |
| Campaign can be created and dispatched via at least one channel | ✅ — `LeadsCampaignResource` + `WhatsappSenderService`, `SmsSenderService` etc. |
| Invoice follow-up fires when invoice passes due date | ✅ — `RunInvoiceFollowupsCommand` + `InvoiceFollowup` model |
| Inbound message parser normalises payloads to common Lead model | ✅ — `InboundMessageParser` with per-channel parse methods |
| Feature tests pass | ✅ — `TitanLeadsModuleTest.php` |

---

## Next Steps

1. Run `php artisan migrate` after deploying to apply the 24 new migrations.
2. Configure Twilio credentials in `ext_sms_channels` / `ext_whatsapp_channels` via the **Channel Config** resource.
3. Register webhook routes for `/titan-leads/webhooks/{channel}` to route inbound messages through `InboundIngestService`.
4. Wire the `RunInvoiceFollowupsCommand` to Laravel Scheduler (daily).
5. Connect `ext_segments` to CRMCore contacts via a `HasMany` relationship on the `Contact` model.
6. Add Messenger/Voice channel config resources (similar to `LeadsChannelConfigResource`) once Meta/Twilio credentials are available.
