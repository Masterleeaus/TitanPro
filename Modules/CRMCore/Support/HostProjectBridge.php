<?php

namespace Modules\CRMCore\Support;

use App\Models\Job;

class HostProjectBridge
{
    public static function modelClass(): string { return Job::class; }
    public static function query() { return Job::query(); }
    public static function create(array $payload) { return Job::create($payload); }
}
