<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Platform\Concerns\ParsesModuleManifest;
use Inertia\Inertia;
use Inertia\Response;
use Nwidart\Modules\Facades\Module as ModulesFacade;

class ModuleAdminDashboardController extends Controller
{
    use ParsesModuleManifest;

    public function index(): Response
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

        return Inertia::render('Platform/ModuleDashboard', [
            'modules' => $modules,
        ]);
    }
}
