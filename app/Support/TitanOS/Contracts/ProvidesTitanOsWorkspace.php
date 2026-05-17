<?php

namespace App\Support\TitanOS\Contracts;

/**
 * Modules can implement this interface to supply a workspace view for the
 * Business OS shell.  The titanOsWorkspace method should return a fully
 * qualified Blade view name which the OS router will render when the app
 * workspace is requested.
 */
interface ProvidesTitanOsWorkspace
{
    /**
     * Return the name of the workspace view to render for this module.
     *
     * @return string
     */
    public static function titanOsWorkspace(): string;
}