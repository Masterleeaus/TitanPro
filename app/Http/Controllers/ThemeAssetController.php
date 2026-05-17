<?php

namespace App\Http\Controllers;

use App\Support\ThemeRuntime;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ThemeAssetController
{
    public function __invoke(string $slug, string $path): BinaryFileResponse|Response
    {
        if (! in_array($slug, ThemeRuntime::installedThemes(), true)) {
            abort(404);
        }

        $path = str_replace(['..', '\\'], ['', '/'], $path);
        $file = ThemeRuntime::themePath($slug) . DIRECTORY_SEPARATOR . ltrim($path, '/');

        if (! File::exists($file) || ! File::isFile($file)) {
            abort(404);
        }

        return response()->file($file, ['Cache-Control' => 'public, max-age=31536000']);
    }
}
