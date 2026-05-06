{{--
    TitanPro — Dynamic Dashboard Index (polished override)

    This view overrides the default LaraZeus Dynamic Dashboard index page.
    It renders all active layouts or shows a friendly empty-state when none exist.

    Variables available from DynamicDashboard controller:
        $layouts  — Collection<LaraZeus\DynamicDashboard\Models\Layout>
--}}
@php
    $appName = config('app.name', 'TitanPro');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — {{ $appName }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/filament/admin/theme.css'])
    <style>
        body { font-family: 'Figtree', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 min-h-screen antialiased">

<header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $appName }}</span>
            <span class="text-gray-400 dark:text-gray-500 text-sm">Dashboard</span>
        </div>
        <nav class="flex items-center gap-4">
            @auth
                <a href="{{ url('/admin') }}"
                   class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition-colors">
                    Admin Panel
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="text-sm font-medium text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        Log out
                    </button>
                </form>
            @else
                <a href="{{ url('/admin/login') }}"
                   class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors">
                    Log in
                </a>
            @endauth
        </nav>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    @if(isset($layouts) && $layouts->isNotEmpty())
        @foreach($layouts->where('is_active', true) as $layout)
            <section class="mb-10">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                    {{ $layout->layout_title }}
                </h1>

                @php
                    $widgets = is_string($layout->widgets)
                        ? json_decode($layout->widgets, true)
                        : (array) $layout->widgets;
                    $widgets = is_array($widgets) ? $widgets : [];
                @endphp

                @if(empty($widgets))
                    <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 p-10 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                        </svg>
                        <p class="mt-4 text-base font-medium text-gray-500 dark:text-gray-400">This layout has no widgets yet.</p>
                        @auth
                            <a href="{{ url('/admin/layouts/' . $layout->id . '/edit') }}"
                               class="mt-3 inline-block text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                Add widgets →
                            </a>
                        @endauth
                    </div>
                @else
                    {{-- Render each widget using the Zeus DynamicDashboard service --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                        @foreach($widgets as $widget)
                            @php
                                $type = $widget['type'] ?? null;
                                $data = $widget['data'] ?? [];
                                $rendered = null;

                                if ($type) {
                                    $registeredWidgets = config('zeus-dynamic-dashboard.widgets', []);
                                    foreach ($registeredWidgets as $widgetClass) {
                                        // Only instantiate classes that implement the Widget contract
                                        if (
                                            ! is_string($widgetClass)
                                            || ! class_exists($widgetClass)
                                            || ! in_array(\LaraZeus\DynamicDashboard\Contracts\Widget::class, class_implements($widgetClass) ?: [])
                                        ) {
                                            continue;
                                        }

                                        try {
                                            $instance = app($widgetClass);
                                        } catch (\Throwable) {
                                            continue;
                                        }

                                        if ($instance->enabled() && method_exists($instance, 'form')) {
                                            $block = $instance->form();
                                            if (method_exists($block, 'getName') && $block->getName() === $type) {
                                                $rendered = $instance->renderWidget($data);
                                                break;
                                            }
                                        }
                                    }
                                }
                            @endphp

                            @if($rendered)
                                <div class="col-span-1">
                                    @if($rendered instanceof \Illuminate\Contracts\View\View)
                                        {!! $rendered->render() !!}
                                    @else
                                        {!! $rendered !!}
                                    @endif
                                </div>
                            @elseif($type)
                                <div class="col-span-1 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-4 text-sm text-gray-400 dark:text-gray-500 italic">
                                    Widget "{{ $type }}" not found.
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </section>
        @endforeach
    @else
        {{-- Empty state — no layouts exist --}}
        <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-8 py-16 text-center shadow-sm">
            <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
            </svg>

            <h2 class="mt-6 text-xl font-bold text-gray-900 dark:text-gray-100">No dashboard layouts yet</h2>
            <p class="mt-2 text-base text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                Create your first dashboard layout in the admin panel and start adding widgets.
            </p>

            @auth
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ url('/admin/layouts/create') }}"
                       class="rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition-colors shadow-sm">
                        Create Layout
                    </a>
                    <a href="{{ url('/admin') }}"
                       class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                        Go to Admin Panel
                    </a>
                </div>
            @else
                <p class="mt-4 text-sm text-gray-400 dark:text-gray-500">
                    <a href="{{ url('/admin/login') }}" class="text-primary-600 dark:text-primary-400 hover:underline">Log in</a>
                    to manage dashboards.
                </p>
            @endauth
        </div>
    @endif

</main>

<footer class="mt-16 border-t border-gray-200 dark:border-gray-700 py-6 text-center text-xs text-gray-400 dark:text-gray-500">
    Powered by {{ $appName }} &middot; Dynamic Dashboard
</footer>

</body>
</html>
