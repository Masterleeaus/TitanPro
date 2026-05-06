<?php
namespace Modules\TitanNexus\Workflows;
class JobAssistWorkflow { public array $steps = ['detect','draft','guardrail','approval','execute','record']; }
