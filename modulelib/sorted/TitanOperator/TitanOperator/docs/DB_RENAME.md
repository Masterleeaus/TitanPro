# Titan Operator — DB Table Rename Pack

This install assumes you have **no live data** and want a clean hard rename to avoid collisions with any other chat extensions.

## What this migration does

Renames legacy tables (if they exist) to Titan Operator names:

- ext_operator_bots → ext_titan_operator_bots
- ext_titan_operator_conversations → ext_titan_operator_conversations
- ext_titan_operator_histories → ext_titan_operator_histories
- ext_titan_operator_customers → ext_titan_operator_customers
- ext_titan_operator_knowledge_base_articles → ext_titan_operator_knowledge_base_articles
- ext_titan_operator_workflow_runs → ext_titan_operator_workflow_runs
- ext_titan_operator_workflow_settings → ext_titan_operator_workflow_settings
- ext_titan_operator_tool_settings → ext_titan_operator_tool_settings

Voice tables (if present):
- ext_voice_operator_bots → ext_titan_operator_voice_bots
- ext_voiceoperator_histories → ext_titan_operator_voice_histories
- ext_voiceoperator_trains → ext_titan_operator_voice_trains

It disables FK checks during rename and is safe on empty installs (checks table existence).

## Apply

Run migrations after deploying the extension:
`php artisan migrate --force`
