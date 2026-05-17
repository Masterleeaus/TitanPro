<x-card class="mb-0">
    <div class="px-5 py-5">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h3 class="mb-0 font-heading text-lg font-semibold">{{ __('Recent operators') }}</h3>
            <a href="{{ route('dashboard.user.client_portal.builder') }}" class="text-2xs text-primary">{{ __('Manage') }}</a>
        </div>
        <div class="space-y-3">
            @forelse (($titan_operators->items() ?? []) as $operator)
                <div class="flex items-center justify-between gap-4 rounded-2xl border px-4 py-3">
                    <div>
                        <p class="mb-1 font-medium text-heading-foreground">{{ data_get($operator, 'title', __('Untitled operator')) }}</p>
                        <p class="mb-0 text-2xs text-heading-foreground/70">{{ __('UUID') }}: {{ data_get($operator, 'uuid', '—') }}</p>
                    </div>
                    <a href="{{ route('dashboard.user.client_portal.embed', $operator) }}" class="text-2xs text-primary">{{ __('Embed') }}</a>
                </div>
            @empty
                @include('titan_operator::default.panel.user.client-portal.partials.empty-state', ['message' => __('No operators created yet.')])
            @endforelse
        </div>
    </div>
</x-card>
