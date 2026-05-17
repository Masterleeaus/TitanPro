<?php

namespace Modules\Complaint\Tests\Unit;

use Modules\Complaint\Actions\CreateComplaintAction;
use Modules\Complaint\Services\ComplaintAnalysisService;
use PHPUnit\Framework\TestCase;

class CreateComplaintActionTest extends TestCase
{
    public function test_it_requires_subject_before_creating_record(): void
    {
        $action = new CreateComplaintAction(new ComplaintAnalysisService());

        $this->expectException(\InvalidArgumentException::class);
        $action->execute(['subject' => '   ']);
    }
}
