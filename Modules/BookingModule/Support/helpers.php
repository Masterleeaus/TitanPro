<?php

if (! function_exists('bookingmodule_manifest_path')) {
    function bookingmodule_manifest_path(string $file): string
    {
        return module_path('BookingModule', 'manifests/' . ltrim($file, '/'));
    }
}
