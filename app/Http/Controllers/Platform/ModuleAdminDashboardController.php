<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nwidart\Modules\Facades\Module as ModulesFacade;

class ModuleAdminDashboardController extends Controller
{
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

    private function manifestFor(mixed $module): array
    {
        $manifest = $module->json()->toArray();

        return is_array($manifest) ? $manifest : [];
    }
}
