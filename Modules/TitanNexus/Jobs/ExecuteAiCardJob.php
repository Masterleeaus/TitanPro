<?php
namespace Modules\TitanNexus\Jobs;
final class ExecuteAiCardJob { public function __construct(public string $card, public array $context=[]) {} }

