<?php

namespace Modules\Security\Http\Controllers\Internal;

use Illuminate\Routing\Controller;
use Modules\Security\Support\Diagnostics\SecurityStructureAudit;

class SecurityStructureController extends Controller
{
    public function __invoke(SecurityStructureAudit $audit)
    {
        $result = $audit->scan();

        return response()->json($result, $result['status'] === 'ok' ? 200 : 500);
    }
}
