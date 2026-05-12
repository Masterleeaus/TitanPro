<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Modules\CallingAgent\Models\CallingAgentConfig;
use Modules\CallingAgent\Services\CallingAgentCredentialResolver;
use Modules\CallingAgent\Support\TenantContext;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CallingAgentCredentialResolverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('calling_agent_configs')) {
            $migrationFiles = glob(base_path('Modules/CallingAgent/Database/Migrations/*create_calling_agent_configs_table.php')) ?: [];
            $this->assertNotEmpty($migrationFiles, 'Could not find CallingAgent configs migration file.');

            $migrationPath = str_replace(base_path().'/', '', $migrationFiles[0]);

            $this->artisan('migrate', ['--path' => $migrationPath])->assertExitCode(0);
        }

        CallingAgentConfig::query()->withoutGlobalScopes()->truncate();
    }

    protected function tearDown(): void
    {
        TenantContext::clear();

        parent::tearDown();
    }

    #[Test]
    public function it_resolves_elevenlabs_api_key_per_company_id(): void
    {
        CallingAgentConfig::create([
            'company_id' => 101,
            'elevenlabs_api_key' => 'key-101',
        ]);

        CallingAgentConfig::create([
            'company_id' => 202,
            'elevenlabs_api_key' => 'key-202',
        ]);

        $resolver = new CallingAgentCredentialResolver();

        TenantContext::setTenantId(101);
        $this->assertSame('key-101', $resolver->elevenLabsApiKey());

        TenantContext::setTenantId(202);
        $this->assertSame('key-202', $resolver->elevenLabsApiKey());
    }

    #[Test]
    public function it_resolves_sip_credentials_per_company_id(): void
    {
        CallingAgentConfig::create([
            'company_id' => 303,
            'sip_username' => 'sip-user-303',
            'sip_password' => 'sip-pass-303',
            'sip_domain' => 'sip.tenant303.example',
        ]);

        CallingAgentConfig::create([
            'company_id' => 404,
            'sip_username' => 'sip-user-404',
            'sip_password' => 'sip-pass-404',
            'sip_domain' => 'sip.tenant404.example',
        ]);

        $resolver = new CallingAgentCredentialResolver();

        TenantContext::setTenantId(303);
        $this->assertSame([
            'username' => 'sip-user-303',
            'password' => 'sip-pass-303',
            'domain' => 'sip.tenant303.example',
        ], $resolver->sipCredentials());

        TenantContext::setTenantId(404);
        $this->assertSame([
            'username' => 'sip-user-404',
            'password' => 'sip-pass-404',
            'domain' => 'sip.tenant404.example',
        ], $resolver->sipCredentials());
    }
}
