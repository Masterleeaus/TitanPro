<?php

namespace Modules\Payroll\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\Payroll\Contracts\Services\PayslipAccessLinkServiceContract;

class PayslipSecureDownloadController extends Controller
{
    public function __invoke(string $token, PayslipAccessLinkServiceContract $links)
    {
        $payload = $links->verify($token);
        $path = $payload['path'] ?? null;

        abort_if(! $path || ! Storage::exists($path), 404, 'Payslip not found.');

        return Storage::download($path, 'payslip-'.$payload['salary_slip_id'].'.pdf');
    }
}
