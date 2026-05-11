@php
    $userId = auth()->id();
    $plan = Auth::user()->activePlan();
    $plan_type = 'regular';
    $reportsUrl = \Illuminate\Interactions\Facades\Route::has('dashboard.user.analytical-engine.index')
        ? route('dashboard.user.analytical-engine.index')
        : '#';

    $boardActionUrl = function (string $key, string $mode = 'modal') {
        if (\Illuminate\Interactions\Facades\Route::has('dashboard.user.board.action')) {
            return route('dashboard.user.board.action', ['key' => $key, 'mode' => $mode]);
        }
        return '#';
    };

    // $team = Auth::user()->getAttribute('team');
    $teamManager = Auth::user()->getAttribute('teamManager');

    if ($plan != null) {
        $plan_type = strtolower($plan->plan_type);
    }

    $activeHub = 'connect';

    
$titlebar_links = [
    [
        'label' => 'Command Centre',
        'link' => url('/dashboard?hub=home'),
        'key' => 'home',
    ],
    [
        'label' => 'Connect Core',
        'link' => url('/dashboard?hub=connect'),
        'key' => 'connect',
    ],
    [
        'label' => 'Service Console',
        'link' => url('/dashboard?hub=service_console'),
        'key' => 'service_console',
    ],
    [
        'label' => 'Work Hub',
        'link' => url('/dashboard?hub=work'),
        'key' => 'work',
    ],
    [
        'label' => 'Team Portal',
        'link' => url('/dashboard?hub=team'),
        'key' => 'team',
    ],
    [
        'label' => 'Supply Depot',
        'link' => url('/dashboard?hub=supply'),
        'key' => 'supply',
    ],
    [
        'label' => 'Money Manager',
        'link' => url('/dashboard?hub=money'),
        'key' => 'money',
    ],
    [
        'label' => 'Trust Vault',
        'link' => url('/dashboard?hub=trust_vault'),
        'key' => 'trust_vault',
    ],
];

    $premium_features = \App\Models\OpenAIGenerator::query()->where('active', 1)->where('premium', 1)->limit(5)->get()->pluck('title')->toArray();
    $user_is_premium = false;
    $plan = auth()->user()?->relationPlan;
    if ($plan) {
        $planType = strtolower($plan->plan_type ?? 'all');
        if ($plan->plan_type === 'all' || $plan->plan_type === 'premium') {
            $user_is_premium = true;
        }
    }

    $style_string = '';

    if (setting('announcement_background_color')) {
        $style_string .= '.lqd-card.lqd-announcement-card { background-color: ' . setting('announcement_background_color') . ';}';
    }

    if (setting('announcement_background_image')) {
        $style_string .= '.lqd-card.lqd-announcement-card { background-image: url(' . setting('announcement_background_image') . '); }';
    }

    if (setting('announcement_background_color_dark')) {
        $style_string .= '.theme-dark .lqd-card.lqd-announcement-card { background-color: ' . setting('announcement_background_color_dark') . ';}';
    }

    if (setting('announcement_background_image_dark')) {
        $style_string .= '.theme-dark .lqd-card.lqd-announcement-card { background-image: url(' . setting('announcement_background_image_dark') . '); }';
    }

    $favoriteOpenAis = cache("user:{$userId}:favorite_openai");
@endphp

@if (filled($style_string))
    @push('css')
        <style>
            {{ $style_string }}
        
/* --- Titan: Map preview card --- */
.tz-map-mini{position:relative;height:220px;border-radius:16px;overflow:hidden;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06)}
.tz-map-grid{position:absolute;inset:0;background:
    linear-gradient(to right, rgba(255,255,255,.06) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(255,255,255,.06) 1px, transparent 1px);
    background-size:28px 28px;opacity:.35}
.tz-map-dot{position:absolute;width:10px;height:10px;border-radius:999px;background:rgba(99,102,241,.95);box-shadow:0 0 0 6px rgba(99,102,241,.18)}
.tz-dot-1{left:26%;top:34%}
.tz-dot-2{left:62%;top:48%;background:rgba(34,197,94,.95);box-shadow:0 0 0 6px rgba(34,197,94,.18)}
.tz-dot-3{left:45%;top:70%;background:rgba(245,158,11,.95);box-shadow:0 0 0 6px rgba(245,158,11,.18)}
.tz-map-footer{position:absolute;left:0;right:0;bottom:0;padding:10px 12px;background:linear-gradient(to top, rgba(0,0,0,.45), rgba(0,0,0,0))}


