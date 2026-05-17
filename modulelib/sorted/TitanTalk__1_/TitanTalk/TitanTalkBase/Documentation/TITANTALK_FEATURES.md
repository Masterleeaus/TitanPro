# TitanTalk Features Inventory

## Live features in this merged base
- AI-assisted inbox for WhatsApp and Telegram conversations.
- Intent classification for booking, quote, support, complaint, invoice, reschedule, cancel, and human handoff.
- Conversation state and goal tracking with role-pack metadata.
- OpenAI-based reply generation with campaign-aware fallback behavior.
- Embeddings and knowledge ingestion from PDF, Excel, text, and link sources.
- Voice lane from TitanHello: call inbox, callback workflows, dialer, inbound numbers, ring groups, IVR, recordings, and provider logs.
- Campaign surfaces for WhatsApp and Telegram outbound messaging.
- Training UI and embedding generation flows.
- Inbox resources and conversation/message API resources.
- Channel webhook handlers for WhatsApp, Telegram, and voice.

## Cleanup goals started in this pass
- Introduce TitanTalk-native config namespace while keeping legacy compatibility.
- Centralize config reads behind a small support helper.
- Reduce hardcoded `marketing-bot` config dependency in new conversation services.
- Prepare the service provider for dual TitanTalk/legacy namespace loading.

## Pass 7 — Human handoff donor merge (ChatbotAgent)
- Added TitanTalk operator inbox shell using the donor chat panel views.
- Added realtime config + Ably-compatible publishers for panel and session updates.
- Added TitanTalk operator notification endpoint and realtime settings UI.
