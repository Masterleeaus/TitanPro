# Drift Report

## Still present
- Legacy `marketing-bot` route prefixes remain across campaign/contact/settings/inbox areas.
- Many views still render through `marketing-bot::` namespace, especially legacy campaign and settings pages.
- Runtime models still primarily use `MarketingConversation` with TitanTalk aliases layered on top.

## Reduced in this pass
- Operator inbox/runtime views now resolve through `titantalk::` aliases.
- Realtime settings now use TitanTalk-native live routes instead of unrelated admin route.
