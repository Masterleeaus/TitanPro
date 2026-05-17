<?php

namespace App\Providers;

use App\Support\ThemeRuntime;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ThemeRuntimeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $slug = ThemeRuntime::activeThemeSlug();

        if (! $slug) {
            return;
        }

        $themePath = ThemeRuntime::themePath($slug);
        $viewsPath = $themePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views';

        if (File::isDirectory($viewsPath)) {
            View::addLocation($viewsPath);
        }

        foreach (['vendor', 'vendor_'] as $vendorFolder) {
            $vendorPath = $viewsPath . DIRECTORY_SEPARATOR . $vendorFolder;

            if (! File::isDirectory($vendorPath)) {
                continue;
            }

            foreach (File::directories($vendorPath) as $namespacePath) {
                View::prependNamespace(basename($namespacePath), $namespacePath);
            }
        }
    }
}
