# TitanTalk Dashboard Card Upgrade Notes

## Included upgraded cards
- titantalk-messaging-overview.blade.php
- titantalk-quick-actions.blade.php
- titantalk-recent-conversations.blade.php
- titantalk-campaign-focus.blade.php

## Intended mapping
- campaigns-stats.blade.php -> titantalk-messaging-overview.blade.php
- recently-launched.blade.php -> titantalk-quick-actions.blade.php
- recent-campaigns.blade.php -> titantalk-recent-conversations.blade.php
- announcement.blade.php -> titantalk-campaign-focus.blade.php

## Expected dashboard payload keys
- messagingStats
- quickActions
- recentConversations
- campaignFocus

## Notes
- Files are written as reusable Blade donor replacements.
- They use safe fallbacks so they can render before full backend wiring is complete.
- TitanTalk-native routes should replace legacy marketing-bot routes where available.
