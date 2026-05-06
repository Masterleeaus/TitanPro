<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Zeus Dynamic Dashboard — Custom Widgets
    |--------------------------------------------------------------------------
    | Register any class that implements
    | LaraZeus\DynamicDashboard\Contracts\Widget so that the Layout builder
    | exposes it as a block type and the frontend renderer can render it.
    |
    | Widget classes live in app/Zeus/Widgets/.
    */
    'widgets' => [
        // ── Content ─────────────────────────────────────────────────────────
        \App\Zeus\Widgets\HtmlCard::class,
        \App\Zeus\Widgets\MarkdownCard::class,

        // ── Stats & Metrics ──────────────────────────────────────────────────
        \App\Zeus\Widgets\StatsCard::class,
        \App\Zeus\Widgets\MetricCard::class,
        \App\Zeus\Widgets\KpiGridCard::class,

        // ── Visual ───────────────────────────────────────────────────────────
        \App\Zeus\Widgets\IconCard::class,
        \App\Zeus\Widgets\ImageCard::class,
        \App\Zeus\Widgets\ChartPlaceholderCard::class,

        // ── Navigation / Action ──────────────────────────────────────────────
        \App\Zeus\Widgets\LinkCard::class,
        \App\Zeus\Widgets\CtaButtonCard::class,

        // ── Data ─────────────────────────────────────────────────────────────
        \App\Zeus\Widgets\TableListCard::class,
        \App\Zeus\Widgets\RecentActivityCard::class,

        // ── Identity / Notifications ─────────────────────────────────────────
        \App\Zeus\Widgets\UserProfileCard::class,
        \App\Zeus\Widgets\AlertNoticeCard::class,
    ],
];
