# PASS70 AI Lead Manager Extraction

## Source scanned
`ai_lead_manager.zip`

## Source type
Perfex CRM / CodeIgniter module.

## Source inventory
- 136 files
- 102 PHP files
- 4 controllers
- 18 voice AI library files
- 1 call log model
- 10 views
- Bland.ai integration
- Vapi.ai integration
- Lead-triggered outbound call helpers
- Voice webhook handlers
- Call log table logic

## Usable code extracted
- Bland.ai API client
- Vapi.ai API client
- Outbound lead call service
- Voice webhook controller
- Voice call log model
- Voice call log migration
- TitanNexus Filament resource for call logs
- API route file for webhook endpoints

## Source code not copied directly
The source module depends on Perfex CRM, CodeIgniter helpers, global option storage, CRM hooks, and legacy request libraries. Those parts were not copied directly. The logic was converted into Laravel services, config, routes, models, migrations, and Filament resources.

## New environment keys
- `TITANNEXUS_VOICE_PROVIDER`
- `BLAND_AI_API_KEY`
- `BLAND_AI_ENCRYPTED_KEY`
- `BLAND_AI_VOICE`
- `BLAND_AI_MAX_DURATION`
- `BLAND_AI_TEMPERATURE`
- `VAPI_AI_API_KEY`
- `VAPI_AI_ASSISTANT_ID`
- `VAPI_AI_PHONE_NUMBER_ID`
- `TITANNEXUS_VOICE_OUTBOUND_PROMPT`
- `TITANNEXUS_VOICE_FIRST_SENTENCE`

## Webhook endpoints
- `/api/titan-nexus/voice/webhooks/bland`
- `/api/titan-nexus/voice/webhooks/vapi/inbound`
- `/api/titan-nexus/voice/webhooks/vapi/outbound`

## Next steps
- Add a Filament action on lead records: `Call Lead`.
- Add per-vertical call scripts.
- Add webhook signature validation.
- Add call result mapping to lead status.
- Add transcript summarization and objection extraction.
