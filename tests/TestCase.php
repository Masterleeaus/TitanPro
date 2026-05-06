<?php

namespace Tests;

use App\Services\SmsService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        // Prevent real Twilio calls during tests; individual tests may override.
        $this->app->bind(SmsService::class, fn () => new class implements SmsService
        {
            public function send(string $to, string $message): void {}
        });

        // assertDenied() — accepts either 403 (explicit policy rejection) or 404
        // (resource hidden by TenantScope / route model binding).  Both status
        // codes confirm that cross-tenant access is blocked.
        TestResponse::macro('assertDenied', function (): TestResponse {
            /** @var TestResponse $this */
            Assert::assertContains(
                $this->getStatusCode(),
                [403, 404],
                "Expected a denied response (403 or 404), but got HTTP {$this->getStatusCode()}."
            );

            return $this;
        });
    }
}
