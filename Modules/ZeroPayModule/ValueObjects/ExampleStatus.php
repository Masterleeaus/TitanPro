<?php

namespace Modules\ZeroPayModule\ValueObjects;

enum ExampleStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Archived = 'archived';
}
