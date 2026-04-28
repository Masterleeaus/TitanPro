<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Zeus Dynamic Dashboard — Custom Widgets
    |--------------------------------------------------------------------------
    | Register any class that implements
    | LaraZeus\DynamicDashboard\Contracts\Widget so that the Layout builder
    | exposes it as a block type and the frontend renderer can render it.
    */
    'widgets' => [
        \App\Zeus\Widgets\HtmlCard::class,
    ],
];
