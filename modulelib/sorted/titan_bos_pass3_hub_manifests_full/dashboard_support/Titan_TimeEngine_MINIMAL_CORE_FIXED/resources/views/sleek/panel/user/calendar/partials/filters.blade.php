<x-card>
    <div class="grid gap-3 md:grid-cols-4">
        <div>
            <label class="mb-1 block text-xs uppercase opacity-70">{{ __('Source') }}</label>
            <select id="calendar-source-filter" class="form-control">
                <option value="">{{ __('All sources') }}</option>
                <option value="manual">{{ __('Manual') }}</option>
                <option value="job">{{ __('Jobs') }}</option>
                <option value="invoice">{{ __('Invoices') }}</option>
                <option value="schedule">{{ __('Scheduled tasks') }}</option>
                <option value="social_post">{{ __('Social posts') }}</option>
                <option value="scheduled_job">{{ __('Chatbot jobs') }}</option>
                <option value="google">{{ __('Google') }}</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs uppercase opacity-70">{{ __('Status') }}</label>
            <select id="calendar-status-filter" class="form-control">
                <option value="">{{ __('All statuses') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="pending">{{ __('Pending') }}</option>
                <option value="draft">{{ __('Draft') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
                <option value="scheduled">{{ __('Scheduled') }}</option>
                <option value="external">{{ __('External') }}</option>
            </select>
        </div>
        <div class="md:col-span-2 flex items-end gap-3">
            <a href="{{ route('dashboard.user.calendar.settings') }}" class="btn btn-outline-primary w-full">{{ __('Calendar settings') }}</a>
            <a href="{{ route('dashboard.user.calendar.scheduled-tasks') }}" class="btn btn-secondary w-full">{{ __('Task queue') }}</a>
        </div>
    </div>
</x-card>
