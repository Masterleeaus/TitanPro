<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Upload Disk
    |--------------------------------------------------------------------------
    | The Storage disk used to temporarily hold uploaded theme zip files.
    */
    'upload_disk' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Upload Directory
    |--------------------------------------------------------------------------
    | Subdirectory on the upload disk where zip files are stored temporarily.
    */
    'upload_directory' => 'themes/uploads',

    /*
    |--------------------------------------------------------------------------
    | Protected Themes
    |--------------------------------------------------------------------------
    | Theme slugs that cannot be deleted or deactivated via the UI.
    */
    'protected_themes' => [],

    /*
    |--------------------------------------------------------------------------
    | Theme Preview
    |--------------------------------------------------------------------------
    */
    'preview' => [
        'enabled' => false,
    ],
];
