<div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="mb-1 text-2xl font-bold">{{ __('Titan Calendar') }}</h1>
        <p class="mb-0 opacity-70">{{ __('Unified business calendar across manual events, jobs, invoices and automation schedules.') }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('dashboard.user.calendar.scheduled-tasks') }}" class="btn btn-secondary">{{ __('Scheduled tasks') }}</a>
        <a href="{{ route('dashboard.user.calendar.settings') }}" class="btn btn-primary">{{ __('Sync settings') }}</a>
    </div>
</div>
