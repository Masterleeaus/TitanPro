# TitanTalk Pass 11 — Tool Planning + Intent/State Brain

This pass adds the first real conversation brain and tool-planning lane:

- `IntentEngine` wraps classifier logic and extracts lightweight entities.
- `GoalEngine` assigns a business goal for each conversation.
- `ConversationStateMachine` computes the next state consistently.
- `ToolRegistry` defines TitanTalk-safe tool schemas.
- `ToolCallEngine` creates Predix-ready tool plans instead of pretending actions already happened.
- `AiChatbotService::replyWithContext()` now returns structured metadata for UI/operator use.

This is still a planning bridge, not direct business mutation. That keeps TitanTalk honest and safe.
