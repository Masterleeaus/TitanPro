# Issue 360 — Install TitanOperator module

## Files changed
- `Modules/TitanOperator/module.json`
- `Modules/TitanOperator/Providers/TitanOperatorServiceProvider.php`
- `Modules/TitanOperator/Routes/api.php`
- `Modules/TitanOperator/Http/Controllers/ChannelWebhookController.php`
- `Modules/TitanOperator/Services/KnowledgeBaseEmbeddingPipeline.php`
- `Modules/TitanOperator/Models/{Operator,Channel,KnowledgeBaseArticle,Conversation}.php`
- `Modules/TitanOperator/Filament/Plugin/TitanOperatorPlugin.php`
- `Modules/TitanOperator/Filament/Resources/**` (Operator, Channel, KnowledgeBase, Conversation resources + pages)
- `Modules/TitanOperator/Database/Migrations/*.php` (copied from pass15 source and adjusted for local compatibility)
- `Modules/TitanOperator/Resources/views/frame.blade.php` (copied from pass15 source)
- `Modules/TitanEchoAssist/Models/Conversation.php`
- `tests/Feature/Modules/TitanOperatorIntegrationTest.php`

## Fixes applied
- Installed `TitanOperator` module scaffold under `Modules/TitanOperator` with active module metadata and `titanpro` panel targeting.
- Registered `Modules\\TitanOperator\\Providers\\TitanOperatorServiceProvider` via module manifest provider list.
- Wired operator/channel/knowledge-base/conversation Filament resources through `TitanOperatorPlugin`.
- Added webhook dispatch endpoint for channel callbacks and operator conversation creation.
- Added knowledge-base embedding ingest pipeline writing to `tz_portal_operator_embeddings`.
- Bridged `TitanEchoAssist` conversation creation into `tz_portal_operator_conversations` using channel-to-operator mapping.
- Imported Pass15 portal migrations and frame view, then patched migration defaults/guards for compatibility.
- Added focused feature tests for module manifest, webhook dispatch, KB embedding ingest, and EchoAssist association.

## Next steps
- Run `php artisan module:migrate TitanOperator` in an environment with Composer dependencies installed.
- Confirm Filament navigation visibility in the `titanpro` panel with real seeded users/roles.
- Expand webhook processing per-channel auth/signature validation if needed.
