<?php
namespace Modules\Accountings\Events;
class StatementGenerated { public function __construct(public array $payload = []) {} }
