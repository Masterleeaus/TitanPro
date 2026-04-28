<?php

namespace Modules\CRMCore\Contracts;

interface HostCRMBridge
{
    public static function modelClass(): string;
    public static function query();
}
