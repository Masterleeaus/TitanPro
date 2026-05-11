<div
    class="mt-10"
    x-data="Command AgentAccounts"
>
    <h2 class="mb-7">
        @lang('Manage Accounts')
    </h2>

    <form class="relative mb-9 w-full">
        <x-tabler-search
            class="absolute start-6 top-1/2 -translate-y-1/2 text-heading-foreground"
            stroke-width="1.5"
        />
        <x-forms.input
            class="h-[52px] rounded-full bg-foreground/5 ps-14 text-foreground placeholder:text-foreground"
            name="search"
            type="text"
            placeholder="{{ __('Search for items') }}"
            x-model="_searchStr"
        />
    </form>

    @if (filled($containers))
        <x-table>
            <x-slot:head>
                <th>
                    {{ __('Name / Username') }}
                </th>

                <th>
                    {{ __('Registered') }}
                </th>

                <th>
                    {{ __('Status') }}
                </th>

                <th>
                    {{ __('Container') }}
                </th>

                <th class="text-end">
                    {{ __('Actions') }}
                </th>
            </x-slot:head>

            <x-slot:body>
                @foreach ($containers as $container)
                    @include('command-agent::accounts.container-table-item', ['container' => $container])
                @endforeach
            </x-slot:body>
        </x-table>
    @else
        <h4>
            {{ __('No agents added yet.') }}
        </h4>
    @endif
</div>

@push('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('Command AgentAccounts', () => ({
                _searchStr: '',

                get searchString() {
                    return this._searchStr.trim().toLowerCase()
                }
            }))
        })
    </script>
@endpush
