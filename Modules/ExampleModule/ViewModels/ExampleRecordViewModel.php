<?php

namespace Modules\ExampleModule\ViewModels;

class ExampleRecordViewModel
{
    public function __construct(public readonly \Modules\ExampleModule\Models\ExampleRecord $record){}
}
