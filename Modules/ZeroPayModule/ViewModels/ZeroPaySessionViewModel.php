<?php

namespace Modules\ZeroPayModule\ViewModels;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class ZeroPaySessionViewModel
{
    public function __construct(public readonly ZeroPaySession $record) {}
}
