<?php

namespace Modules\TitanRewind\Tests\Unit;

use PHPUnit\Framework\TestCase;

class MigrationTenantBoundaryTest extends TestCase
{
    public function test_all_rewind_migrations_include_company_id_column(): void
    {
        $migrationDir = dirname(__DIR__, 2).'/Database/Migrations';
        $migrations = glob($migrationDir.'/*.php') ?: [];

        $this->assertCount(4, $migrations);

        foreach ($migrations as $migration) {
            $contents = (string) file_get_contents($migration);
            $this->assertStringContainsString("'company_id'", $contents, basename($migration).' must include company_id');
        }
    }
}
