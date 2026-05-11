<x-card class="mb-0">
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
        @php
            $portalNav = [
                ['label' => __('Dashboard'), 'route' => 'dashboard.user.client_portal.index'],
                ['label' => __('Builder'), 'route' => 'dashboard.user.client_portal.builder'],
                ['label' => __('Inbox'), 'route' => 'dashboard.user.client_portal.inbox'],
                ['label' => __('Templates'), 'route' => 'dashboard.user.client_portal.templates'],
                ['label' => __('Runtime'), 'route' => 'dashboard.user.client_portal.runtime'],
                ['label' => __('History'), 'route' => 'dashboard.user.client_portal.history'],
                ['label' => __('Preview'), 'route' => 'dashboard.user.client_portal.preview'],
                ['label' => __('Install'), 'route' => 'dashboard.user.client_portal.install'],
            ];
        @endphp

        <div class="flex flex-wrap items-center gap-3">
            @foreach ($portalNav as $item)
                <a href="{{ route($item['route'], ['template' => data_get($selectedPortalTemplate, 'slug')]) }}"
                   class="inline-flex items-center rounded-full border px-4 py-2 text-sm font-medium {{ request()->routeIs($item['route']) ? 'bg-primary text-white border-primary' : 'border-heading-foreground/10 text-heading-foreground hover:border-primary/30' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        <span class="rounded-full border px-3 py-1 text-2xs">{{ __('Template') }}: {{ data_get($selectedPortalTemplate, 'title', __('Client Portal')) }}</span>
    </div>
</x-card>
