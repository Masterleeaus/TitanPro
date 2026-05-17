
# Imported Sources

## Workflow -> PulseKernel
- WorkflowEngine.php
- WorkflowEvaluator.php
- TriggerRegistry.php
- ActionRegistry.php
- WorkflowRunRecorder.php
- Idempotency/*
- RateLimit/*
- Locks/*
- Jobs/*
- Handler manager pattern

## AiSocialMedia -> PulseEngine
- AutomationService.php -> PulseActionSourceService.php
- ScheduledPostService.php -> ScheduledPulseSourceService.php
- UserPostJob.php -> RunPulseGenerationJob.php
- GeneratePostDaily/Weekly/MonthlyCommand.php -> Pulse cadence command scaffolds
- BaseService.php -> BasePulseService.php

No SignalBus creation code is included.
