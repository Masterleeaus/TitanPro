# TitanTalk Architecture Blueprint

## Layer map
- UI lane: operator inbox, webchat frame, future MagicAI embeds.
- Conversation lane: intents, goals, role packs, state transitions.
- Channel lane: webhook intake, payload normalization, send/reply services.
- AI lane: generator, embeddings, parsers, retrieval context.
- Process lane: TitanPulse signals, handoffs, future Predix tool calls.

## Channel abstraction target
Each channel should converge on a normalized envelope:
- channel
- session_id
- message_id
- message
- message_type
- conversation_name
- customer_payload

## Process flow
1. Channel webhook receives payload.
2. Payload normalizer maps raw provider data into a common envelope.
3. Conversation is found or created.
4. Inbound user message is recorded.
5. Intent + state + role pack are updated.
6. AI reply and/or handoff decision is generated.
7. TitanPulse signals are emitted for downstream processing.

## Cleanup doctrine
- Keep donor code provenance while extracting stable subsystems.
- Prefer aliasing and wrappers before destructive renames.
- Keep TitanTalk as the conversation brain; do not absorb TitanPulse or Predix concerns into it.
