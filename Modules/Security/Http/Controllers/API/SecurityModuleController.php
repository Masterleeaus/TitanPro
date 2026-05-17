<?php

namespace Modules\Security\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Modules\Security\Contracts\Services\SecurityModuleServiceInterface;
use Modules\Security\Http\Resources\SecurityModuleStatusResource;
use Modules\Security\Contracts\Services\OperationalReadinessServiceInterface;

class SecurityModuleController extends Controller
{
    public function __construct(private readonly SecurityModuleServiceInterface $securityModule,
        private readonly OperationalReadinessServiceInterface $readiness)
    {
    }

    public function dashboard()
    {
        return response()->json($this->securityModule->dashboard());
    }

    public function health()
    {
        $health = $this->securityModule->health();

        return response()->json($health, $health['status'] === 'ok' ? 200 : 503);
    }

    public function status()
    {
        return new SecurityModuleStatusResource($this->securityModule->status());
    }

    public function features()
    {
        return response()->json([
            'module' => 'security',
            'features' => $this->securityModule->features(),
        ]);
    }

    public function permissions()
    {
        return response()->json([
            'module' => 'security',
            'permissions' => $this->securityModule->permissions(),
        ]);
    }

    public function diagnostics()
    {
        $diagnostics = $this->securityModule->diagnostics();

        return response()->json($diagnostics, $diagnostics['status'] === 'ok' ? 200 : 503);
    }

    public function readiness()
    {
        $readiness = $this->readiness->report();

        return response()->json($readiness, $readiness['status'] === 'ready' ? 200 : 503);
    }
}
