
# Titan Pulse Runtime Package

This package contains the TitanPulse extension plus imported runtime source from:
- Workflow module -> PulseKernel
- AiSocialMedia extension -> PulseEngine

Purpose:
- keep Pulse extension-only
- provide heavier runtime skeleton instead of a thin rules shell
- preserve source patterns for worker cadence, queue jobs, rule evaluation, idempotency, locks, and AI-generation wrappers

Important:
- Pulse remains consumer-only for signals
- no SignalBus or signal table creation code is included here