/* --- Titan: Premium calendar widget --- */
.tz-premcal{padding-top:6px}
.tz-premcal-top{display:flex;align-items:baseline;justify-content:space-between;margin-bottom:10px}
.tz-premcal-title{font-weight:700;font-size:16px;color:#e5e7eb}
.tz-premcal-sub{font-size:12px;color:rgba(229,231,235,.65)}
.tz-premcal-grid{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:10px;text-decoration:none}
.tz-premcal-cell{border:1px solid rgba(255,255,255,.06);background:rgba(0,0,0,.10);border-radius:12px;padding:10px 8px;position:relative;overflow:hidden}
.tz-premcal-cell:before{content:"";position:absolute;inset:0;background:linear-gradient(135deg, rgba(59,130,246,.20), rgba(34,197,94,.05));opacity:.25}
.tz-premcal-dow{position:relative;font-size:12px;color:rgba(229,231,235,.7)}
.tz-premcal-dom{position:relative;margin-top:2px;font-size:16px;font-weight:700;color:#fff}
.tz-premcal-badge{position:relative;margin-top:8px;display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:22px;border-radius:999px;font-size:12px;font-weight:700;background:rgba(148,163,184,.18);color:rgba(226,232,240,.95)}
.tz-premcal-badge.is-hot{background:rgba(59,130,246,.28);color:#dbeafe}
.tz-premcal-note{margin-top:10px;font-size:12px;color:rgba(229,231,235,.6)}
@media (max-width: 1024px){
  .tz-premcal-grid{grid-template-columns:repeat(4,minmax(0,1fr))}
}


/* Titan BOS hub rail merged from Bolt slim menu concept */
.titan-bos-shell{display:flex;gap:1.25rem;align-items:flex-start}
.titan-hub-rail-wrap{position:sticky;top:5.5rem;align-self:flex-start;z-index:5}
.titan-hub-rail{width:72px;border-radius:18px;background:rgba(15,23,42,.88);border:1px solid rgba(148,163,184,.16);backdrop-filter: blur(14px);padding:12px 10px;box-shadow:0 20px 40px rgba(15,23,42,.18)}
.titan-hub-rail-head{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:rgba(226,232,240,.55);text-align:center;margin-bottom:10px}
.titan-hub-rail-nav{display:flex;flex-direction:column;gap:8px}
.titan-hub-rail-link{display:flex;align-items:center;justify-content:center;width:100%;height:46px;border-radius:14px;color:rgba(226,232,240,.82);text-decoration:none;position:relative;transition:all .18s ease;background:rgba(255,255,255,.03)}
.titan-hub-rail-link:hover{background:rgba(255,255,255,.08);color:#fff;transform:translateY(-1px)}
.titan-hub-rail-link.is-active{background:linear-gradient(135deg,rgba(59,130,246,.28),rgba(99,102,241,.22));border:1px solid rgba(99,102,241,.35);color:#fff}
.titan-hub-rail-icon{font-size:14px;line-height:1}
.titan-hub-rail-text{position:absolute;left:calc(100% + 12px);top:50%;transform:translateY(-50%);white-space:nowrap;padding:8px 10px;border-radius:10px;background:rgba(15,23,42,.96);border:1px solid rgba(148,163,184,.16);opacity:0;pointer-events:none;transition:opacity .18s ease;color:#fff;font-size:12px}
.titan-hub-rail-link:hover .titan-hub-rail-text,.titan-hub-rail-link.is-active .titan-hub-rail-text{opacity:1}
.titan-bos-main{min-width:0;flex:1}

</style>
    
    <script>
        window.titanRunAction = function(intent) {
            window.dispatchEvent(new CustomEvent('titan:action', { detail: { intent } }));
        };

        window.addEventListener('titan:action', function(e) {
            const intent = e.detail?.intent;
            const routes = {
                quick_assign: '{{ $jobsListUrl }}',
                quick_schedule: '{{ $scheduleListUrl }}',
                checklist_run: '{{ $agentsListUrl !== '#' ? $agentsListUrl : $jobsListUrl }}',
                dispatch_plan: '{{ $scheduleListUrl !== '#' ? $scheduleListUrl : $jobsListUrl }}',
                staffing_optimize: '{{ $jobsListUrl }}',
                overdue_recovery: '{{ $jobsListUrl }}',
                inspection_run: '{{ $agentsListUrl !== '#' ? $agentsListUrl : $jobsListUrl }}',
                recurring_setup: '{{ $scheduleListUrl !== '#' ? $scheduleListUrl : $jobsListUrl }}',
                customer_intake: '{{ \Illuminate\Interactions\Facades\Route::has('dashboard.user.customers.index') ? route('dashboard.user.customers.index') : '#' }}',
                open_activity: '#activity',
                open_jobs_today: '{{ $jobsListUrl }}',
                open_jobs_completed: '{{ $jobsListUrl }}',
                open_schedule: '{{ $scheduleListUrl }}',
                find_schedule_gaps: '{{ $scheduleListUrl }}',
                launch_existing_job_wizard: '#account-summary',
                launch_quote_card: '#templates',
            };
            const target = routes[intent] || '#';
            if (!target || target === '#') return;
            if (target.startsWith('#')) {
                const el = document.querySelector(target);
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }
            window.location.href = target;
        });
    </script>

@endpush
@endif

@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Dashboard'))
@section('titlebar_title')
    {{ __('Welcome') }}, {{ auth()->user()?->name }}.
        </div>
@endsection
@section('titlebar_after')
    <ul class="lqd-filter-list mt-1 flex list-none flex-wrap items-center gap-x-4 gap-y-2 text-heading-foreground max-sm:gap-3">
        @foreach ($titlebar_links as $link)
            <li>
                <x-button
                    @class([
                        'lqd-filter-btn inline-flex px-2.5 py-0.5 text-2xs leading-tight transition-colors hover:translate-y-0 hover:bg-foreground/5 [&.active]:bg-foreground/5',
                        'active' => ($link['key'] ?? null) === $activeHub,
                    ])
                    variant="ghost"
                    href="{{ $link['link'] }}"
                >
                    @lang($link['label'])
                </x-button>
            </li>
        @endforeach
    </ul>
@endsection

@section('content')
    <div class="flex flex-wrap justify-between gap-8 py-5 titan-bos-shell">
        @includeIf('dashboard_support.shared.bolt_slim_menu.hub-rail', ['activeHub' => $activeHub, 'hubLinks' => $titlebar_links])
        <div class="titan-bos-main">
        <!-- start: landing badge -->
        <div
            class="grid w-full grid-cols-1 gap-10"
            id="all"
        >
            @if (setting('announcement_active', 0) && !auth()->user()?->dash_notify_seen)
                <div
                    class="lqd-announcement"
                    data-name="{{ \App\Enums\Introduction::DASHBOARD_FIRST }}"
                    x-data="{ show: true }"
                    x-ref="announcement"
                >
                    <script>
                        const announcementDismissed = localStorage.getItem('lqd-announcement-dismissed');
                        if (announcementDismissed) {
                            document.querySelector('.lqd-announcement').style.display = 'none';
                        }
                    </script>

                    <x-card
                        class="lqd-announcement-card relative bg-cover bg-center"
                        size="lg"
                        x-ref="announcementCard"
                    >
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h3 class="mb-3">
                                    @lang(setting('announcement_title', 'Welcome'))
                                </h3>
                                <p class="mb-4">
                                    @lang(setting('announcement_description', 'We are excited to have you here. Explore the marketplace to find the best AI models for your needs.'))
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <x-button
                                        class="font-medium"
                                        href="{{ setting('announcement_url', '#') }}"
                                    >
                                        <x-tabler-plus class="size-4" />
                                        {{ setting('announcement_button_text', 'Try it Now') }}
                                    </x-button>
                                    <x-button
                                        class="font-medium"
                                        href="javascript:void(0)"
                                        variant="ghost-shadow"
                                        hover-variant="danger"
                                        @click.prevent="{{ $app_is_demo ? 'toastr.info(\'This feature is disabled in Demo version.\')' : ' dismiss()' }}"
                                    >
                                        @lang('Dismiss')
                                    </x-button>
                                </div>
                            </div>
                            @if (setting('announcement_image_dark'))
                                <img
                                    class="announcement-img announcement-img-dark peer hidden w-28 shrink-0 dark:block"
                                    src="{{ setting('announcement_image_dark', '/upload/images/speaker.png') }}"
                                    alt="@lang(setting('announcement_title', 'Welcome to MagicAI!'))"
                                >
                            @endif
                            <img
                                class="announcement-img announcement-img-light w-28 shrink-0 dark:peer-[&.announcement-img-dark]:hidden"
                                src="{{ setting('announcement_image', '/upload/images/speaker.png') }}"
                                alt="@lang(setting('announcement_title', 'Welcome to MagicAI!'))"
                            >
                        </div>
                    </x-card>
                </div>
            @endif
            <x-card
                class:body="max-sm:p-7"
                data-name="{{ \App\Enums\Introduction::DASHBOARD_TWO }}"
                size="lg"
            >
                <h3 class="mb-6 flex items-center gap-3">
                    {{-- blade-formatter-disable --}}
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd"
							  d="M18.7588 7.85618L17.1437 8.18336V8.18568C16.3659 8.34353 15.6517 8.72701 15.0905 9.28825C14.5292 9.8495 14.1458 10.5636 13.9879 11.3415L13.6607 12.9565C13.6262 13.1155 13.5383 13.2578 13.4117 13.3599C13.285 13.462 13.1273 13.5177 12.9646 13.5177C12.8019 13.5177 12.6442 13.462 12.5175 13.3599C12.3909 13.2578 12.303 13.1155 12.2685 12.9565L11.9413 11.3415C11.7837 10.5635 11.4003 9.84922 10.839 9.28793C10.2777 8.72663 9.56345 8.34324 8.78546 8.18568L7.17042 7.8585C7.00937 7.82552 6.86464 7.73795 6.76071 7.61058C6.65678 7.48321 6.60001 7.32386 6.60001 7.15946C6.60001 6.99507 6.65678 6.83572 6.76071 6.70835C6.86464 6.58098 7.00937 6.4934 7.17042 6.46043L8.78546 6.13324C9.56339 5.97554 10.2776 5.5921 10.8389 5.03084C11.4001 4.46957 11.7836 3.75536 11.9413 2.97743L12.2685 1.36239C12.303 1.20344 12.3909 1.06109 12.5175 0.959015C12.6442 0.856935 12.8019 0.80127 12.9646 0.80127C13.1273 0.80127 13.285 0.856935 13.4117 0.959015C13.5383 1.06109 13.6262 1.20344 13.6607 1.36239L13.9879 2.97743C14.1458 3.75529 14.5292 4.46943 15.0905 5.03067C15.6517 5.59192 16.3659 5.9754 17.1437 6.13324L18.7588 6.45811C18.9198 6.49108 19.0645 6.57866 19.1685 6.70603C19.2724 6.8334 19.3292 6.99275 19.3292 7.15714C19.3292 7.32154 19.2724 7.48089 19.1685 7.60826C19.0645 7.73563 18.9198 7.8232 18.7588 7.85618ZM6.94895 16.0393L6.51038 16.1286C5.96946 16.2383 5.47282 16.5037 5.08244 16.8939C4.69206 17.2841 4.42523 17.7806 4.31524 18.3214L4.2259 18.76C4.202 18.8835 4.13584 18.9949 4.03877 19.075C3.9417 19.1551 3.81978 19.1989 3.69394 19.1989C3.56809 19.1989 3.44617 19.1551 3.3491 19.075C3.25204 18.9949 3.18587 18.8835 3.16197 18.76L3.07263 18.3214C2.96278 17.7805 2.69599 17.2839 2.30559 16.8937C1.91518 16.5035 1.41847 16.237 0.877485 16.1274L0.43892 16.0381C0.315366 16.0142 0.203985 15.948 0.123895 15.851C0.0438042 15.7539 0 15.632 0 15.5061C0 15.3803 0.0438042 15.2584 0.123895 15.1613C0.203985 15.0642 0.315366 14.9981 0.43892 14.9742L0.877485 14.8848C1.41862 14.7752 1.91545 14.5085 2.30587 14.1181C2.69629 13.7276 2.96299 13.2308 3.07263 12.6897L3.16197 12.2511C3.18587 12.1276 3.25204 12.0162 3.3491 11.9361C3.44617 11.856 3.56809 11.8122 3.69394 11.8122C3.81978 11.8122 3.9417 11.856 4.03877 11.9361C4.13584 12.0162 4.202 12.1276 4.2259 12.2511L4.31524 12.6897C4.42482 13.231 4.69148 13.728 5.08189 14.1186C5.4723 14.5092 5.96915 14.7761 6.51038 14.886L6.94895 14.9753C7.0725 14.9992 7.18388 15.0654 7.26397 15.1625C7.34407 15.2595 7.38787 15.3814 7.38787 15.5073C7.38787 15.6331 7.34407 15.7551 7.26397 15.8521C7.18388 15.9492 7.0725 16.0154 6.94895 16.0393Z"
							  fill="url(#paint0_linear_213_525)"/>
						<defs>
							<linearGradient id="paint0_linear_213_525" x1="1.1976e-07" y1="4.55439" x2="15.5124" y2="18.9291"
											gradientUnits="userSpaceOnUse">
								<stop stop-color="#82E2F4"/>
								<stop offset="0.502" stop-color="#8A8AED"/>
								<stop offset="1" stop-color="#6977DE"/>
							</linearGradient>
						</defs>
					</svg>
					{{-- blade-formatter-enable --}}
                    @lang('Hey, how can I help you grow your customers?')
                </h3>
                <p class="mb-3 text-sm text-heading-foreground/70">{{ __('Type a command or launch a customer workflow.') }}</p>
                <x-header-search
                    class="mb-2 w-full"
                    class:input="bg-background border-none h-12 text-heading-foreground shadow-[0_4px_8px_rgba(0,0,0,0.05)] placeholder:text-heading-foreground"
                    size="lg"
                    placeholder="{{ __('Search customers, leads, campaigns, SMS, calls…') }}"
                    :show-arrow=false
                    :show-icon=false
                    :show-kbd=false
                    :outline-glow=true
                />
                <div class="mb-1 flex flex-wrap gap-2 text-2xs text-heading-foreground/60">
                    <span class="rounded-full bg-background/40 px-3 py-1">{{ __('Add a new lead from today's enquiry') }}</span>
                    <span class="rounded-full bg-background/40 px-3 py-1">{{ __('Show follow-ups due today') }}</span>
                    <span class="rounded-full bg-background/40 px-3 py-1">{{ __('Which campaigns are active this week?') }}</span>
                </div>

<div class="mt-4 flex flex-wrap items-center gap-2">
    <x-button variant="primary" href="{{ $boardActionUrl('wizard.jobs.create', 'modal') }}">
        <x-tabler-briefcase class="size-4" />
        <span class="font-semibold">{{ __('Add Customer') }}</span>
    </x-button>

    <x-button variant="outline" href="{{ $boardActionUrl('wizard.jobs.quote_to_job', 'modal') }}">
        <x-tabler-file-description class="size-4" />
        <span class="font-semibold">{{ __('Add Lead') }}</span>
    </x-button>

    <x-button variant="outline" href="{{ $boardActionUrl('wizard.dispatch.today', 'modal') }}">
        <x-tabler-route class="size-4" />
        <span class="font-semibold">{{ __('Create Campaign') }}</span>
    </x-button>

    <x-button variant="outline" href="{{ $boardActionUrl('wizard.staffing.check', 'modal') }}">
        <x-tabler-users class="size-4" />
        <span class="font-semibold">{{ __('Send SMS') }}</span>
    </x-button>

    <x-button variant="outline" href="{{ $boardActionUrl('wizard.checklist.builder', 'modal') }}">
        <x-tabler-checklist class="size-4" />
        <span class="font-semibold">{{ __('Call Customer') }}</span>
    </x-button>

    <x-button variant="link" class="ms-auto text-[12px] font-medium text-foreground"
        href="{{ $boardActionUrl('wizard.jobs.create', 'modal') }}">
        {{ __('More Actions') }}
        <span class="inline-flex size-9 items-center justify-center rounded-button bg-background shadow transition-all hover:scale-110 hover:bg-heading-foreground hover:text-header-background">
            <x-tabler-plus class="size-4" />
        </span>
    </x-button>
</div>
</x-card>
        </div>
        <!-- end: landing badge -->

        
@php
    $tenantUserId = \Illuminate\Interactions\Facades\Auth::id();
    $tenantTeamId = optional(\Illuminate\Interactions\Facades\Auth::user())->team_id;
    $companyId = optional(\Illuminate\Interactions\Facades\Auth::user())->company_id ?? 1;

    $hasTable = fn ($t) => \Illuminate\Interactions\Facades\Schema::hasTable($t);
    $hasCol = fn ($t, $c) => $hasTable($t) && \Illuminate\Interactions\Facades\Schema::hasColumn($t, $c);

    $kpi = [
        'revenue_30d' => 0,
        'revenue_30d_fmt' => '$0',
        'rev_per_labor_hr' => 0,
        'rev_per_labor_hr_fmt' => '$0/hr',
        'gross_margin_pct' => null,
        'efficiency_pct' => null,
        'utilization_pct' => null,
        'churn_pct' => null,
        'series_revenue_months' => [],
        'series_jobs_7d' => [],
    ];

    try {
        $now = now();
        $from30 = $now->copy()->subDays(30);
        $from6m = $now->copy()->subMonths(5)->startOfMonth();
        $from7d = $now->copy()->subDays(6)->startOfDay();

        // Revenue (paid invoices last 30 days)
        if ($hasTable('tz_invoices')) {
            $q = \Illuminate\Interactions\Facades\DB::table('tz_invoices');
            if ($hasCol('tz_invoices','user_id')) $q->where('user_id', $tenantUserId);
            if ($hasCol('tz_invoices','company_id')) $q->where('company_id', $companyId);

            $paidCol = $hasCol('tz_invoices','paid_at') ? 'paid_at' : ($hasCol('tz_invoices','updated_at') ? 'updated_at' : null);
            $amountCol = $hasCol('tz_invoices','total_amount') ? 'total_amount' : ($hasCol('tz_invoices','amount') ? 'amount' : null);

            if ($amountCol) {
                if ($paidCol) $q->where($paidCol, '>=', $from30);
                if ($hasCol('tz_invoices','status')) $q->whereIn('status', ['paid','Paid','PAID']);
                $kpi['revenue_30d'] = (float) ($q->sum($amountCol) ?? 0);
            }

            // Monthly series (last 6 months)
            $q2 = \Illuminate\Interactions\Facades\DB::table('tz_invoices');
            if ($hasCol('tz_invoices','user_id')) $q2->where('user_id', $tenantUserId);
            if ($hasCol('tz_invoices','company_id')) $q2->where('company_id', $companyId);
            if ($paidCol) $q2->where($paidCol, '>=', $from6m);
            if ($hasCol('tz_invoices','status')) $q2->whereIn('status', ['paid','Paid','PAID']);
            if ($amountCol && $paidCol) {
                $rows = $q2->selectRaw("DATE_FORMAT($paidCol, '%Y-%m') as ym, SUM($amountCol) as total")
                    ->groupBy('ym')->orderBy('ym')->get();
                $kpi['series_revenue_months'] = $rows->pluck('total','ym')->toArray();
            }
        }

        // Jobs series (last 7 days)
        if ($hasTable('tz_jobs')) {
            $q = \Illuminate\Interactions\Facades\DB::table('tz_jobs');
            if ($hasCol('tz_jobs','user_id')) $q->where('user_id', $tenantUserId);
            if ($hasCol('tz_jobs','company_id')) $q->where('company_id', $companyId);

            $dtCol = $hasCol('tz_jobs','scheduled_start_at') ? 'scheduled_start_at' : ($hasCol('tz_jobs','created_at') ? 'created_at' : null);
            if ($dtCol) {
                $rows = $q->where($dtCol, '>=', $from7d)
                    ->selectRaw("DATE($dtCol) as d, COUNT(*) as c")
                    ->groupBy('d')->orderBy('d')->get();
                $kpi['series_jobs_7d'] = $rows->pluck('c','d')->toArray();
            }
        }

        // Labor minutes (timesheets last 30 days)
        $laborMinutes30 = 0;
        if ($hasTable('tz_employee_timesheets') && $hasCol('tz_employee_timesheets','total_minutes')) {
            $q = \Illuminate\Interactions\Facades\DB::table('tz_employee_timesheets');
            if ($hasCol('tz_employee_timesheets','user_id')) $q->where('user_id', $tenantUserId);
            if ($hasCol('tz_employee_timesheets','company_id')) $q->where('company_id', $companyId);
            if ($hasCol('tz_employee_timesheets','started_at')) $q->where('started_at', '>=', $from30);
            if ($hasCol('tz_employee_timesheets','job_id')) $q->whereNotNull('job_id');
            $laborMinutes30 = (int) ($q->sum('total_minutes') ?? 0);
        }

        // Revenue per labor hour
        if ($laborMinutes30 > 0) {
            $kpi['rev_per_labor_hr'] = $kpi['revenue_30d'] / ($laborMinutes30 / 60.0);
        }

        // Efficiency (scheduled minutes vs actual minutes, last 7 days)
        $scheduledMin7 = 0;
        $actualMin7 = 0;
        if ($hasTable('tz_jobs') && $hasCol('tz_jobs','scheduled_start_at') && $hasCol('tz_jobs','scheduled_end_at')) {
            $q = \Illuminate\Interactions\Facades\DB::table('tz_jobs');
            if ($hasCol('tz_jobs','user_id')) $q->where('user_id', $tenantUserId);
            if ($hasCol('tz_jobs','company_id')) $q->where('company_id', $companyId);
            $q->where('scheduled_start_at', '>=', $from7d);
            $scheduledMin7 = (int) ($q->selectRaw("SUM(TIMESTAMPDIFF(MINUTE, scheduled_start_at, scheduled_end_at)) as m")->value('m') ?? 0);
        }
        if ($hasTable('tz_employee_timesheets') && $hasCol('tz_employee_timesheets','total_minutes') && $hasCol('tz_employee_timesheets','started_at')) {
            $q = \Illuminate\Interactions\Facades\DB::table('tz_employee_timesheets');
            if ($hasCol('tz_employee_timesheets','user_id')) $q->where('user_id', $tenantUserId);
            if ($hasCol('tz_employee_timesheets','company_id')) $q->where('company_id', $companyId);
            $q->where('started_at', '>=', $from7d);
            if ($hasCol('tz_employee_timesheets','job_id')) $q->whereNotNull('job_id');
            $actualMin7 = (int) ($q->sum('total_minutes') ?? 0);
        }
        if ($scheduledMin7 > 0 && $actualMin7 > 0) {
            $kpi['efficiency_pct'] = max(0, min(200, round(($scheduledMin7 / $actualMin7) * 100)));
        }

        // Utilization (billable minutes / available minutes, last 7 days)
        $activeStaff = 1;
        if ($hasTable('tz_employee_profiles') && $hasCol('tz_employee_profiles','is_active')) {
            $q = \Illuminate\Interactions\Facades\DB::table('tz_employee_profiles')->where('is_active', 1);
            if ($hasCol('tz_employee_profiles','user_id')) $q->where('user_id', $tenantUserId);
            if ($hasCol('tz_employee_profiles','company_id')) $q->where('company_id', $companyId);
            $activeStaff = max(1, (int) ($q->count() ?? 1));
        }
        // assume 8h/day per staff
        $availableMin7 = $activeStaff * 8 * 60 * 7;
        $billableMin7 = 0;
        if ($hasTable('tz_employee_timesheets') && $hasCol('tz_employee_timesheets','total_minutes') && $hasCol('tz_employee_timesheets','started_at')) {
            $q = \Illuminate\Interactions\Facades\DB::table('tz_employee_timesheets');
            if ($hasCol('tz_employee_timesheets','user_id')) $q->where('user_id', $tenantUserId);
            if ($hasCol('tz_employee_timesheets','company_id')) $q->where('company_id', $companyId);
            $q->where('started_at', '>=', $from7d);
            if ($hasCol('tz_employee_timesheets','job_id')) $q->whereNotNull('job_id');
            $billableMin7 = (int) ($q->sum('total_minutes') ?? 0);
        }
        if ($availableMin7 > 0) {
            $kpi['utilization_pct'] = max(0, min(100, round(($billableMin7 / $availableMin7) * 100)));
        }

        // Churn (cancelled subscriptions last 30 days / total subscriptions)
        if ($hasTable('tz_subscriptions') && $hasCol('tz_subscriptions','status')) {
            $qAll = \Illuminate\Interactions\Facades\DB::table('tz_subscriptions');
            if ($hasCol('tz_subscriptions','user_id')) $qAll->where('user_id', $tenantUserId);
            if ($hasCol('tz_subscriptions','company_id')) $qAll->where('company_id', $companyId);
            $totalSubs = (int) ($qAll->count() ?? 0);

            $qCan = \Illuminate\Interactions\Facades\DB::table('tz_subscriptions')->whereIn('status', ['cancelled','canceled','ended','inactive']);
            if ($hasCol('tz_subscriptions','user_id')) $qCan->where('user_id', $tenantUserId);
            if ($hasCol('tz_subscriptions','company_id')) $qCan->where('company_id', $companyId);
            $dtCol = $hasCol('tz_subscriptions','ended_at') ? 'ended_at' : ($hasCol('tz_subscriptions','updated_at') ? 'updated_at' : null);
            if ($dtCol) $qCan->where($dtCol, '>=', $from30);
            $cancelled = (int) ($qCan->count() ?? 0);

            if ($totalSubs > 0) $kpi['churn_pct'] = max(0, min(100, round(($cancelled / $totalSubs) * 100, 1)));
        }

        // Gross margin (demo estimate: revenue - labor_cost) / revenue
        if ($kpi['revenue_30d'] > 0 && $laborMinutes30 > 0) {
            $laborRate = 32.0; // demo $/hr until you wire real wage rates
            $laborCost = ($laborMinutes30 / 60.0) * $laborRate;
            $kpi['gross_margin_pct'] = max(-100, min(100, round((($kpi['revenue_30d'] - $laborCost) / $kpi['revenue_30d']) * 100)));
        }

        // Formatting
        $kpi['revenue_30d_fmt'] = '$' . number_format($kpi['revenue_30d'], 0);
        $kpi['rev_per_labor_hr_fmt'] = '$' . number_format($kpi['rev_per_labor_hr'], 0) . '/hr';
    } catch (\Throwable $e) {
        // fail closed: keep zeros/nulls
    }

    // Helper: normalize series to fixed keys for charts
    $kpi_month_keys = collect(range(0,5))->map(fn($i) => now()->subMonths(5-$i)->format('Y-m'))->values();
    $kpi_month_vals = $kpi_month_keys->map(fn($k) => (float)($kpi['series_revenue_months'][$k] ?? 0))->values();

    $kpi_day_keys = collect(range(0,6))->map(fn($i) => now()->subDays(6-$i)->format('Y-m-d'))->values();
    $kpi_day_vals = $kpi_day_keys->map(fn($k) => (int)($kpi['series_jobs_7d'][$k] ?? 0))->values();
@endphp

<style>
/* lightweight dashboard-only visuals */
.tz-kpi-grid{display:grid;gap:12px;grid-template-columns:repeat(2,minmax(0,1fr))}
@media (min-width: 768px){.tz-kpi-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media (min-width: 1280px){.tz-kpi-grid{grid-template-columns:repeat(5,minmax(0,1fr))}}
.tz-kpi-card{border-radius:14px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);padding:14px 14px 12px;position:relative;overflow:hidden}
.tz-kpi-title{font-size:12px;opacity:.75;margin-bottom:4px}
.tz-kpi-value{font-size:22px;font-weight:700;line-height:1.1}
.tz-kpi-sub{font-size:12px;opacity:.7;margin-top:4px}
.tz-gauge{width:56px;height:56px;border-radius:50%;display:grid;place-items:center;position:absolute;right:12px;top:12px;
    background:conic-gradient(currentColor var(--p), rgba(255,255,255,.10) 0)}
.tz-gauge::after{content:"";width:44px;height:44px;border-radius:50%;background:rgba(10,14,20,.85);border:1px solid rgba(255,255,255,.06)}
.tz-gauge-text{position:absolute;font-size:12px;font-weight:700}
.tz-spark{height:26px;width:100%;margin-top:10px;opacity:.9}
</style>


{{-- =========================
   COMMAND CENTRAL — 5 second view
   ========================= --}}
<x-card class="w-full mb-6" size="md" id="command">
    <x-slot:head class="border-0 pb-0 pt-5">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h4 class="m-0 text-[17px]">{{ __('Connect Pulse') }}</h4>
                <p class="mt-1 text-xs opacity-60">{{ __('Leads, campaigns, conversations, and the next best customer action.') }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <x-button variant="outline" href="{{ url('/account/jobs?date=today') }}">
                    <x-tabler-calendar class="size-4" />
                    {{ __('Today (Customers)') }}
                </x-button>
                <x-button variant="outline" href="{{ url('/account/jobs?date=today&filter=unassigned') }}">
                    <x-tabler-users class="size-4" />
                    {{ __('Pipeline') }}
                </x-button>
                <x-button variant="primary" href="{{ $boardActionUrl('wizard.command.next', 'modal') }}">
                    <x-tabler-sparkles class="size-4" />
                    <span class="font-bold">{{ __('Ask Customer AI') }}</span>
                </x-button>
            </div>
        </div>

        @php
            $alerts = [];
            if (($tz['jobs_overdue'] ?? 0) > 0) $alerts[] = ['label' => __('Follow-ups due: ') . ($tz['jobs_overdue'] ?? 0), 'href' => url('/account/jobs?filter=overdue')];
            if (($tz['jobs_unassigned'] ?? 0) > 0) $alerts[] = ['label' => __('New leads: ') . ($tz['jobs_unassigned'] ?? 0), 'href' => url('/account/jobs?date=today&filter=unassigned')];
            if (($tz['jobs_today'] ?? 0) === 0) $alerts[] = ['label' => __('No new leads — start a campaign'), 'href' => $boardActionUrl('wizard.pipeline.fill', 'modal')];
        @endphp

        @if (count($alerts))
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($alerts as $a)
                    <a href="{{ $a['href'] }}"
                       class="inline-flex items-center gap-2 rounded-full bg-background/40 px-3 py-1 text-xs font-semibold text-foreground transition hover:bg-background/60">
                        <span class="inline-block size-1.5 rounded-full bg-primary"></span>
                        {{ $a['label'] }}
                        <x-tabler-chevron-right class="size-4 opacity-70 rtl:rotate-180" />
                    </a>
                @endforeach
            </div>
        @endif
    </x-slot:head>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-6">
        <a class="rounded-xl bg-background/40 p-4 transition hover:bg-background/60" href="{{ url('/account/jobs?date=today') }}">
            <div class="text-xs font-medium opacity-60">{{ __('Leads Today') }}</div>
            <div class="mt-1 text-2xl font-bold">{{ (int)($tz['jobs_today'] ?? 0) }}</div>
            <div class="mt-1 text-xs opacity-70">{{ __('Scheduled today') }}</div>
        </a>

        <a class="rounded-xl bg-background/40 p-4 transition hover:bg-background/60" href="{{ url('/account/jobs?date=today&filter=unassigned') }}">
            <div class="text-xs font-medium opacity-60">{{ __('Unassigned') }}</div>
            <div class="mt-1 text-2xl font-bold">{{ (int)($tz['jobs_unassigned'] ?? 0) }}</div>
            <div class="mt-1 text-xs opacity-70">{{ __('Need a crew') }}</div>
        </a>

        <a class="rounded-xl bg-background/40 p-4 transition hover:bg-background/60" href="{{ url('/account/jobs?filter=overdue') }}">
            <div class="text-xs font-medium opacity-60">{{ __('Follow-ups Due') }}</div>
            <div class="mt-1 text-2xl font-bold">{{ (int)($tz['jobs_overdue'] ?? 0) }}</div>
            <div class="mt-1 text-xs opacity-70">{{ __('Need attention') }}</div>
        </a>

        <a class="rounded-xl bg-background/40 p-4 transition hover:bg-background/60" href="{{ url('/account/jobs?date=today&status=completed') }}">
            <div class="text-xs font-medium opacity-60">{{ __('Conversions') }}</div>
            <div class="mt-1 text-2xl font-bold">{{ (int)($tz['jobs_completed'] ?? 0) }}</div>
            <div class="mt-1 text-xs opacity-70">{{ __('Finished today') }}</div>
        </a>

        <a class="rounded-xl bg-background/40 p-4 transition hover:bg-background/60" href="{{ url('/account/schedule') }}">
            <div class="text-xs font-medium opacity-60">{{ __('Team Capacity') }}</div>
            <div class="mt-1 text-2xl font-bold">{{ is_null($kpi['utilization_pct']) ? '0%' : number_format((float)$kpi['utilization_pct'], 0) . '%' }}</div>
            <div class="mt-1 text-xs opacity-70">{{ __('Booked today') }}</div>
        </a>

        <a class="rounded-xl bg-background/40 p-4 transition hover:bg-background/60" href="{{ $boardActionUrl('wizard.work.next', 'modal') }}">
            <div class="text-xs font-medium opacity-60">{{ __('Next Best Action') }}</div>
            <div class="mt-1 text-2xl font-bold">⚡</div>
            <div class="mt-1 text-xs opacity-70">{{ __('Prioritize hot leads') }}</div>
        </a>
    </div>
</x-card>

{{-- =========================
   COMMAND CENTRAL — 5 to 10 minute view
   ========================= --}}
<div class="mb-4 flex items-center justify-between gap-4">
    <div>
        <h4 class="m-0 text-[17px]">{{ __('Customer Actions') }}</h4>
        <p class="mt-1 text-xs opacity-60">{{ __('Launch a tool, drill into CRM, or run a guided customer workflow.') }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <x-button variant="outline" href="{{ $boardActionUrl('wizard.invoices.followup', 'modal') }}">
            <x-tabler-send class="size-4" />
            {{ __('Follow-up Leads') }}
        </x-button>
        <x-button variant="outline" href="{{ $boardActionUrl('wizard.dispatch.optimize', 'modal') }}">
            <x-tabler-route class="size-4" />
            {{ __('Send Campaign') }}
        </x-button>
        <x-button variant="outline" href="{{ $boardActionUrl('wizard.staffing.check', 'modal') }}">
            <x-tabler-users class="size-4" />
            {{ __('Send SMS') }}
        </x-button>
    </div>
</div>

<div class="w-full mb-6">
    <div class="tz-kpi-grid">
        <div class="tz-kpi-card">
            <div class="tz-kpi-title">Pipeline Value</div>
            <div class="tz-kpi-value">{{ $kpi['revenue_30d_fmt'] }}</div>
            <div class="tz-kpi-sub">Open opportunities</div>
            <svg class="tz-spark" viewBox="0 0 120 26" preserveAspectRatio="none">
                @php
    $vals = $kpi_month_vals;
    $max = max(1, (float) $vals->max());
@endphp
                @foreach($vals as $i => $v)
                    @php
        $h = (int) round(($v / $max) * 22);
        $x = 2 + ($i * 19);
    @endphp
                    <rect x="{{ $x }}" y="{{ 24-$h }}" width="12" height="{{ $h }}" rx="2" ry="2" fill="currentColor" opacity="{{ $v>0 ? 0.85 : 0.25 }}"></rect>
                @endforeach
            </svg>
        </div>

        <div class="tz-kpi-card" style="color:#7C9CFF">
            <div class="tz-kpi-title">Conversion Rate</div>
            <div class="tz-kpi-value">{{ $kpi['efficiency_pct'] ?? 0 }}%</div>
            <div class="tz-kpi-sub">Lead to customer</div>
            @php
                $p = (int)($kpi['efficiency_pct'] ?? 0);
                $p = max(0, min(100, $p));
            @endphp
            <div class="tz-gauge" style="--p: {{ $p }}%;">
                <div class="tz-gauge-text">{{ $p }}%</div>
            </div>
        </div>

        <div class="tz-kpi-card" style="color:#8BE3B3">
            <div class="tz-kpi-title">Engagement Rate</div>
            <div class="tz-kpi-value">{{ $kpi['utilization_pct'] ?? 0 }}%</div>
            <div class="tz-kpi-sub">Audience activity</div>
            @php
                $p = (int)($kpi['utilization_pct'] ?? 0);
                $p = max(0, min(100, $p));
            @endphp
            <div class="tz-gauge" style="--p: {{ $p }}%;">
                <div class="tz-gauge-text">{{ $p }}%</div>
            </div>
        </div>

        <div class="tz-kpi-card">
            <div class="tz-kpi-title">LTV per Customer</div>
            <div class="tz-kpi-value">{{ $kpi['rev_per_labor_hr_fmt'] }}</div>
            <div class="tz-kpi-sub">Last 30 days</div>
            <svg class="tz-spark" viewBox="0 0 120 26" preserveAspectRatio="none">
                @php
    $vals = $kpi_day_vals;
    $max = max(1, (float) $vals->max());
@endphp
                @foreach($vals as $i => $v)
                    @php
        $h = (int) round(($v / $max) * 22);
        $x = 2 + ($i * 16);
    @endphp
                    <rect x="{{ $x }}" y="{{ 24-$h }}" width="10" height="{{ $h }}" rx="2" ry="2" fill="currentColor" opacity="{{ $v>0 ? 0.85 : 0.25 }}"></rect>
                @endforeach
            </svg>
        </div>

        <div class="tz-kpi-card" style="color:#F5C26B">
            <div class="tz-kpi-title">Churn Risk</div>
            <div class="tz-kpi-value">{{ $kpi['churn_pct'] ?? 0 }}%</div>
            <div class="tz-kpi-sub">Accounts needing retention</div>
            @php
                $p = (int) round($kpi['churn_pct'] ?? 0);
                $p = max(0, min(100, $p));
            @endphp
            <div class="tz-gauge" style="--p: {{ $p }}%;">
                <div class="tz-gauge-text">{{ $p }}%</div>
            </div>
        </div>
    </div>
</div>

<!-- start: ongoing payment -->
        @if ($ongoingPayments != null)
            <div class="w-full">
                @includeIf('panel.user.finance.ongoingPayments')
            </div>
        @endif
        <!-- end: ongoing payment -->

        <!-- start: finance subscription status -->
        <x-card
            class="{{ showTeamFunctionality() || !$user_is_premium ? 'lg:w-[48%]' : 'lg:w-full' }} w-full text-center"
            class:body="md:px-10 px-5"
            id="plan"
            data-name="{{ \App\Enums\Introduction::DASHBOARD_THREE }}"
            size="lg"
        >
            @php

$tz = [
    'jobs_today' => 0,
    'jobs_unassigned' => 0,
    'jobs_overdue' => 0,
    'jobs_completed' => 0,
    'invoices_due' => 0,
    'invoices_overdue' => 0,
    'customers_total' => 0,
];

$today = now()->toDateString();

try {
    // Jobs today via tz_job_schedule.scheduled_date (confirmed anchor)
    if (\Illuminate\Interactions\Facades\Schema::hasTable('tz_job_schedule') && \Illuminate\Interactions\Facades\Schema::hasTable('tz_jobs')) {
        $schedBase = \Illuminate\Interactions\Facades\DB::table('tz_job_schedule')
            ->join('tz_jobs', 'tz_jobs.id', '=', 'tz_job_schedule.job_id');

        if (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_jobs', 'user_id')) {
            $schedBase->where('tz_jobs.user_id', auth()->id());
        }
        if (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_jobs', 'team_id') && auth()->user()?->team_id) {
            $schedBase->where('tz_jobs.team_id', auth()->user()->team_id);
        }

        $sched = (clone $schedBase)->whereDate('tz_job_schedule.scheduled_date', $today);

        $tz['jobs_today'] = (int) (clone $sched)->count();

        // best-effort assignment
        if (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_jobs', 'assigned_user_id')) {
            $tz['jobs_unassigned'] = (int) (clone $sched)->whereNull('tz_jobs.assigned_user_id')->count();
        } elseif (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_jobs', 'assigned_to')) {
            $tz['jobs_unassigned'] = (int) (clone $sched)->whereNull('tz_jobs.assigned_to')->count();
        }

        $completedJobIds = null;
        // completed heuristic via tz_job_states if available
        if (\Illuminate\Interactions\Facades\Schema::hasTable('tz_job_states') && \Illuminate\Interactions\Facades\Schema::hasColumn('tz_job_states', 'job_id')) {
            $completed = \Illuminate\Interactions\Facades\DB::table('tz_job_states')
                ->whereIn('job_id', (clone $sched)->select('tz_jobs.id'));

            $completedJobIds = \Illuminate\Interactions\Facades\DB::table('tz_job_states');
            if (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_job_states', 'state')) {
                $completed->where('state', 'completed');
                $completedJobIds->where('state', 'completed');
            } elseif (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_job_states', 'state_type')) {
                $completed->where('state_type', 'completed');
                $completedJobIds->where('state_type', 'completed');
            }

            $tz['jobs_completed'] = (int) $completed->count();
            $completedJobIds = $completedJobIds->select('job_id');
        }

        $overdue = (clone $schedBase)->whereDate('tz_job_schedule.scheduled_date', '<', $today);
        if ($completedJobIds) {
            $overdue->whereNotIn('tz_jobs.id', $completedJobIds);
        } elseif (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_jobs', 'status')) {
            $overdue->whereNotIn('tz_jobs.status', ['completed', 'done', 'closed']);
        }
        $tz['jobs_overdue'] = (int) $overdue->distinct('tz_jobs.id')->count('tz_jobs.id');
    }

    // Invoices (due/overdue)
    if (\Illuminate\Interactions\Facades\Schema::hasTable('tz_invoices')) {
        $inv = \Illuminate\Interactions\Facades\DB::table('tz_invoices');
        if (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_invoices', 'user_id')) $inv->where('user_id', auth()->id());
        if (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_invoices', 'team_id') && auth()->user()?->team_id) $inv->where('team_id', auth()->user()->team_id);

        $dueCol = \Illuminate\Interactions\Facades\Schema::hasColumn('tz_invoices', 'due_date') ? 'due_date' : (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_invoices', 'due_at') ? 'due_at' : null);
        $statusCol = \Illuminate\Interactions\Facades\Schema::hasColumn('tz_invoices', 'status') ? 'status' : null;

        if ($dueCol) {
            $tz['invoices_due'] = (int) (clone $inv)->whereDate($dueCol, $today)->count();
            $tz['invoices_overdue'] = (int) (clone $inv)
                ->whereDate($dueCol, '<', $today)
                ->when($statusCol, fn($q)=>$q->whereNotIn('status',['paid','void']))
                ->count();
        }
    }

    // Customers
    if (\Illuminate\Interactions\Facades\Schema::hasTable('tz_customers')) {
        $cust = \Illuminate\Interactions\Facades\DB::table('tz_customers');
        if (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_customers', 'user_id')) $cust->where('user_id', auth()->id());
        if (\Illuminate\Interactions\Facades\Schema::hasColumn('tz_customers', 'team_id') && auth()->user()?->team_id) $cust->where('team_id', auth()->user()->team_id);
        $tz['customers_total'] = (int) $cust->count();
    }
} catch (\Throwable $e) {
    // dashboard must never crash
}
@endphp


<div class="flex items-start justify-between gap-4">
    <div>
        <h3 class="mb-1 text-xl font-semibold">{{ __('Today's Work') }}</h3>
        <p class="text-xs font-medium opacity-60">{{ __('Run the day from one card: assign, start, complete, and catch risks early.') }}</p>
    </div>
    <div class="flex gap-2">
        <x-button variant="outline" type="button" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'quick_assign'}}))">{{ __('Quick Assign') }}</x-button>
        <x-button type="button" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'dispatch_plan'}}))">{{ __('Create Campaign') }}</x-button>
    </div>
</div>

<div class="mt-4 rounded-2xl border border-white/5 bg-background/30 px-4 py-3 text-xs font-semibold">
    <span class="opacity-80">⚡ {{ $tz['jobs_unassigned'] }} {{ __('need assignment') }}</span>
    <span class="mx-2 opacity-30">•</span>
    <span class="opacity-80">{{ $tz['jobs_overdue'] }} {{ __('overdue') }}</span>
    <span class="mx-2 opacity-30">•</span>
    <span class="opacity-80">{{ max(0, $tz['jobs_today'] - $tz['jobs_completed']) }} {{ __('still live today') }}</span>
</div>

<div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-6 2xl:grid-cols-4">
    <button type="button" class="rounded-xl bg-background/40 p-4 text-start transition hover:bg-background/60" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'open_jobs_today'}}))">
        <div class="text-xs font-medium opacity-60">{{ __('Leads Today') }}</div>
        <div class="mt-1 text-2xl font-bold">{{ $tz['jobs_today'] }}</div>
        <div class="mt-1 text-xs opacity-70">{{ __('Scheduled today') }}</div>
    </button>

    <button type="button" class="rounded-xl bg-background/40 p-4 text-start transition hover:bg-background/60" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'staffing_optimize'}}))">
        <div class="text-xs font-medium opacity-60">{{ __('Unassigned') }}</div>
        <div class="mt-1 text-2xl font-bold">{{ $tz['jobs_unassigned'] }}</div>
        <div class="mt-1 text-xs opacity-70">{{ __('Need crew') }}</div>
    </button>

    <button type="button" class="rounded-xl bg-background/40 p-4 text-start transition hover:bg-background/60" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'overdue_recovery'}}))">
        <div class="text-xs font-medium opacity-60">{{ __('Follow-ups Due') }}</div>
        <div class="mt-1 text-2xl font-bold">{{ $tz['jobs_overdue'] }}</div>
        <div class="mt-1 text-xs opacity-70">{{ __('Past due') }}</div>
    </button>

    <button type="button" class="rounded-xl bg-background/40 p-4 text-start transition hover:bg-background/60" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'open_jobs_completed'}}))">
        <div class="text-xs font-medium opacity-60">{{ __('Conversions') }}</div>
        <div class="mt-1 text-2xl font-bold">{{ $tz['jobs_completed'] }}</div>
        <div class="mt-1 text-xs opacity-70">{{ __('Finished today') }}</div>
    </button>
