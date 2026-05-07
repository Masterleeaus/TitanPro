<?php

use App\Models\Job;

test('admin workflow only allows scheduled to in progress to completed', function () {
    expect(Job::canTransitionInAdminWorkflow(Job::STATUS_SCHEDULED, Job::STATUS_IN_PROGRESS))->toBeTrue()
        ->and(Job::canTransitionInAdminWorkflow(Job::STATUS_IN_PROGRESS, Job::STATUS_COMPLETED))->toBeTrue()
        ->and(Job::canTransitionInAdminWorkflow(Job::STATUS_SCHEDULED, Job::STATUS_COMPLETED))->toBeFalse()
        ->and(Job::canTransitionInAdminWorkflow(Job::STATUS_COMPLETED, Job::STATUS_IN_PROGRESS))->toBeFalse();
});
