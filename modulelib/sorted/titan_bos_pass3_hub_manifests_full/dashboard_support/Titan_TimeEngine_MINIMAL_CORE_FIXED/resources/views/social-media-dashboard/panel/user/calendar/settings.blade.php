@extends('panel.layout.app')
@section('title', __('Titan Calendar Settings'))

@section('content')
    <div class="py-10">
        <div class="container-xl space-y-6">
            <x-card>
                <h2 class="mb-3">{{ __('Sync status') }}</h2>
                <pre class="mb-0 whitespace-pre-wrap">{{ json_encode($syncSummary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </x-card>
            <x-card>
                <h2 class="mb-3">{{ __('Google adapter status') }}</h2>
                <pre class="mb-0 whitespace-pre-wrap">{{ json_encode($googleSummary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </x-card>
            <x-card>
                <h2 class="mb-3">{{ __('Sync accounts') }}</h2>
                <div class="overflow-auto">
                    <table class="table">
                        <thead><tr><th>{{ __('Provider') }}</th><th>{{ __('Account') }}</th><th>{{ __('Calendar ID') }}</th><th>{{ __('Mode') }}</th><th>{{ __('Last Sync') }}</th></tr></thead>
                        <tbody>
                        @forelse($syncAccounts as $account)
                            <tr>
                                <td>{{ $account->provider }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td>{{ $account->calendar_id }}</td>
                                <td>{{ $account->sync_mode }}</td>
                                <td>{{ optional($account->last_synced_at)->toDayDateTimeString() ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center opacity-70">{{ __('No sync accounts configured yet.') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
@endsection
