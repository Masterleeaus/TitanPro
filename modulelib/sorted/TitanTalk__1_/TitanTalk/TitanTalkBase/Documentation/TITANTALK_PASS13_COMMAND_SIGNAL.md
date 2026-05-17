# TitanTalk Pass 13 — Command + Signal Loop

This pass upgrades TitanTalk from intent-only routing into a true command/signal loop for Zero.

## Added
- CommandInterpreter: turns conversation intent + extracted entities into structured business commands.
- TitanTalkSignalBridge: converts commands into TitanPulse-ready signal envelopes.
- VoiceCommandRouter: routes transcripts through TitanTalk so Zero can work as a voice control layer.
- VoiceCommandController: JSON endpoint for transcript → command → signal → response.

## Purpose
TitanTalk now has the beginning of a real control loop:

voice/chat message → intent → command → signal → process

This keeps TitanTalk as the conversation layer while TitanPulse stays the processing layer.
