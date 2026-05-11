# TitanTalk Route Aliases

This pass adds TitanTalk-native route aliases while preserving legacy MarketingBot routes.

## API
- `api/titan-talk/whatsapp/{whatsappChannel}/webhook`
- `api/titan-talk/telegram/webhook/{token}`
- `api/titan-talk/voice/webhook/call`

## Inbox
- `dashboard/user/titan-talk/inbox/*`

## Console
- `dashboard/user/titan-talk`
- `dashboard/user/titan-talk/settings`
- `dashboard/user/titan-talk/voice/*`

These alias routes make the module usable under the TitanTalk identity without breaking older menu wiring.
