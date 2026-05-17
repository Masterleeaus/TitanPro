<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\TitanGoActionBridge;
use Modules\TitanEchoAssist\Services\WorkcoreWorkerDataService;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class TitanGoActionBridgeTest extends TestCase
{
    public function test_mutating_action_requires_confirmation_in_preview_mode(): void
    {
        $bridge = new TitanGoActionBridge(new FakeWorkcoreWorkerDataService());

        $payload = $bridge->dispatch('site_diary.create', [
            'has_titango_license' => true,
            'job_id' => 99,
            'company_id' => 77,
            'preview_only' => true,
        ]);

        $this->assertTrue($payload['requiresConfirmation']);
        $this->assertNull($payload['result']);
    }

    public function test_non_entitled_context_throws_authorization_exception(): void
    {
        $bridge = new TitanGoActionBridge(new FakeWorkcoreWorkerDataService());

        $this->expectException(RuntimeException::class);

        $bridge->dispatch('job.summary', [
            'has_titango_license' => false,
            'job_id' => 99,
            'company_id' => 77,
        ]);
    }
}

class FakeWorkcoreWorkerDataService extends WorkcoreWorkerDataService
{
    public function getJobDetails(int $jobId, int $companyId): array
    {
        return ['id' => $jobId, 'status' => 'in_progress'];
    }

    public function getJobChecklist(int $jobId, int $companyId): array
    {
        return [
            ['id' => 1, 'completed_at' => '2026-05-17 10:00:00'],
            ['id' => 2, 'completed_at' => null],
        ];
    }

    public function createSiteDiaryEntry(int $jobId, int $companyId, string $content): array
    {
        return [
            'id' => 123,
            'job_id' => $jobId,
            'organization_id' => $companyId,
            'event' => 'diary',
            'body' => $content,
        ];
    }
}
