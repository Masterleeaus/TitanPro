<?php
namespace Modules\TitanNexus\Events;
class JobStatusChanged { public function __construct(public array $payload = []) {} }