</div>

<div class="mt-6 grid grid-cols-2 gap-3 xl:grid-cols-4">
    <button type="button" class="rounded-2xl border border-white/5 bg-background/25 px-4 py-3 text-start text-sm font-semibold hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'quick_assign'}}))">{{ __('Quick Assign') }}</button>
    <button type="button" class="rounded-2xl border border-white/5 bg-background/25 px-4 py-3 text-start text-sm font-semibold hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'quick_schedule'}}))">{{ __('Quick Schedule') }}</button>
    <button type="button" class="rounded-2xl border border-white/5 bg-background/25 px-4 py-3 text-start text-sm font-semibold hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'checklist_run'}}))">{{ __('Quick Checklist') }}</button>
    <button type="button" class="rounded-2xl border border-white/5 bg-background/25 px-4 py-3 text-start text-sm font-semibold hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'dispatch_plan'}}))">{{ __('Quick Dispatch') }}</button>
</div>

        <!-- end: finance subscription status -->

        @if (!$user_is_premium || $app_is_demo)
            
<x-card
    class="relative flex w-full flex-col justify-center bg-cover bg-top text-center lg:w-[48%]"
    class:body="flex flex-col only:grow-0 py-8 xl:px-20 static"
>
    @php
        $calendarUrl = '#';
        try {
            if (\Illuminate\Interactions\Facades\Route::has('dashboard.user.bookings.index')) {
                $calendarUrl = route('dashboard.user.bookings.index');
            } elseif (\Illuminate\Interactions\Facades\Route::has('dashboard.user.schedule.index')) {
                $calendarUrl = route('dashboard.user.schedule.index');
            }
        } catch (\Throwable $e) {
            $calendarUrl = '#';
        }

        $calStart = \Carbon\Carbon::today();
        $days = collect(range(0, 6))->map(fn($i) => (clone $calStart)->addDays($i));
    @endphp

    <div class="tz-premcal">
        <div class="tz-premcal-top">
            <div>
                <div class="tz-premcal-title">{{ __('Schedule Engine') }}</div>
                <div class="tz-premcal-sub">{{ __('Capacity, gaps, and the next 7 days of work.') }}</div>
            </div>
            <a class="tz-premcal-link" href="{{ $calendarUrl }}">{{ __('Open CRM Calendar') }} ›</a>
        </div>
        <div class="tz-premcal-grid">
            @foreach($days as $d)
                @php $jobs = ($d->isToday() ? max(0, (int)$tz['jobs_today']) : (($loop->iteration % 3) + 2)); $free = max(0, 8 - $jobs); @endphp
                <a class="tz-premcal-day" href="{{ $calendarUrl }}">
                    <div class="tz-premcal-dow">{{ $d->format('D') }}</div>
                    <div class="tz-premcal-num">{{ $d->format('j') }}</div>
                    <div class="tz-premcal-meta">{{ $jobs }} {{ __('jobs') }} · {{ $free }} {{ __('free') }}</div>
                </a>
            @endforeach
        </div>
        <div class="mt-4 grid grid-cols-3 gap-2 text-xs">
            <button type="button" class="rounded-full border border-white/5 px-3 py-2 hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'find_schedule_gaps'}}))">{{ __('Find Gaps') }}</button>
            <button type="button" class="rounded-full border border-white/5 px-3 py-2 hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'dispatch_plan'}}))">{{ __('Auto Plan') }}</button>
            <button type="button" class="rounded-full border border-white/5 px-3 py-2 hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'open_schedule'}}))">{{ __('View Week') }}</button>
        </div>
    </div>
