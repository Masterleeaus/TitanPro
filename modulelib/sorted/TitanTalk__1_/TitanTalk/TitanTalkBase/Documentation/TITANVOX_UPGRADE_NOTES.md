TitanVox Upgrade Pass 1

What changed
- Added real intent classification and conversation state tracking.
- Added AI chatbot orchestration service for inbound WhatsApp and Telegram replies.
- Added assistant reply endpoint for the inbox UI / MagicAI bridge.
- Added TitanVox metadata fields on conversations.
- Added intent->tool hint registry so later Predix/TitanPulse binding has a stable surface.

Next passes
1. Bind intent tools to Predix / TitanPulse processing.
2. Add human handoff UI and operator takeover.
3. Normalize channels into VoxConversation / VoxMessage aliases.
4. Add confidence-aware clarification prompts and process bindings.
5. Add knowledge source controls per role pack.
