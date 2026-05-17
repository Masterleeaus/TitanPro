<?php

namespace Modules\Biometric\Tests\Contract;

use PHPUnit\Framework\TestCase;

class BiometricManifestContractTest extends TestCase
{
    public function test_required_manifest_files_exist_and_are_valid_json(): void
    {
        $base = dirname(__DIR__, 2) . '/manifests';

        foreach (['ai_tools.json', 'signals_manifest.json', 'api_manifest.json'] as $file) {
            $path = $base . '/' . $file;
            $this->assertFileExists($path);
            $this->assertIsArray(json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR));
        }
    }

    public function test_ai_tools_manifest_contains_required_tools_and_risk_class(): void
    {
        $manifest = json_decode(
            (string) file_get_contents(dirname(__DIR__, 2) . '/manifests/ai_tools.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $toolIds = array_column($manifest['tools'], 'id');
        $this->assertContains('detect_attendance_anomaly', $toolIds);
        $this->assertContains('predict_overtime_risk', $toolIds);

        foreach ($manifest['tools'] as $tool) {
            $this->assertSame('medium', $tool['risk_class']);
            $this->assertTrue(class_exists($tool['class']));
        }
    }

    public function test_signals_manifest_declares_required_emits_and_consumes(): void
    {
        $manifest = json_decode(
            (string) file_get_contents(dirname(__DIR__, 2) . '/manifests/signals_manifest.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $emits = array_column($manifest['emits'], 'signal');
        $consumes = array_column($manifest['consumes'], 'signal');

        $this->assertContains('biometric.attendance.recorded', $emits);
        $this->assertContains('biometric.attendance.anomaly_detected', $emits);
        $this->assertContains('biometric.overtime.threshold_reached', $emits);
        $this->assertContains('HRCore.EmployeeOnboarded', $consumes);
        $this->assertContains('HRCore.EmployeeOffboarded', $consumes);
    }
}

