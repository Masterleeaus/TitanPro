<?php

declare(strict_types=1);

namespace App\Extensions\TitanCommand\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class JobManagerImportController extends Controller
{

    private function tenantIds(): array
    {
        $userId = (int) (\Illuminate\Support\Facades\Auth::id() ?? 0);
        // MVP rule: company_id == user_id
        $companyId = $userId;
        return [$companyId, $userId];
    }

    public function index(Request $request)
    {
        return view('titancommand::job-manager.imported', [
            'title' => 'Jobs Manager (Imported)',
        ]);
    }
}