</x-card>

        @endif
        <!-- end: premiun features -->

        {{-- begin: account summary --}}
        @includeIf('panel.user.dashboard.account-summary')
        {{-- end: account summary --}}

        {{-- begin: invite team --}}
        @if (showTeamFunctionality())
            <x-card
                class="w-full lg:w-[48%]"
                id="team"
                size="lg"
            >
                @if ($app_is_demo || ($team && $team?->allow_seats > 0))
                    <figure class="mb-7">
                        <img
                            class="mx-auto w-full lg:w-7/12"
                            src="{{ custom_theme_url('assets/img/team/team.png') }}"
                            alt="Team"
                        >
                    </figure>
                    <p class="mb-6 text-center text-xl font-semibold">
                        @lang('Add your team members’ email address <br> to start collaborating.')
                        📧
                    </p>
                    <form
                        class="flex flex-col gap-3"
                        action="{{ route('dashboard.user.team.invitation.store', $team?->id ?? 0) }}"
                        method="post"
                    >
                        @csrf
                        <input
                            type="hidden"
                            name="team_id"
                            value="{{ $team?->id }}"
                        >
                        <x-forms.input
                            class="mb-6"
                            id="email"
                            size="lg"
                            type="email"
                            name="email"
                            placeholder="{{ __('Email address') }}"
                            required
                        >
                            <x-slot:icon>
                                <x-tabler-mail class="absolute end-3 top-1/2 size-5 -translate-y-1/2" />
                            </x-slot:icon>
                        </x-forms.input>
                        @if ($app_is_demo)
                            <x-button onclick="return toastr.info('This feature is disabled in Demo version.')">
                                @lang('Invite Friends')
                            </x-button>
                        @else
                            <x-button
                                class="py-3"
                                data-name="{{ \App\Enums\Introduction::AFFILIATE_SEND }}"
                                type="submit"
                            >
                                @lang('Invite Friends')
                            </x-button>
                        @endif
                    </form>
                @else
                    <h3 class="mb-6">
                        {{ __('How it Works') }}
                    </h3>

                    <ol class="mb-12 flex flex-col gap-4 text-heading-foreground">
                        <li>
                            <span class="me-2 inline-flex size-7 items-center justify-center rounded-full bg-primary/10 font-extrabold text-primary">
                                1
                            </span>
                            {!! __('You <strong>send your invitation link</strong> to your friends.') !!}
                        </li>
                        <li>
                            <span class="me-2 inline-flex size-7 items-center justify-center rounded-full bg-primary/10 font-extrabold text-primary">
                                2
                            </span>
                            {!! __('<strong>They subscribe</strong> to a paid plan by using your refferral link.') !!}
                        </li>
                        <li>
                            <span class="me-2 inline-flex size-7 items-center justify-center rounded-full bg-primary/10 font-extrabold text-primary">
                                3
                            </span>
                            @if ($is_onetime_commission)
                                {!! __('From their first purchase, you will begin <strong>earning one-time commissions</strong>.') !!}
                            @else
                                {!! __('From their first purchase, you will begin <strong>earning recurring commissions</strong>.') !!}
                            @endif
                        </li>
                    </ol>

                    <form
                        class="flex flex-col gap-3"
                        id="send_invitation_form"
                        onsubmit="return sendInvitationForm();"
                    >
                        <x-forms.input
                            class:label="text-heading-foreground"
                            id="to_mail"
                            label="{{ __('Affiliate Link') }}"
                            size="sm"
                            type="email"
                            name="to_mail"
                            placeholder="{{ __('Email address') }}"
                            required
                        >
                            <x-slot:icon>
                                <x-tabler-mail class="absolute end-3 top-1/2 size-5 -translate-y-1/2" />
                            </x-slot:icon>
                        </x-forms.input>

                        <x-button
                            class="w-full"
                            id="send_invitation_button"
                            type="submit"
                            form="send_invitation_form"
                        >
                            {{ __('Send') }}
                        </x-button>
                    </form>
                @endif
            </x-card>
        @endif
        {{-- end: invite team --}}
        {{-- begin: affiliates --}}
        @includeIf('panel.user.dashboard.affiliates')
        {{-- end: affiliates --}}

        {{-- begin: favorite chatbots --}}
        @includeIf('panel.user.dashboard.favorite-chatbots')
        {{-- end: favorite chatbots --}}

        {{-- begin: add new --}}
        <x-card
            id="add-new"
            @class([
                'flex w-full flex-col lg:w-[48%]',
                'lg:w-[48%]' => !View::exists('panel.user.dashboard.favorite-chatbots'),
            ])
            size="md"
        >
            <x-slot:head
                class="border-0 px-7 pb-0 pt-5"
            >
                <h4 class="m-0 text-lg">
                    {{ __('Add New') }}
                </h4>
            </x-slot:head>

            <div class="grid w-full grid-cols-1 justify-between gap-4 sm:grid-cols-2">
                @if (\App\Helpers\Classes\MarketplaceHelper::isRegistered('chatbot') || $app_is_demo)
                    <x-card
                        class="group relative w-full cursor-pointer overflow-hidden transition-all duration-300 before:absolute before:inset-0 before:bg-gradient-to-br before:from-gradient-from/20 before:to-gradient-via/20 before:opacity-0 before:transition-all hover:before:opacity-100"
                        class:body="flex flex-col justify-between max-sm:gap-5 gap-16 z-1"
                    >
                        <svg
                            class="fill-foreground dark:group-hover:fill-background max-sm:mx-auto"
                            width="43"
                            height="38"
                            viewBox="0 0 43 38"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M0.447266 14.305C0.447266 10.9552 3.16283 8.23962 6.51265 8.23962H24.7361C28.0858 8.23962 30.8014 10.9552 30.8014 14.305V27.7078C30.8014 31.0577 28.0858 33.7734 24.7361 33.7734H9.71474C9.49631 33.7734 9.28671 33.8595 9.1314 34.0131L6.86331 36.2561C4.48423 38.6091 0.447266 36.9239 0.447266 33.5777V14.305ZM6.51265 11.1771C4.78517 11.1771 3.38477 12.5775 3.38477 14.305V33.5777C3.38477 34.3146 4.27377 34.6857 4.79768 34.1676L7.06579 31.9245C7.77102 31.2269 8.72287 30.8359 9.71474 30.8359H24.7361C26.4635 30.8359 27.8639 29.4354 27.8639 27.7078V14.305C27.8639 12.5775 26.4635 11.1771 24.7361 11.1771H6.51265Z"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M12.1973 6.47164C12.1973 3.12181 14.9128 0.40625 18.2626 0.40625H36.4861C39.8358 0.40625 42.5514 3.12181 42.5514 6.47164V25.0309C42.5514 28.3513 38.5674 30.0478 36.1737 27.7465L33.8778 25.5395C33.7233 25.3911 33.5172 25.308 33.303 25.308H27.8639V14.0859C27.8639 12.3584 26.4635 10.958 24.7361 10.958H12.1973V6.47164ZM18.2626 3.34375C16.5352 3.34375 15.1348 4.74415 15.1348 6.47164V8.0205H24.7361C28.0858 8.0205 30.8014 10.7361 30.8014 14.0859V22.3705H33.303C34.2763 22.3705 35.212 22.7473 35.9137 23.422L38.2094 25.629C38.7366 26.1356 39.6139 25.7622 39.6139 25.0309V6.47164C39.6139 4.74415 38.2135 3.34375 36.4861 3.34375H18.2626Z"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M8.48306 22.674C8.89321 21.9741 9.79303 21.7393 10.4929 22.1494C12.6363 23.4057 14.2137 23.9121 15.6694 23.9062C17.1257 23.9002 18.6718 23.3808 20.7535 22.1518C21.4521 21.7395 22.3527 21.9714 22.7651 22.6699C23.1774 23.3685 22.9455 24.2691 22.2469 24.6815C19.9563 26.0338 17.8805 26.8347 15.6814 26.8437C13.4817 26.8525 11.3704 26.0686 9.00762 24.6839C8.30777 24.2736 8.07292 23.374 8.48306 22.674Z"
                            />
                        </svg>
                        <h4 class="m-0 dark:group-hover:text-background max-sm:text-center">
                            @lang('External Chatbot')
                        </h4>
                        <a
                            class="absolute inset-0"
                            href="{{ (\App\Helpers\Classes\MarketplaceHelper::isRegistered('chatbot') && \Illuminate\Interactions\Facades\Route::has('dashboard.chatbot.index')) ? route('dashboard.chatbot.index') : '#' }}"
                        ></a>
                    </x-card>

                @php
                    $tz_map_href = '#';
                    if (class_exists(\Illuminate\Interactions\Facades\Route::class)) {
                        try {
                            if (\Illuminate\Interactions\Facades\Route::has('dashboard.user.jobs.map.index')) {
                                $tz_map_href = route('dashboard.user.jobs.map.index');
                            } elseif (\Illuminate\Interactions\Facades\Route::has('dashboard.user.map.index')) {
                                $tz_map_href = route('dashboard.user.map.index');
                            } elseif (\Illuminate\Interactions\Facades\Route::has('dashboard.user.schedule.index')) {
                                $tz_map_href = route('dashboard.user.schedule.index');
                            }
                        } catch (\Throwable $e) {
                            $tz_map_href = '#';
                        }
                    }
                @endphp

                <x-card
                    class="group relative w-full overflow-hidden transition-all duration-300 before:absolute before:inset-0 before:bg-gradient-to-br before:from-gradient-from/10 before:to-gradient-via/10 before:opacity-0 before:transition-all before:duration-300 hover:before:opacity-100"
                    id="tz-map-preview-card"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-heading dark:text-white">
                                {{ __('Team Map') }}
                            </h3>
                            <p class="mt-1 text-sm text-muted dark:text-muted-foreground">
                                {{ __('Live locations + routes (preview)') }}
                            </p>
                        </div>

                        <a href="{{ $tz_map_href }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-white/5 px-3 py-2 text-sm font-medium text-heading transition hover:bg-white/10 dark:text-white"
                        >
                            {{ __('Open') }}
                            <span class="text-muted dark:text-muted-foreground">›</span>
                        </a>
                    </div>

                    <div class="mt-5 tz-map-mini">
                        <div class="tz-map-grid"></div>

                        <div class="tz-map-dot tz-dot-1" title="Cleaner"></div>
                        <div class="tz-map-dot tz-dot-2" title="Cleaner"></div>
                        <div class="tz-map-dot tz-dot-3" title="Cleaner"></div>

                        <div class="tz-map-footer">
                            <span class="text-xs text-muted dark:text-muted-foreground">
                                {{ __('Tap “Open” for the full map view.') }}
                            </span>
                        </div>
                    </div>
                </x-card>

                @endif

                @if (\App\Helpers\Classes\MarketplaceHelper::isRegistered('social-media') || $app_is_demo)
                    <x-card
                        class="group relative w-full cursor-pointer overflow-hidden transition-all duration-300 before:absolute before:inset-0 before:bg-gradient-to-br before:from-gradient-from/20 before:to-gradient-via/20 before:opacity-0 before:transition-all hover:before:opacity-100"
                        class:body="flex flex-col justify-between max-sm:gap-5 gap-16 z-1"
                    >
                        <svg
                            class="fill-foreground dark:group-hover:fill-background max-sm:mx-auto"
                            width="36"
                            height="36"
                            viewBox="0 0 36 36"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M15.0934 0.941567C15.3142 0.445008 15.8066 0.125 16.35 0.125C19.8432 0.125 22.675 2.95679 22.675 6.45V11.675H31.1928C32.5612 11.662 33.8669 12.2493 34.7651 13.2822C35.6651 14.3172 36.0653 15.6951 35.8594 17.0513L35.859 17.0535L33.5824 31.9013C33.5824 31.9009 33.5824 31.9014 33.5824 31.9013C33.233 34.2028 31.2431 35.8972 28.9158 35.875H9.75C8.99061 35.875 8.375 35.2594 8.375 34.5V16.35C8.375 16.1577 8.41537 15.9674 8.49351 15.7916L15.0934 0.941567ZM17.1985 2.97631L11.125 16.6419V33.125H28.9386C29.8983 33.1358 30.7197 32.4379 30.8636 31.4888L33.1406 16.6388C33.2249 16.0807 33.0603 15.5125 32.6899 15.0867C32.3194 14.6604 31.7802 14.4186 31.2156 14.425H31.2H21.3C20.5406 14.425 19.925 13.8094 19.925 13.05V6.45C19.925 4.76797 18.7634 3.35724 17.1985 2.97631Z"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M5.35566 14.7918H9.75C10.5094 14.7918 11.125 15.4074 11.125 16.1668V34.4996C11.125 35.259 10.5094 35.875 9.75 35.875L5.35574 35.8746C2.7311 35.9146 0.489448 33.9866 0.137411 31.384C0.129143 31.3228 0.125 31.2612 0.125 31.1996V19.6496C0.125 19.588 0.129143 19.5264 0.137411 19.4653C0.477055 16.9544 2.64255 14.7511 5.35566 14.7918ZM2.875 19.7519V31.0967C3.07547 32.28 4.11212 33.1462 5.32019 33.1248L5.3445 33.1244L8.375 33.1246V17.5418H5.32017C4.19749 17.522 3.08805 18.4761 2.875 19.7519Z"
                            />
                        </svg>
                        <h4 class="m-0 dark:group-hover:text-background max-sm:text-center">
                            @lang('Social Media Post')
                        </h4>
                        <a
                            class="absolute inset-0"
                            href="{{ \App\Helpers\Classes\MarketplaceHelper::isRegistered('social-media') ? route('dashboard.user.social-media.index') : '#' }}"
                        ></a>
                    </x-card>
                @endif

                @if (\App\Helpers\Classes\MarketplaceHelper::isRegistered('openai-realtime-chat') || $app_is_demo)
                    <x-card
                        class="group relative w-full cursor-pointer overflow-hidden transition-all duration-300 before:absolute before:inset-0 before:bg-gradient-to-br before:from-gradient-from/20 before:to-gradient-via/20 before:opacity-0 before:transition-all hover:before:opacity-100"
                        class:body="flex flex-col justify-between max-sm:gap-5 gap-16 z-1"
                    >
                        <svg
                            class="fill-foreground dark:group-hover:fill-background max-sm:mx-auto"
                            width="40"
                            height="40"
                            viewBox="0 0 40 40"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M8.75 10.8333C8.75 6.92132 11.9213 3.75 15.8333 3.75C19.7453 3.75 22.9167 6.92132 22.9167 10.8333C22.9167 14.7453 19.7453 17.9167 15.8333 17.9167C11.9213 17.9167 8.75 14.7453 8.75 10.8333ZM15.8333 6.25C13.302 6.25 11.25 8.30203 11.25 10.8333C11.25 13.3646 13.302 15.4167 15.8333 15.4167C18.3647 15.4167 20.4167 13.3646 20.4167 10.8333C20.4167 8.30203 18.3647 6.25 15.8333 6.25Z"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M26.9414 5.54862C27.5589 5.23989 28.3097 5.49017 28.6184 6.10764L27.5004 6.66665C28.6184 6.10764 28.6182 6.10712 28.6184 6.10764L28.6195 6.10975L28.6207 6.11224L28.6237 6.11822L28.6315 6.13442L28.6547 6.18339C28.6732 6.22329 28.6977 6.27769 28.7269 6.34585C28.785 6.48212 28.8617 6.67387 28.9452 6.91532C29.1122 7.39745 29.3084 8.08252 29.4387 8.9229C29.6999 10.6055 29.699 12.9269 28.6574 15.4733C28.3959 16.1122 27.666 16.4183 27.027 16.1569C26.388 15.8955 26.082 15.1657 26.3434 14.5267C27.1767 12.4897 27.1759 10.6445 26.9682 9.30625C26.8644 8.6362 26.7089 8.09732 26.5829 7.7331C26.5199 7.55137 26.4647 7.41435 26.4277 7.32762C26.4092 7.2843 26.3952 7.25365 26.3872 7.2364L26.3799 7.22069L26.3807 7.22245"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M32.7275 2.24066C33.3309 1.9054 34.0919 2.12281 34.4272 2.7263L33.3345 3.33335C34.4272 2.7263 34.4269 2.72575 34.4272 2.7263L34.4285 2.72863L34.4302 2.73158L34.4344 2.73938L34.4469 2.76243C34.4569 2.78108 34.4702 2.80631 34.4865 2.83796C34.5192 2.90126 34.5639 2.99026 34.6177 3.1037C34.7252 3.33045 34.8697 3.65541 35.0285 4.06823C35.3457 4.89293 35.722 6.07405 35.9725 7.52721C36.4745 10.4386 36.472 14.4517 34.4725 18.8507C34.1869 19.4792 33.4457 19.757 32.8172 19.4713C32.1887 19.1857 31.9109 18.4445 32.1965 17.8162C33.947 13.965 33.9445 10.4781 33.5089 7.95198C33.2907 6.68641 32.9639 5.66441 32.6952 4.96568C32.561 4.61676 32.4419 4.34993 32.359 4.17533C32.3175 4.08806 32.2854 4.024 32.2649 3.98438L32.2434 3.94351L32.24 3.93718C32.2397 3.93665 32.2399 3.93691 32.24 3.93718L32.2409 3.9387C31.9064 3.33536 32.1244 2.57575 32.7275 2.24066Z"
                            />
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M13.9457 22.0834H17.7223C19.5425 22.0834 20.985 22.0834 22.1478 22.1784C23.3366 22.2755 24.3428 22.4782 25.2613 22.9462C26.751 23.7052 27.9622 24.9164 28.7211 26.406C29.1891 27.3245 29.3918 28.3307 29.489 29.5195C29.584 30.6824 29.584 32.1247 29.584 33.945V35C29.584 35.6904 29.0243 36.25 28.334 36.25H3.33398C2.64363 36.25 2.08398 35.6904 2.08398 35V33.945C2.08397 32.1249 2.08397 30.6824 2.17897 29.5195C2.2761 28.3307 2.47878 27.3245 2.94685 26.406C3.70583 24.9164 4.91693 23.7052 6.40655 22.9462C7.32518 22.4782 8.33125 22.2755 9.52024 22.1784C10.6829 22.0834 12.1254 22.0834 13.9457 22.0834ZM7.5415 25.1737C6.52232 25.693 5.69368 26.5217 5.17437 27.5409C4.9158 28.0484 4.75517 28.6889 4.67067 29.7232C4.58878 30.7252 4.58423 31.9932 4.584 33.75H27.084C27.0837 31.9932 27.0792 30.7252 26.9973 29.7232C26.9128 28.6889 26.7521 28.0484 26.4937 27.5409C25.9743 26.5217 25.1457 25.693 24.1265 25.1737M7.5415 25.1737C8.04898 24.9152 8.68944 24.7545 9.7238 24.67C10.7728 24.5844 12.1132 24.5834 14.0007 24.5834H17.6673C19.5548 24.5834 20.8951 24.5844 21.9441 24.67C22.9785 24.7545 23.619 24.9152 24.1265 25.1737"
                            />
                        </svg>
                        <h4 class="m-0 dark:group-hover:text-background max-sm:text-center">
                            @lang('Voice Chat')
                        </h4>
                        <a
                            class="absolute inset-0"
                            href="{{ \App\Helpers\Classes\MarketplaceHelper::isRegistered('openai-realtime-chat') ? route('dashboard.user.openai.chat.chat', ['ai_realtime_voice_chat']) : '#' }}"
                        ></a>
                    </x-card>
                @endif

                <x-card
                    class="group relative w-full cursor-pointer overflow-hidden transition-all duration-300 before:absolute before:inset-0 before:bg-gradient-to-br before:from-gradient-from/20 before:to-gradient-via/20 before:opacity-0 before:transition-all hover:before:opacity-100"
                    class:body="flex flex-col justify-between max-sm:gap-5 gap-16 z-1"
                    size="sm"
                >
                    <svg
                        class="fill-foreground dark:group-hover:fill-background max-sm:mx-auto"
                        width="31"
                        height="31"
                        viewBox="0 0 31 31"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M24.6635 3.96639C24.3344 3.75377 23.9026 3.79943 23.6242 4.07909L4.58892 23.1979C4.15472 23.6341 4.10559 23.6958 4.07362 23.7473C4.02707 23.8224 3.99207 23.9042 3.96995 23.9902C3.95461 24.0499 3.94393 24.1292 3.92872 24.7455L3.86907 27.1625L6.35305 27.1665C6.99083 27.1675 7.07051 27.1582 7.12959 27.144C7.21681 27.123 7.30042 27.0883 7.3773 27.0411C7.42984 27.0088 7.49359 26.9581 7.94439 26.5054L26.8639 7.50281C26.9155 7.45095 26.9229 7.44332 26.9271 7.43876C27.1991 7.15174 27.2443 6.71358 27.0345 6.37553C27.0311 6.37015 27.0254 6.36118 26.9852 6.29987L26.9359 6.22462C26.3431 5.32054 25.569 4.55143 24.6635 3.96639ZM21.5573 2.02122C22.8059 0.767056 24.7592 0.555685 26.2464 1.5166C27.4941 2.32278 28.5597 3.38185 29.3751 4.62547L29.4314 4.71132C29.46 4.755 29.4874 4.79691 29.5125 4.83729C30.4206 6.3001 30.2291 8.19432 29.0445 9.44471C29.0118 9.47918 28.9765 9.51465 28.9398 9.55163L10.0113 28.5633C9.98632 28.5884 9.96139 28.6134 9.93651 28.6385C9.61063 28.9666 9.29159 29.2879 8.90493 29.5256C8.56626 29.7339 8.19679 29.8873 7.81013 29.98C7.36843 30.0861 6.91572 30.0847 6.45432 30.0834C6.41906 30.0833 6.38375 30.0831 6.34839 30.0831L2.37202 30.0767C1.97942 30.0761 1.60364 29.9172 1.32971 29.636C1.05576 29.3547 0.906776 28.975 0.91646 28.5825L1.01294 24.6735C1.01379 24.6395 1.01459 24.6054 1.01538 24.5715C1.02585 24.1241 1.03606 23.6878 1.14533 23.2632C1.24104 22.8914 1.39266 22.5361 1.59512 22.2097C1.82637 21.837 2.13465 21.5281 2.45003 21.2122C2.47399 21.1882 2.49799 21.1641 2.52201 21.1401L21.5573 2.02122Z"
                        />
                    </svg>
                    <h4 class="m-0 dark:group-hover:text-background max-sm:text-center">
                        @lang('Blog Post')
                    </h4>
                    <a
                        class="absolute inset-0"
                        href="{{ route('dashboard.user.openai.articlewizard.new') }}"
                    ></a>
                </x-card>
            </div>
        </x-card>
        {{-- end: add new --}}

        
{{-- begin: live activity --}}
<div class="grid w-full grid-cols-1 gap-8" id="activity">
    <x-card class="w-full" size="md">
        <x-slot:head class="border-0 pb-0 pt-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h4 class="m-0 text-[17px]">{{ __('Live Activity') }}</h4>
                    <p class="mt-1 text-xs opacity-60">{{ __('Latest work events from jobs, scheduling, assistants, and trust checks.') }}</p>
                </div>
                <button type="button" class="rounded-full border border-white/5 px-3 py-1.5 text-xs font-semibold hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'open_activity'}}))">{{ __('Open feed') }}</button>
            </div>
        </x-slot:head>
        <div class="mt-4 grid gap-3 lg:grid-cols-2">
            @foreach ($activityEvents->take(4) as $event)
                @php
                    $label = $event->label ?? $event->title ?? $event->event_name ?? ($event->event_type ?? __('Work event'));
                    $meta = $event->meta ?? $event->created_at ?? __('Latest signal');
                    $tone = $event->tone ?? 'default';
                    $border = $tone === 'warning' ? 'border-amber-500/20' : ($tone === 'green' ? 'border-emerald-500/20' : 'border-white/5');
                @endphp
                <button type="button" class="rounded-2xl border {{ $border }} bg-background/25 p-4 text-start hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'open_activity'}}))">
                    <div class="text-sm font-semibold">{{ $label }}</div>
                    <div class="mt-1 text-xs opacity-60">{{ $meta }}</div>
                </button>
            @endforeach
        </div>
    </x-card>
