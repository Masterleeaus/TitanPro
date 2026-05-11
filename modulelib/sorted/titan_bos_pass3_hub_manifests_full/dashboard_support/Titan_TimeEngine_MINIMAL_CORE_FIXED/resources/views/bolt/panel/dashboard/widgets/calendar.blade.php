<x-card>
    <div class="flex items-center justify-between">
        <div>
            <h3 class="mb-1 text-base font-semibold">{{ __('Titan Calendar') }}</h3>
            <p class="mb-0 text-sm opacity-70">{{ __('Open the unified schedule for jobs, invoices, posts and manual events.') }}</p>
        </div>
        <a href="{{ route('dashboard.user.calendar.index') }}" class="btn btn-sm btn-primary">{{ __('Open') }}</a>
    </div>
</x-card>
