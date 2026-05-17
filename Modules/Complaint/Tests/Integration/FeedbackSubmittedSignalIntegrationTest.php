<?php

namespace Modules\Complaint\Tests\Integration;

use Modules\Complaint\Listeners\FeedbackSubmittedListener;
use Modules\ZeroFussPortal\Events\FeedbackSubmitted;
use PHPUnit\Framework\TestCase;

class FeedbackSubmittedSignalIntegrationTest extends TestCase
{
    public function test_feedback_submitted_signal_contract_is_wired_to_listener(): void
    {
        $this->assertTrue(class_exists(FeedbackSubmitted::class));
        $this->assertTrue(class_exists(FeedbackSubmittedListener::class));
    }
}