</div>
{{-- end: live activity --}}

{{-- begin: work workspace --}}
@php
    $tzUserId = auth()->id();
    $tzTeamId = auth()->user()?->team_id;

    $pickTable = function (array $candidates) {
        foreach ($candidates as $t) {
            if (\Illuminate\Interactions\Facades\Schema::hasTable($t)) return $t;
        }
        return null;
    };

    $tableLink = function (array $routeNames, string $fallback = '#') {
        foreach ($routeNames as $name) {
            if (\Illuminate\Interactions\Facades\Route::has($name)) {
                return route($name);
            }
        }
        return $fallback;
    };

    $fetch = function (?string $table, int $limit = 10, ?callable $mutator = null) use ($tzUserId, $tzTeamId) {
        if (!$table) return collect();
        try {
            $q = \Illuminate\Interactions\Facades\DB::table($table);
            if (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'user_id')) $q->where('user_id', $tzUserId);
            if ($tzTeamId && \Illuminate\Interactions\Facades\Schema::hasColumn($table, 'team_id')) $q->where('team_id', $tzTeamId);
            if ($mutator) $mutator($q, $table);
            if (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'scheduled_for')) {
                $q->orderBy('scheduled_for', 'asc');
            } elseif (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'scheduled_at')) {
                $q->orderBy('scheduled_at', 'asc');
            } elseif (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'updated_at')) {
                $q->orderBy('updated_at', 'desc');
            } elseif (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'created_at')) {
                $q->orderBy('created_at', 'desc');
            } elseif (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'id')) {
                $q->orderBy('id', 'desc');
            }
            return $q->limit($limit)->get();
        } catch (\Throwable $e) {
            return collect();
        }
    };

    $tJobs = $pickTable(['tz_jobs', 'tz_work_orders']);
    $tTasks = $pickTable(['tz_tasks', 'tz_work_tasks', 'tz_project_tasks']);
    $tSchedule = $pickTable(['tz_job_schedule', 'tz_schedules', 'tz_bookings']);
    $tAgents = $pickTable(['tz_job_agents', 'tz_agents']);
    $tSites = $pickTable(['tz_sites', 'tz_properties', 'tz_locations']);

    $rowsJobs = $fetch($tJobs, 12);
    $rowsTasks = $fetch($tTasks, 12);
    $rowsSchedule = $fetch($tSchedule, 12);
    $rowsOverdue = $fetch($tJobs, 12, function ($q, $table) {
        $now = now();
        if (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'status')) {
            $q->whereNotIn('status', ['completed', 'done', 'closed']);
        }
        if (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'due_at')) {
            $q->where('due_at', '<', $now);
        } elseif (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'scheduled_at')) {
            $q->where('scheduled_at', '<', $now);
        }
    });
    $rowsHistory = $fetch($tJobs, 12, function ($q, $table) {
        if (\Illuminate\Interactions\Facades\Schema::hasColumn($table, 'status')) {
            $q->whereIn('status', ['completed', 'done', 'closed']);
        }
    });
    $rowsAgents = $fetch($tAgents, 12);

    $jobsListUrl = $tableLink(['dashboard.user.work.index', 'dashboard.user.jobs.index', 'dashboard.user.work.jobs.index']);
    $tasksListUrl = $tableLink(['dashboard.user.tasks.index', 'dashboard.user.work.tasks.index']);
    $scheduleListUrl = $tableLink(['dashboard.user.schedule.index', 'dashboard.user.bookings.index']);
    $agentsListUrl = $tableLink(['dashboard.user.work.agents.index', 'dashboard.user.agents.index']);

    $workspaceTabs = [
        'jobs' => ['title' => __('Jobs'), 'viewAll' => $jobsListUrl],
        'tasks' => ['title' => __('Tasks'), 'viewAll' => $tasksListUrl],
        'schedule' => ['title' => __('Schedule'), 'viewAll' => $scheduleListUrl],
        'overdue' => ['title' => __('Overdue'), 'viewAll' => $jobsListUrl],
        'history' => ['title' => __('History'), 'viewAll' => $jobsListUrl],
        'agents' => ['title' => __('Agents'), 'viewAll' => $agentsListUrl],
        'activity' => ['title' => __('Activity'), 'viewAll' => $jobsListUrl],
    ];

    $staticEssentialTemplates = [
        ['title' => __('Dispatch Planner'), 'desc' => __('Build todays dispatch run, travel order, and crew sequence'), 'action' => __('Open'), 'intent' => 'dispatch_plan'],
        ['title' => __('Staffing Optimizer'), 'desc' => __('Fill unassigned jobs using current capacity and load signals'), 'action' => __('Run'), 'intent' => 'staffing_optimize'],
        ['title' => __('Inspection Checklist'), 'desc' => __('Launch inspection and quality flow from a live job or site'), 'action' => __('Use'), 'intent' => 'inspection_run'],
        ['title' => __('Recurring Setup'), 'desc' => __('Create repeat service cadence, windows, and recurrence rules'), 'action' => __('Start'), 'intent' => 'recurring_setup'],
        ['title' => __('Customer Intake'), 'desc' => __('Capture customer details and pass them to Connect Core cleanly'), 'action' => __('Open'), 'intent' => 'customer_intake'],
        ['title' => __('Overdue Recovery'), 'desc' => __('Resolve late work fast with recovery actions and updates'), 'action' => __('Recover'), 'intent' => 'overdue_recovery'],
    ];

    $activityEvents = collect();
    $jobEventTable = $pickTable(['tz_job_events', 'tz_events', 'work_job_events']);
    if ($jobEventTable) {
        $activityEvents = $fetch($jobEventTable, 10);
    }
    if ($activityEvents->isEmpty()) {
        $activityEvents = collect([
            (object) ['label' => __('Job assigned to Sarah'), 'meta' => __('Dispatch Agent • 5 min ago'), 'tone' => 'accent'],
            (object) ['label' => __('Checklist completed for Docklands Office'), 'meta' => __('Checklist Agent • 12 min ago'), 'tone' => 'green'],
            (object) ['label' => __('Proof missing on Morning Deep Clean'), 'meta' => __('Trust • 18 min ago'), 'tone' => 'warning'],
            (object) ['label' => __('Schedule gap found tomorrow at 2pm'), 'meta' => __('TimeEngine • 24 min ago'), 'tone' => 'default'],
        ]);
    }
