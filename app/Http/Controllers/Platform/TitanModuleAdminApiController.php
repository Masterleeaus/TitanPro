<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Platform\Concerns\ParsesModuleManifest;
use App\Services\ModuleAuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module as ModulesFacade;
use Nwidart\Modules\Module;

class TitanModuleAdminApiController extends Controller
{
    use ParsesModuleManifest;

    public function index(): JsonResponse
    {
        $modules = collect(ModulesFacade::all())->map(function ($module): array {
            $manifest = $this->manifestFor($module);

            return [
                'name' => $module->getName(),
                'alias' => $manifest['alias'] ?? strtolower($module->getName()),
                'enabled' => (bool) $module->isEnabled(),
                'version' => $manifest['version'] ?? null,
                'description' => $manifest['description'] ?? null,
            ];
        })->values();

        return response()->json(['data' => $modules]);
    }

    public function enable(string $module, ModuleAuditLogger $audit): JsonResponse
    {
        $resolved = $this->resolveModule($module);
        abort_if($resolved === null, 404, 'Module not found.');

        $resolved->enable();
        $audit->logEnable($resolved->getName());

        return response()->json([
            'ok' => true,
            'module' => $resolved->getName(),
            'enabled' => true,
        ]);
    }

    public function disable(string $module, ModuleAuditLogger $audit): JsonResponse
    {
        $resolved = $this->resolveModule($module);
        abort_if($resolved === null, 404, 'Module not found.');

        $resolved->disable();
        $audit->logDisable($resolved->getName());

        return response()->json([
            'ok' => true,
            'module' => $resolved->getName(),
            'enabled' => false,
        ]);
    }

    public function health(string $module): JsonResponse
    {
        $resolved = $this->resolveModule($module);
        abort_if($resolved === null, 404, 'Module not found.');

        Artisan::call('modules:health', [
            '--module' => $resolved->getName(),
            '--json' => true,
        ]);

        $decoded = json_decode(Artisan::output(), true);

        return response()->json([
            'module' => $resolved->getName(),
            'health' => is_array($decoded) ? ($decoded[$resolved->getName()] ?? null) : null,
        ]);
    }

    public function manifests(string $module): JsonResponse
    {
        $resolved = $this->resolveModule($module);
        abort_if($resolved === null, 404, 'Module not found.');

        return response()->json([
            'module' => $resolved->getName(),
            'manifest' => $this->manifestFor($resolved),
        ]);
    }

    public function sync(ModuleAuditLogger $audit): JsonResponse
    {
        Artisan::call('modules:manifest-cache');

        $modules = collect(ModulesFacade::all())->map(function ($module): array {
            $manifest = $this->manifestFor($module);

            return [
                'name' => $module->getName(),
                'alias' => $manifest['alias'] ?? strtolower($module->getName()),
                'enabled' => (bool) $module->isEnabled(),
                'version' => $manifest['version'] ?? null,
                'description' => $manifest['description'] ?? null,
            ];
        })->values();

        $audit->logSync('*');

        return response()->json([
            'ok' => true,
            'data' => $modules,
        ]);
    }

    private function resolveModule(string $identifier): ?Module
    {
        $found = ModulesFacade::find($identifier);

        if ($found !== null) {
            return $found;
        }

        foreach (ModulesFacade::all() as $module) {
            $manifest = $this->manifestFor($module);
            $alias = $manifest['alias'] ?? null;
            if ($alias === $identifier || strtolower($module->getName()) === strtolower($identifier)) {
                return $module;
            }
        }

        return null;
    }
}
