<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Illuminate\Support\Facades\Storage;

/**
 * Custom ThemeManager page that fixes the zip upload path bug in
 * alizharb/filament-themes-manager v1.x.
 *
 * The package's FileUpload component stores the uploaded file using a relative
 * Storage-disk path (e.g. "themes/uploads/abc.zip"). The service's
 * installThemeFromZip() then calls File::exists($zipPath) on that relative
 * string, which resolves against the OS working directory — not the Storage
 * disk root — so it always returns false and installation silently fails.
 *
 * The fix: intercept handleInstall(), resolve the relative path to an absolute
 * filesystem path via Storage::disk()->path(), then hand off to the parent.
 */
class ThemeManager extends \Alizharb\FilamentThemesManager\Pages\ThemeManager
{
    protected function handleInstall(array $data): void
    {
        if (($data['source'] ?? 'zip') === 'zip' && isset($data['zip'])) {
            $disk     = config('filament-themes-manager.upload_disk', 'public');
            $data['zip'] = Storage::disk($disk)->path($data['zip']);
        }

        parent::handleInstall($data);
    }
}
