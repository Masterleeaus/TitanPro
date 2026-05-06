<?php
namespace Modules\TitanNexus\Events;
class JobCompleted { public function __construct(public array $payload = []) {} }
