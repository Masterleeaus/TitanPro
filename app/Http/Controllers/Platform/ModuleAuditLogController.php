<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\TitanModuleAuditLog;
use Inertia\Inertia;
use Inertia\Response;

class ModuleAuditLogController extends Controller
{
    public function index(): Response
    {
        $entries = TitanModuleAuditLog::query()
            ->orderByDesc('created_at')
            ->paginate(50);

        return Inertia::render('Platform/ModuleAuditLog', [
            'entries' => $entries,
        ]);
    }
}