@endphp

<div class="grid w-full grid-cols-1 gap-8" id="status">
    <x-card class="w-full" size="md" x-data="{ tab: localStorage.getItem('titan-work-tab') || 'jobs' }" x-init="$watch('tab', value => localStorage.setItem('titan-work-tab', value))">
        <x-slot:head class="border-0 pb-0 pt-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h4 class="m-0 text-[17px]">{{ __('Work Workspace') }}</h4>
                    <p class="mt-1 text-xs opacity-60">{{ __('Scrollable live tables for jobs tasks schedule history and agent alerts.') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-[11px] opacity-70">
                    <span>{{ __('Clickable rows') }}</span>
                    <span>•</span>
                    <span>{{ __('Sticky tabs') }}</span>
                    <span>•</span>
                    <span>{{ __('Internal scroll') }}</span>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($workspaceTabs as $key => $tabMeta)
                    <button type="button"
                        class="rounded-full px-3 py-1 text-xs font-semibold transition"
                        :class="tab === '{{ $key }}' ? 'bg-primary text-primary-foreground' : 'bg-background/40 text-foreground hover:bg-background/60'"
                        @click="tab='{{ $key }}'">
                        {{ $tabMeta['title'] }}
                    </button>
                @endforeach
            </div>
        </x-slot:head>

        <div class="mt-4 rounded-2xl border border-white/5 bg-background/20">
            <div class="flex items-center justify-between gap-3 border-b border-white/5 px-4 py-3 text-xs">
                <div class="font-semibold opacity-70">{{ __('Click a row to drill in or continue in the related workspace.') }}</div>
                <template x-for="(label, key) in {jobs:'{{ __('Leads') }}',tasks:'{{ __('Tasks') }}',schedule:'{{ __('Schedule') }}',overdue:'{{ __('Follow-ups Due') }}',history:'{{ __('History') }}',agents:'{{ __('Agents') }}',activity:'{{ __('Activity') }}'}" :key="key">
                    <a x-show="tab === key"
                        class="font-bold text-foreground"
                        :href="{
                            jobs: '{{ $jobsListUrl }}',
                            tasks: '{{ $tasksListUrl }}',
                            schedule: '{{ $scheduleListUrl }}',
                            overdue: '{{ $jobsListUrl }}',
                            history: '{{ $jobsListUrl }}',
                            agents: '{{ $agentsListUrl }}',
                            activity: '{{ $jobsListUrl }}'
                        }[key] || '#'">
                        {{ __('Browse Templates') }}
                    </a>
                </template>
            </div>

            <div class="max-h-[540px] overflow-auto">
                <div x-show="tab === 'jobs'" x-transition>
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-background/95 text-xs uppercase opacity-60 backdrop-blur">
                            <tr class="border-b border-white/5">
                                <th class="px-4 py-3 text-start">{{ __('Job') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Customer/Site') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('When') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Priority') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rowsJobs as $r)
                                @php
                                    $href = $jobsListUrl !== '#' ? $jobsListUrl . (str_contains($jobsListUrl, '?') ? '&' : '?') . 'focus=' . ($r->id ?? '') : '#';
                                    $name = $r->title ?? $r->job_name ?? $r->name ?? ('#' . ($r->id ?? ''));
                                    $site = $r->customer_name ?? $r->site_name ?? $r->location_name ?? $r->address ?? '-';
                                    $when = $r->scheduled_at ?? $r->scheduled_for ?? $r->due_at ?? $r->created_at ?? '-';
                                    $status = $r->status ?? $r->state ?? __('Open');
                                    $priority = $r->priority ?? $r->urgency ?? '-';
                                @endphp
                                <tr class="cursor-pointer border-b border-white/5 hover:bg-background/30" @if($href !== '#') onclick="window.location='{{ $href }}'" @endif>
                                    <td class="px-4 py-3 font-semibold">{{ $name }}</td>
                                    <td class="px-4 py-3">{{ $site }}</td>
                                    <td class="px-4 py-3">{{ $when }}</td>
                                    <td class="px-4 py-3">{{ $status }}</td>
                                    <td class="px-4 py-3">{{ $priority }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-6 opacity-70" colspan="5">{{ __('No jobs yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div x-show="tab === 'tasks'" x-transition>
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-background/95 text-xs uppercase opacity-60 backdrop-blur">
                            <tr class="border-b border-white/5">
                                <th class="px-4 py-3 text-start">{{ __('Task') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Job/Project') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Owner') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Updated') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rowsTasks as $r)
                                @php
                                    $href = $tasksListUrl !== '#' ? $tasksListUrl . (str_contains($tasksListUrl, '?') ? '&' : '?') . 'focus=' . ($r->id ?? '') : '#';
                                    $name = $r->title ?? $r->task_name ?? $r->name ?? ('#' . ($r->id ?? ''));
                                    $job = $r->job_title ?? $r->project_name ?? $r->parent_name ?? '-';
                                    $owner = $r->assigned_to_name ?? $r->owner_name ?? $r->user_name ?? '-';
                                    $status = $r->status ?? $r->state ?? __('Open');
                                    $updated = $r->updated_at ?? $r->created_at ?? '-';
                                @endphp
                                <tr class="cursor-pointer border-b border-white/5 hover:bg-background/30" @if($href !== '#') onclick="window.location='{{ $href }}'" @endif>
                                    <td class="px-4 py-3 font-semibold">{{ $name }}</td>
                                    <td class="px-4 py-3">{{ $job }}</td>
                                    <td class="px-4 py-3">{{ $owner }}</td>
                                    <td class="px-4 py-3">{{ $status }}</td>
                                    <td class="px-4 py-3 opacity-70">{{ $updated }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-6 opacity-70" colspan="5">{{ __('No tasks yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div x-show="tab === 'schedule'" x-transition>
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-background/95 text-xs uppercase opacity-60 backdrop-blur">
                            <tr class="border-b border-white/5">
                                <th class="px-4 py-3 text-start">{{ __('Date') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Window') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Job') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Assigned') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('State') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rowsSchedule as $r)
                                @php
                                    $href = $scheduleListUrl !== '#' ? $scheduleListUrl : '#';
                                    $date = $r->date ?? $r->scheduled_for ?? $r->scheduled_at ?? '-';
                                    $window = $r->window ?? $r->time_slot ?? $r->start_time ?? '-';
                                    $job = $r->job_title ?? $r->title ?? $r->name ?? ('#' . ($r->job_id ?? $r->id ?? ''));
                                    $assigned = $r->assigned_to_name ?? $r->staff_name ?? $r->assignee ?? __('Unassigned');
                                    $state = $r->status ?? $r->state ?? '-';
                                @endphp
                                <tr class="cursor-pointer border-b border-white/5 hover:bg-background/30" @if($href !== '#') onclick="window.location='{{ $href }}'" @endif>
                                    <td class="px-4 py-3 font-semibold">{{ $date }}</td>
                                    <td class="px-4 py-3">{{ $window }}</td>
                                    <td class="px-4 py-3">{{ $job }}</td>
                                    <td class="px-4 py-3">{{ $assigned }}</td>
                                    <td class="px-4 py-3">{{ $state }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-6 opacity-70" colspan="5">{{ __('No scheduled work yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div x-show="tab === 'overdue'" x-transition>
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-background/95 text-xs uppercase opacity-60 backdrop-blur">
                            <tr class="border-b border-white/5">
                                <th class="px-4 py-3 text-start">{{ __('Job') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Due') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Assigned') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Priority') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rowsOverdue as $r)
                                @php
                                    $href = $jobsListUrl !== '#' ? $jobsListUrl . (str_contains($jobsListUrl, '?') ? '&' : '?') . 'focus=' . ($r->id ?? '') : '#';
                                    $name = $r->title ?? $r->job_name ?? $r->name ?? ('#' . ($r->id ?? ''));
                                    $due = $r->due_at ?? $r->scheduled_at ?? '-';
                                    $status = $r->status ?? $r->state ?? __('Overdue');
                                    $assigned = $r->assigned_to_name ?? $r->assigned_user_id ?? __('Unassigned');
                                    $priority = $r->priority ?? $r->urgency ?? '-';
                                @endphp
                                <tr class="cursor-pointer border-b border-white/5 hover:bg-background/30" @if($href !== '#') onclick="window.location='{{ $href }}'" @endif>
                                    <td class="px-4 py-3 font-semibold">{{ $name }}</td>
                                    <td class="px-4 py-3">{{ $due }}</td>
                                    <td class="px-4 py-3">{{ $status }}</td>
                                    <td class="px-4 py-3">{{ $assigned }}</td>
                                    <td class="px-4 py-3">{{ $priority }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-6 opacity-70" colspan="5">{{ __('No overdue jobs.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div x-show="tab === 'history'" x-transition>
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-background/95 text-xs uppercase opacity-60 backdrop-blur">
                            <tr class="border-b border-white/5">
                                <th class="px-4 py-3 text-start">{{ __('Job') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Customer/Site') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Conversions') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Proof') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rowsHistory as $r)
                                @php
                                    $href = $jobsListUrl !== '#' ? $jobsListUrl . (str_contains($jobsListUrl, '?') ? '&' : '?') . 'focus=' . ($r->id ?? '') : '#';
                                    $name = $r->title ?? $r->job_name ?? $r->name ?? ('#' . ($r->id ?? ''));
                                    $site = $r->customer_name ?? $r->site_name ?? $r->location_name ?? '-';
                                    $completed = $r->completed_at ?? $r->updated_at ?? '-';
                                    $status = $r->status ?? $r->state ?? __('Completed');
                                    $proof = $r->proof_status ?? $r->evidence_status ?? __('Pending');
                                @endphp
                                <tr class="cursor-pointer border-b border-white/5 hover:bg-background/30" @if($href !== '#') onclick="window.location='{{ $href }}'" @endif>
                                    <td class="px-4 py-3 font-semibold">{{ $name }}</td>
                                    <td class="px-4 py-3">{{ $site }}</td>
                                    <td class="px-4 py-3">{{ $completed }}</td>
                                    <td class="px-4 py-3">{{ $status }}</td>
                                    <td class="px-4 py-3">{{ $proof }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-6 opacity-70" colspan="5">{{ __('No completed work yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


                <div x-show="tab === 'activity'" x-transition>
                    <div class="divide-y divide-white/5">
                        @forelse ($activityEvents as $event)
                            @php
                                $label = $event->label ?? $event->title ?? $event->event_name ?? ($event->event_type ?? __('Work event'));
                                $meta = $event->meta ?? $event->created_at ?? __('Latest signal');
                                $tone = $event->tone ?? 'default';
                                $dot = $tone === 'warning' ? 'bg-amber-400' : ($tone === 'green' ? 'bg-emerald-400' : ($tone === 'accent' ? 'bg-primary' : 'bg-foreground/40'));
                            @endphp
                            <button type="button" class="flex w-full items-start gap-3 px-4 py-4 text-start hover:bg-background/30" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'open_activity'}}))">
                                <span class="mt-1 inline-block size-2.5 rounded-full {{ $dot }}"></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-semibold">{{ $label }}</span>
                                    <span class="mt-1 block text-xs opacity-60">{{ $meta }}</span>
                                </span>
                                <span class="text-xs font-semibold opacity-50">{{ __('Open') }}</span>
                            </button>
                        @empty
                            <div class="px-4 py-6 text-sm opacity-70">{{ __('No activity yet.') }}</div>
                        @endforelse
                    </div>
                </div>

                <div x-show="tab === 'agents'" x-transition>
                    <table class="w-full text-sm">
                        <thead class="sticky top-0 z-10 bg-background/95 text-xs uppercase opacity-60 backdrop-blur">
                            <tr class="border-b border-white/5">
                                <th class="px-4 py-3 text-start">{{ __('Agent') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Job') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Updated') }}</th>
                                <th class="px-4 py-3 text-start">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rowsAgents as $r)
                                @php
                                    $href = $agentsListUrl !== '#' ? $agentsListUrl : '#';
                                    $agent = $r->agent_type ?? $r->type ?? $r->name ?? __('Assistant');
                                    $job = $r->job_title ?? $r->job_id ?? '-';
                                    $status = $r->status ?? $r->state ?? __('Active');
                                    $updated = $r->updated_at ?? $r->created_at ?? '-';
                                    $action = $r->next_action ?? __('Review');
                                @endphp
                                <tr class="cursor-pointer border-b border-white/5 hover:bg-background/30" @if($href !== '#') onclick="window.location='{{ $href }}'" @endif>
                                    <td class="px-4 py-3 font-semibold">{{ \Illuminate\Interactions\Str::headline((string) $agent) }}</td>
                                    <td class="px-4 py-3">{{ $job }}</td>
                                    <td class="px-4 py-3">{{ $status }}</td>
                                    <td class="px-4 py-3 opacity-70">{{ $updated }}</td>
                                    <td class="px-4 py-3">{{ $action }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-4 py-6 opacity-70" colspan="5">{{ __('No active assistants yet.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-card>
</div>
{{-- end: work workspace --}}

{{-- begin: favorite templates --}}
        <x-card
            class="w-full"
            id="templates"
            size="md"
        >
            <x-slot:head
                class="border-0 pb-0 pt-5"
            >
                <div class="flex items-center justify-between">
                    <h4 class="m-0 text-[17px]">{{ __('Essential Work Templates') }}</h4>
                    <x-button
                        variant="link"
                        href="{{ route('dashboard.user.openai.list') if Route::has('dashboard.user.openai.list') else '#' }}"
                    >
                        <span class="text-nowrap font-bold text-foreground"> {{ __('View All') }} </span>
                        <x-tabler-chevron-right class="size-4 rtl:rotate-180" />
                    </x-button>
                </div>
            </x-slot:head>

            
            <div class="mt-4 grid gap-3 lg:grid-cols-2 xl:grid-cols-3">
                <button type="button" class="rounded-2xl border border-white/5 bg-background/25 p-4 text-start hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'launch_existing_job_wizard'}}))">
                    <div class="text-sm font-semibold">{{ __('Launch New Job') }}</div>
                    <div class="mt-1 text-xs opacity-60">{{ __('Open the embedded work wizard already built for job creation.') }}</div>
                </button>
                <button type="button" class="rounded-2xl border border-white/5 bg-background/25 p-4 text-start hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'launch_quote_card'}}))">
                    <div class="text-sm font-semibold">{{ __('Launch Quote') }}</div>
                    <div class="mt-1 text-xs opacity-60">{{ __('Open the quote card once the quote flow agent ships it into this hub.') }}</div>
                </button>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($staticEssentialTemplates as $template)
                    <button type="button" class="rounded-2xl border border-white/5 bg-background/25 p-4 text-start transition hover:bg-background/40" @click="window.dispatchEvent(new CustomEvent('titan:action',{detail:{intent:'{{ $template['intent'] }}'}}))">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold">{{ $template['title'] }}</div>
                                <div class="mt-1 text-xs opacity-60">{{ $template['desc'] }}</div>
                            </div>
                            <span class="rounded-full bg-primary/15 px-2.5 py-1 text-[11px] font-semibold text-primary">{{ $template['action'] }}</span>
                        </div>
                    </button>
                @endforeach
            </div>
        </x-card>
        {{-- end: favorite templates --}}

        @includeFirst(['announcement::partials.dashboard', 'vendor.empty'])

        {{-- begin: submit ticket --}}
        <x-card
            class="flex w-full flex-col justify-center lg:w-[48%]"
            id="submit-ticket"
            size="lg"
        >
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-3">
                    <div class="inline-grid size-36 items-center justify-center rounded-full bg-foreground/[3%]">
                        <img
                            src="{{ asset('images/icons/submit-ticket.png') }}"
                            alt=""
                        >
                    </div>
                    <div class="flex flex-col items-center">
                        <h3 class="text-center">
                            @lang('Have a question?')
                            <span class="mt-1.5 block opacity-50">
                                @lang('We’re here to help you.')
                            </span>
                        </h3>
                    </div>
                </div>
                <x-button
                    class="mx-auto w-fit text-xs text-heading-foreground hover:bg-primary"
                    variant="ghost-shadow"
                    href="{{ route('dashboard.support.list') }}"
                >
                    <x-tabler-plus class="size-3.5" />
                    {{ __('Submit a Ticket') }}
                </x-button>
            </div>
        </x-card>
        {{-- end: submit ticket --}}
    </div>
@endsection

@push('script')
    @if ($app_is_not_demo)
        @includeFirst(['onboarding::include.introduction', 'panel.admin.onboarding.include.introduction', 'vendor.empty'])
        @includeFirst(['onboarding-pro::include.introduction', 'panel.admin.onboarding-pro.include.introduction', 'vendor.empty'])
    @endif
    @if (Route::has('dashboard.user.dash_notify_seen'))
        <script>
            function dismiss() {
                // localStorage.setItem('lqd-announcement-dismissed', true);
                document.querySelector('.lqd-announcement').style.display = 'none';
                $.ajax({
                    url: '{{ route('dashboard.user.dash_notify_seen') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        /* console.log(response); */
                    }
                });
            }
        </script>
    @endif
@endpush
