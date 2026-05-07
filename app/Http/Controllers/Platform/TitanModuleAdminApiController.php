<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Services\ModuleAuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;

class TitanModuleAdminApiController extends Controller
{
    public function index(): JsonResponse
    {
        $modules = collect(Module::all())->map(function ($module): array {
            $manifest = is_array($module->json()->toArray()) ? $module->json()->toArray() : [];

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

    private function resolveModule(string $identifier): mixed
    {
        $found = Module::find($identifier);

        if ($found !== null) {
            return $found;
        }

        foreach (Module::all() as $module) {
            $manifest = is_array($module->json()->toArray()) ? $module->json()->toArray() : [];
            $alias = $manifest['alias'] ?? null;
            if ($alias === $identifier || strtolower($module->getName()) === strtolower($identifier)) {
                return $module;
            }
        }

        return null;
    }
}
