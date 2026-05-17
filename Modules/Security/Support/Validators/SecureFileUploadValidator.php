<?php

namespace Modules\Security\Support\Validators;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SecureFileUploadValidator
{
    /**
     * Keep the default list intentionally narrow for permit attachments.
     * Add types through config('security.uploads.allowed_mimes') when needed.
     */
    public function validate(UploadedFile $file): void
    {
        if (! $file->isValid()) {
            throw new InvalidArgumentException('Upload failed or was not completed.');
        }

        $maxKb = (int) config('security.uploads.max_kb', 5120);
        if ($file->getSize() > ($maxKb * 1024)) {
            throw new InvalidArgumentException('Uploaded file exceeds the configured security module size limit.');
        }

        $allowed = (array) config('security.uploads.allowed_mimes', [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
        ]);

        $mime = (string) $file->getMimeType();
        if (! in_array($mime, $allowed, true)) {
            throw new InvalidArgumentException('Uploaded file type is not allowed.');
        }

        $extension = Str::lower((string) $file->getClientOriginalExtension());
        $blocked = (array) config('security.uploads.blocked_extensions', [
            'php', 'phtml', 'phar', 'js', 'html', 'htm', 'svg', 'exe', 'sh', 'bat', 'cmd', 'com', 'scr',
        ]);

        if ($extension === '' || in_array($extension, $blocked, true)) {
            throw new InvalidArgumentException('Uploaded file extension is not allowed.');
        }
    }
}
