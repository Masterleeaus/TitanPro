<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\WorkcorePortalDataService;
use Modules\TitanEchoAssist\Support\WorkcoreSchemaMap;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class WorkcorePortalDataServiceContractTest extends TestCase
{
    public function test_workcore_portal_data_service_exposes_required_api(): void
    {
        $reflection = new ReflectionClass(WorkcorePortalDataService::class);

        foreach ([
            'getUpcomingVisits',
            'getPastVisits',
            'getInvoices',
            'getOutstandingBalance',
            'getQuotes',
            'getServiceIssues',
            'getJobChecklist',
            'getJobTimeline',
            'getCustomerProfile',
            'getSiteProfile',
        ] as $method) {
            $this->assertTrue($reflection->hasMethod($method), "Missing method: {$method}");
        }
    }

    public function test_workcore_schema_map_points_to_titanpro_tables(): void
    {
        $this->assertSame('field_jobs', WorkcoreSchemaMap::JOBS_TABLE);
        $this->assertSame('customers', WorkcoreSchemaMap::CUSTOMERS_TABLE);
        $this->assertSame('invoices', WorkcoreSchemaMap::INVOICES_TABLE);
        $this->assertSame('estimates', WorkcoreSchemaMap::QUOTES_TABLE);
    }
}
