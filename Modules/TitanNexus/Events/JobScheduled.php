<?php
namespace Modules\TitanNexus\Events;
class JobScheduled { public function __construct(public array $payload = []) {} }
