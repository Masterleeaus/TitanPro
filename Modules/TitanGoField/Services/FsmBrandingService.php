<?php

namespace Modules\TitanGoField\Services;

use Modules\TitanGoField\Models\FsmSetting;

class FsmBrandingService
{
    public function pdfHeader(int $companyId): ?string
    {
        return data_get(FsmSetting::forCompany($companyId)->branding, 'pdf_header');
    }

    public function pdfFooter(int $companyId): ?string
    {
        return data_get(FsmSetting::forCompany($companyId)->branding, 'pdf_footer');
    }

    public function logoUrl(int $companyId): ?string
    {
        $path = data_get(FsmSetting::forCompany($companyId)->branding, 'logo_path');
        return $path ? asset('storage/'.$path) : null;
    }
}
