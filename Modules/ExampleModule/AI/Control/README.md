# AI Module Control

This module is controllable through Filament chat, PWA chat, connected channels, and voice.

## Runtime ownership

- **TitanZero** is the system AI supervisor: routing, governance, configuration, diagnostics, cross-module orchestration.
- **TitanAgents** owns the module agent runtime: trained module agent, tools, knowledge binding, action execution, and module-specific conversations.
- **TitanCore** owns providers, embeddings, retrieval primitives, prompt registry, usage tracking, policies, and audit infrastructure.

## Command lifecycle

1. User speaks or chats from Filament, PWA, or an external channel.
2. TitanZero classifies whether the request is system-level or module-level.
3. Module-level requests route to this module's TitanAgents agent.
4. The agent retrieves from `Knowledge/`, reads the module state, and selects an action.
5. Write/destructive actions require confirmation unless explicitly marked safe.
6. TitanCore logs usage, policy decisions, citations, and tool execution.
