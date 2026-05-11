# TitanTalk donor map — pass 8

## Merged donor packs
- `donors/Chatbot/` — webchat UI, knowledge-base, embeddings, multi-generator, external widget assets.
- `donors/ChatbotVoice/` — voice chatbot admin/history/training flows.
- `donors/ElevenlabsVoiceChat/` — ElevenLabs-oriented voice bot/training flow.

## Immediate extraction targets
- Webchat surface: `Chatbot/resources/views/frontend-ui/*`, `resources/assets/js/external-chatbot.js`
- Knowledge base: `Chatbot/System/Models/ChatbotKnowledgeBaseArticle.php`, `System/Tools/KnowledgeBase.php`
- Voice bot models/controllers: `ChatbotVoice/System/Models/*`, `System/Http/Controllers/*`
- ElevenLabs provider lane: `ElevenlabsVoiceChat/System/Services/ElevenLabsVoiceChatService.php`

## Pass-8 additions
- `System/Services/TitanTalk/Webchat/WebchatBridgeService.php`
- `System/Services/TitanTalk/Voice/VoiceBotResolverService.php`
- `System/Http/Controllers/TitanTalk/WebchatFrameController.php`
- `config/titantalk-webchat.php`

## Why this pass
This pass keeps the donor code intact inside TitanTalk so future cleanup passes can extract from real source without losing provenance.
