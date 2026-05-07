<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

test('modules:doctor flags missing driver location organization scoping column', function () {
    Schema::table('driver_locations', function (Blueprint $table) {
        $table->dropColumn('organization_id');
    });

    $this->artisan('modules:doctor', ['--skip-schema' => true])
        ->expectsOutputToContain('missing organization_id column required for org-scoped technician location queries')
        ->assertExitCode(1);
});
