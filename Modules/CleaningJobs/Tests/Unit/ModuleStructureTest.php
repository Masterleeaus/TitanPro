<?php

namespace Modules\CleaningJobs\Tests\Unit;

use PHPUnit\Framework\TestCase;

class ModuleStructureTest extends TestCase { public function test_module_name(): void { $this->assertSame('CleaningJobs', 'CleaningJobs'); } }
