{{--
    TitanWork Control Panel view

    This Blade template provides the skeleton for the single-page control panel.
    It divides the page into a top half with metrics, widgets and right-hand
    cards (AI chat, shortcuts, settings) and a bottom half containing a
    tabbed table surface.

    The actual data for each section is injected via Livewire components or
    passed through from the controller. You can customise the layout using
    Tailwind classes or your preferred CSS framework.
--}}

<x-filament::page>
    <div class="space-y-6">
        <!-- Metrics strip -->
        <div class="grid grid-cols-3 gap-4">
            @foreach($metrics ?? [] as $metric)
                <div class="bg-white shadow rounded-xl p-4">
                    <div class="text-sm text-gray-500">{{ $metric['label'] }}</div>
                    <div class="text-2xl font-bold">{{ $metric['value'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Middle row with widgets and right column -->
        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2 space-y-4">
                <!-- Example widgets placeholder -->
                @foreach($widgets ?? [] as $widget)
                    <div class="bg-white shadow rounded-xl p-4">
                        <h3 class="font-semibold text-lg mb-2">{{ $widget['title'] }}</h3>
                        <!-- Widget content should be rendered here -->
                    </div>
                @endforeach
            </div>
            <div class="col-span-1 space-y-4">
                <!-- AI Chat card placeholder -->
                <div class="bg-white shadow rounded-xl p-4">
                    <h3 class="font-semibold text-lg mb-2">AI Chat</h3>
                    <p class="text-sm text-gray-500">Use voice or type to interact with Titan agents.</p>
                </div>
                <!-- Shortcuts card placeholder -->
                <div class="bg-white shadow rounded-xl p-4">
                    <h3 class="font-semibold text-lg mb-2">Shortcuts</h3>
                    <ul class="list-disc ml-5">
                        @foreach($shortcuts ?? [] as $shortcut)
                            <li>{{ $shortcut['label'] }}</li>
                        @endforeach
                    </ul>
                </div>
                <!-- Settings card placeholder -->
                <div class="bg-white shadow rounded-xl p-4">
                    <h3 class="font-semibold text-lg mb-2">Settings</h3>
                    <ul class="list-disc ml-5">
                        @foreach($settings ?? [] as $setting)
                            <li>{{ $setting['label'] }}: {{ $setting['value'] ? 'Enabled' : 'Disabled' }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom tabbed tables -->
        <div class="bg-white shadow rounded-xl p-4">
            <div>
                <ul class="flex space-x-4 border-b mb-4">
                    @foreach($tabs ?? [] as $tab)
                        <li>
                            <button type="button" wire:click="selectTab('{{ $tab['key'] }}')" class="py-2 px-4 @if($activeTab === $tab['key']) border-b-2 border-indigo-600 font-semibold @endif">
                                {{ $tab['label'] }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div>
                    <!-- Render the currently selected tab table here -->
                    @if(isset($activeTab) && isset($tableData[$activeTab]))
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr>
                                    @foreach(array_keys($tableData[$activeTab][0] ?? []) as $column)
                                        <th class="px-4 py-2 border-b text-left">{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tableData[$activeTab] as $row)
                                    <tr>
                                        @foreach($row as $value)
                                            <td class="px-4 py-2 border-b">{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::page>