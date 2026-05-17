# TitanTalk + TitanHello Merge

This pass merges TitanHello's voice/call stack into TitanTalk as a native voice lane.

## Added into TitanTalk
- Voice models under `System/Models/Voice/*`
- Voice services under `System/Services/Voice/*`
- Voice controllers under `System/Http/Controllers/Voice/*`
- Voice requests under `System/Http/Requests/Voice/*`
- Voice views under `resources/views/voice/*`
- Voice JS helper under `resources/assets/js/voice/toasts.js`
- TitanHello call/callback/routing migrations copied and namespace-normalized

## Route surfaces added
- `api/marketing-bot/voice/webhook/call`
- `dashboard/user/marketing-bot/voice/calls`
- `dashboard/user/marketing-bot/voice/callbacks`
- `dashboard/user/marketing-bot/voice/dialer`

## Notes
This merge preserves TitanTalk as the conversation brain while pulling TitanHello into a voice lane. The copied code still needs follow-up passes for naming cleanup, permissions normalization, and deeper TitanPulse / Predix bindings.
