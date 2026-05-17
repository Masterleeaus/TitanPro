<?php
namespace Modules\ExampleModule\Events;
use Modules\ExampleModule\Models\ExampleRecord;
class ExampleCreated{public function __construct(public readonly ExampleRecord $record){}}
