<?php

namespace Modules\Security\Tests\Security;

use Illuminate\Http\UploadedFile;
use Modules\Security\Support\Validators\SecureFileUploadValidator;
use Tests\TestCase;

class SecureFileUploadValidatorTest extends TestCase
{
    public function test_it_accepts_pdf_uploads(): void
    {
        config(['security.uploads.allowed_mimes' => ['application/pdf']]);
        config(['security.uploads.max_kb' => 5120]);

        $validator = new SecureFileUploadValidator();
        $validator->validate(UploadedFile::fake()->create('permit.pdf', 10, 'application/pdf'));

        $this->assertTrue(true);
    }
}
