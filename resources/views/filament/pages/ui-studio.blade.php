<x-filament-panels::page>
    @vite('resources/js/filament/ui-studio.js')

    {{--
        UI Studio — three-panel visual design surface.
        Left  : component tree / layer list
        Centre: drag-and-drop canvas preview
        Right : context-sensitive property editor
    --}}

    <style>
        .ui-studio-shell {
            display: grid;
            grid-template-columns: 260px 1fr 300px;
            grid-template-rows: 1fr;
            height: calc(100vh - 10rem);
            min-height: 520px;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid rgba(0,0,0,0.08);
        }
        @media (max-width: 1024px) {
            .ui-studio-shell { grid-template-columns: 1fr; grid-template-rows: auto auto auto; height: auto; }
        }
        .studio-panel {
            overflow-y: auto;
            overflow-x: hidden;
        }
        .studio-canvas-item {
            transition: box-shadow 0.15s ease;
            cursor: grab;
        }
        .studio-canvas-item:active {
            cursor: grabbing;
        }
        .studio-canvas-item.sortable-ghost {
            opacity: 0.4;
        }
        .studio-canvas-item.sortable-chosen {
            box-shadow: 0 0 0 2px #2563eb;
        }
        .resize-handle {
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: col-resize;
            user-select: none;
        }
    </style>

    <div class="ui-studio-shell bg-white dark:bg-gray-900 shadow-sm">

        {{-- ── LEFT PANEL: Component tree ───────────────────────────── --}}
        <aside class="studio-panel border-r border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-gray-950 flex flex-col">
            <div class="px-4 py-3 border-b border-gray-200 dark:border-white/10">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Components</h3>
            </div>

            {{-- Component Design System Registry --}}
            <div class="px-3 py-3 border-b border-gray-200 dark:border-white/10">
                <p class="mb-2 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Design System</p>
                <div class="space-y-1">
                    @foreach (\App\Platform\Ui\ComponentRegistry::all() as $componentKey => $componentDef)
                        <button
                            type="button"
                            wire:click="styleComponent('{{ $componentKey }}')"
                            class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm transition-colors group
                                {{ $activeComponentKey === $componentKey && $activeTab === 'components'
                                    ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-semibold'
                                    : 'text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-sm' }}"
                        >
                            <x-heroicon-o-swatch class="h-4 w-4 text-gray-400 group-hover:text-primary-500 flex-shrink-0" />
                            <span class="flex-1 text-left text-xs">{{ $componentDef['label'] }}</span>
                            <span class="text-[10px] font-semibold text-primary-500 opacity-0 group-hover:opacity-100 transition-opacity">Style</span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Widget catalogue --}}
            <div class="px-3 py-3 flex-1">
                <p class="mb-2 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Widget Catalogue</p>
                <div class="space-y-1">
                    @foreach ($widgetCatalogue as $type => $label)
                        <button
                            type="button"
                            wire:click="addWidget('{{ $type }}')"
                            class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 hover:shadow-sm transition-colors group"
                        >
                            <x-heroicon-o-squares-2x2 class="h-4 w-4 text-gray-400 group-hover:text-primary-500 flex-shrink-0" />
                            {{ $label }}
                            <x-heroicon-o-plus class="h-3 w-3 ml-auto text-gray-300 group-hover:text-primary-500" />
                        </button>
                    @endforeach
                </div>

                {{-- Current layout layer list --}}
                @if (count($canvasWidgets) > 0)
                    <p class="mt-4 mb-2 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Canvas Layers</p>
                    <div class="space-y-1">
                        @foreach ($canvasWidgets as $widget)
                            <button
                                type="button"
                                wire:click="selectWidget('{{ $widget['id'] }}')"
                                class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-xs transition-colors
                                    {{ $selectedWidgetId === $widget['id']
                                        ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-semibold'
                                        : 'text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800' }}"
                            >
                                <x-heroicon-o-rectangle-stack class="h-3.5 w-3.5 flex-shrink-0" />
                                <span class="truncate">{{ $widget['label'] }}</span>
                                <button
                                    type="button"
                                    wire:click.stop="removeWidget('{{ $widget['id'] }}')"
                                    class="ml-auto text-gray-300 hover:text-red-400 transition-colors"
                                    title="Remove widget"
                                >
                                    <x-heroicon-o-x-mark class="h-3 w-3" />
                                </button>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </aside>

        {{-- ── CENTRE PANEL: Canvas ─────────────────────────────────── --}}
        <main class="studio-panel bg-gray-100 dark:bg-gray-800 flex flex-col">
            <div class="flex items-center justify-between px-4 py-2.5 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-white/10 text-xs text-gray-500 dark:text-gray-400">
                <span class="font-semibold">Canvas Preview</span>
                <span class="text-[11px] text-gray-400">Drag rows to reorder · Click to select · Resize columns in properties panel</span>
            </div>

            {{-- Widget canvas --}}
            <div class="flex-1 p-4 overflow-y-auto">
                @if (count($canvasWidgets) === 0)
                    <div class="flex flex-col items-center justify-center h-full text-center text-gray-400 dark:text-gray-600 py-20 select-none">
                        <x-heroicon-o-squares-plus class="h-14 w-14 mb-4 opacity-30" />
                        <p class="text-sm font-medium">Canvas is empty</p>
                        <p class="text-xs mt-1">Click a widget in the left panel to add it here.</p>
                    </div>
                @else
                    <div
                        id="studio-canvas"
                        x-data="studioCanvas($wire)"
                        x-init="init()"
                        class="space-y-3"
                    >
                        @foreach ($canvasWidgets as $widget)
                            <div
                                data-id="{{ $widget['id'] }}"
                                wire:key="widget-{{ $widget['id'] }}"
                                class="studio-canvas-item rounded-xl border bg-white dark:bg-gray-900 shadow-sm
                                    {{ $selectedWidgetId === $widget['id'] ? 'border-primary-400 ring-1 ring-primary-400' : 'border-gray-200 dark:border-white/10' }}"
                                style="width: {{ round($widget['columns'] / 12 * 100) }}%"
                                @click="$wire.selectWidget('{{ $widget['id'] }}')"
                            >
                                <div class="flex items-center justify-between px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-bars-3 class="h-4 w-4 text-gray-400 drag-handle cursor-grab" />
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $widget['label'] }}
                                        </span>
                                        <span class="text-[10px] font-mono text-gray-400 bg-gray-100 dark:bg-white/5 px-1.5 py-0.5 rounded">
                                            {{ $widget['type'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-gray-400">
                                            {{ $widget['columns'] }}/12 cols
                                        </span>
                                        <button
                                            type="button"
                                            wire:click.stop="removeWidget('{{ $widget['id'] }}')"
                                            class="text-gray-300 hover:text-red-400 transition-colors"
                                        >
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                        </button>
                                    </div>
                                </div>

                                {{-- Widget preview placeholder --}}
                                <div class="mx-4 mb-4 h-16 rounded-lg bg-gray-50 dark:bg-white/5 border border-dashed border-gray-200 dark:border-white/10 flex items-center justify-center">
                                    <span class="text-xs text-gray-400">{{ $widget['label'] }} preview</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>

        {{-- ── RIGHT PANEL: Property editor ─────────────────────────── --}}
        <aside class="studio-panel border-l border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 flex flex-col">

            {{-- Tab strip --}}
            <div class="flex border-b border-gray-200 dark:border-white/10 overflow-x-auto">
                @foreach (['branding' => 'Branding', 'layout' => 'Layout', 'menu' => 'Menu', 'components' => 'Components', 'marketplace' => 'Marketplace'] as $tab => $tabLabel)
                    <button
                        type="button"
                        wire:click="selectTab('{{ $tab }}')"
                        class="flex-none px-3 py-2.5 text-xs font-semibold transition-colors whitespace-nowrap
                            {{ $activeTab === $tab
                                ? 'text-primary-600 border-b-2 border-primary-500 bg-primary-50 dark:bg-primary-900/10'
                                : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}"
                    >
                        {{ $tabLabel }}
                    </button>
                @endforeach
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-5">

                {{-- ── Branding tab ───────────────────────────────────── --}}
                @if ($activeTab === 'branding')
                    <section>
                        <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Identity</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Panel name</label>
                                <input type="text" wire:model.live="panelName" class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300" placeholder="Your panel name" />
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Logo</label>
                                <input type="file" wire:model="logoUpload" accept="image/*" class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300" />
                                @error('logoUpload') <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p> @enderror
                                @if ($logoPath)
                                    <p class="mt-1 text-[11px] text-gray-400">Current: {{ $logoPath }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Favicon</label>
                                <input type="file" wire:model="faviconUpload" accept="image/*" class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300" />
                                @error('faviconUpload') <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p> @enderror
                                @if ($faviconPath)
                                    <p class="mt-1 text-[11px] text-gray-400">Current: {{ $faviconPath }}</p>
                                @endif
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Colors</h4>
                        <div class="space-y-3">
                            @foreach ([
                                'primaryColor'   => 'Primary',
                                'secondaryColor' => 'Secondary',
                                'accentColor'    => 'Accent',
                                'surfaceColor'   => 'Surface',
                            ] as $prop => $colorLabel)
                                <div class="flex items-center justify-between gap-2">
                                    <label class="text-xs text-gray-600 dark:text-gray-400 flex-1">{{ $colorLabel }}</label>
                                    <input
                                        type="color"
                                        wire:model.live="{{ $prop }}"
                                        class="h-7 w-10 cursor-pointer rounded border border-gray-200 dark:border-white/10 p-0.5"
                                        title="{{ $colorLabel }} color"
                                    />
                                    <input
                                        type="text"
                                        wire:model.live="{{ $prop }}"
                                        class="w-20 text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1 font-mono text-gray-700 dark:text-gray-300"
                                        placeholder="#000000"
                                    />
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Typography</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Font family</label>
                                <input type="text" wire:model.live="fontFamily" class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300" placeholder="Figtree" />
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Background</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Background type</label>
                                <select wire:model.live="backgroundType" class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300">
                                    <option value="none">None</option>
                                    <option value="gradient">Gradient CSS</option>
                                    <option value="image">Image URL</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Background value</label>
                                <input type="text" wire:model.live="backgroundValue" class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300" placeholder="linear-gradient(...)" />
                            </div>
                        </div>
                    </section>

                    <section>
                        <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Design token engine</h4>
                        <div class="rounded-lg border border-dashed border-gray-200 dark:border-white/10 bg-gray-50/70 dark:bg-white/5 px-3 py-3 text-xs text-gray-600 dark:text-gray-300">
                            Theme overrides are stored as semantic tokens in <code>titan_theme_tokens</code>. Component tokens inherit from those values, and you can export the full token set with <code>php artisan titan:tokens:export</code>.
                        </div>
                    </section>

                    {{-- Live preview swatch --}}
                    <section>
                        <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Preview</h4>
                        <div class="rounded-lg border border-gray-200 dark:border-white/10 p-3 space-y-2 text-xs">
                            <div class="flex gap-2 items-center">
                                <div class="h-8 w-8 rounded-md flex-shrink-0" style="background: {{ e($this->safeColor($primaryColor)) }}"></div>
                                <div>
                                    <p class="font-semibold" style="color: {{ e($this->safeColor($primaryColor)) }}">{{ e($panelName) }}</p>
                                    <p class="text-gray-400">Panel · {{ e($primaryColor) }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 items-center">
                                <div class="h-8 w-8 rounded-md flex-shrink-0" style="background: {{ e($this->safeColor($accentColor)) }}"></div>
                                <div>
                                    <p class="text-gray-500" style="font-family: {{ e($this->safeFont($fontFamily)) }}">{{ e($fontFamily) }}</p>
                                    <p class="text-gray-400">Body · {{ e($accentColor) }}</p>
                                </div>
                            </div>
                            <div class="h-8 rounded-md border border-dashed border-gray-200" style="background: {{ e($this->safeColor($surfaceColor)) }}"></div>
                            @php($backgroundPreviewStyle = $this->safeBackgroundStyle($backgroundType, $backgroundValue))
                            @if ($backgroundPreviewStyle)
                                <div class="h-12 rounded-md border border-dashed border-gray-200" style="{{ e($backgroundPreviewStyle) }}"></div>
                            @endif
                        </div>
                    </section>
                @endif

                {{-- ── Layout / Widget tab ─────────────────────────── --}}
                @if ($activeTab === 'layout')
                    @if ($selectedWidgetId !== null)
                        @php($selectedWidget = collect($canvasWidgets)->firstWhere('id', $selectedWidgetId))
                        @if ($selectedWidget)
                            <section>
                                <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">
                                    Widget: {{ $selectedWidget['label'] }}
                                </h4>

                                <div class="space-y-4">
                                    <div>
                                        <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">
                                            Column width (1 – 12)
                                        </label>
                                        <div class="flex items-center gap-3">
                                            <input
                                                type="range"
                                                min="1"
                                                max="12"
                                                value="{{ $selectedWidget['columns'] }}"
                                                wire:change="updateWidgetColumns('{{ $selectedWidget['id'] }}', $event.target.value)"
                                                class="flex-1 accent-primary-500"
                                            />
                                            <span class="text-xs font-mono text-gray-500 w-6 text-right">{{ $selectedWidget['columns'] }}</span>
                                        </div>
                                        <p class="mt-1 text-[10px] text-gray-400">
                                            {{ round($selectedWidget['columns'] / 12 * 100) }}% of canvas width
                                        </p>
                                    </div>

                                    <div>
                                        <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Widget type</label>
                                        <p class="text-xs font-mono text-gray-500 bg-gray-50 dark:bg-white/5 rounded px-2 py-1.5">{{ $selectedWidget['type'] }}</p>
                                    </div>

                                    {{-- ── Per-widget property fields ─────────────────── --}}
                                    @php($widgetSchema = \App\Filament\Pages\UiStudio\WidgetPropertyRegistry::schema($selectedWidget['type']))
                                    @if (count($widgetSchema) > 0)
                                        <div class="border-t border-gray-200 dark:border-white/10 pt-4 space-y-3">
                                            <p class="text-[11px] font-semibold uppercase tracking-widest text-gray-400">Properties</p>

                                            @foreach ($widgetSchema as $field)
                                                <div>
                                                    <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">
                                                        {{ $field['label'] }}
                                                    </label>

                                                    @if ($field['type'] === 'textarea')
                                                        <textarea
                                                            rows="{{ $field['rows'] ?? 3 }}"
                                                            wire:model.live="widgetPropertyValues.{{ $field['key'] }}"
                                                            wire:change="updateWidgetProperty('{{ $field['key'] }}', $event.target.value)"
                                                            class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 font-mono text-gray-700 dark:text-gray-300 resize-y"
                                                            placeholder="{{ $field['placeholder'] ?? '' }}"
                                                        >{{ $widgetPropertyValues[$field['key']] ?? $field['default'] }}</textarea>
                                                        @if (! empty($field['helper']))
                                                            <p class="mt-1 text-[10px] text-gray-400">{{ $field['helper'] }}</p>
                                                        @endif

                                                    @elseif ($field['type'] === 'select')
                                                        <select
                                                            wire:model.live="widgetPropertyValues.{{ $field['key'] }}"
                                                            wire:change="updateWidgetProperty('{{ $field['key'] }}', $event.target.value)"
                                                            class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                                        >
                                                            @foreach ($field['options'] as $optVal => $optLabel)
                                                                <option
                                                                    value="{{ $optVal }}"
                                                                    @selected(($widgetPropertyValues[$field['key']] ?? $field['default']) == $optVal)
                                                                >{{ $optLabel }}</option>
                                                            @endforeach
                                                        </select>

                                                    @elseif ($field['type'] === 'toggle')
                                                        <label class="flex items-center gap-2 cursor-pointer">
                                                            <input
                                                                type="checkbox"
                                                                wire:model.live="widgetPropertyValues.{{ $field['key'] }}"
                                                                wire:change="updateWidgetProperty('{{ $field['key'] }}', $event.target.checked)"
                                                                @checked((bool)($widgetPropertyValues[$field['key']] ?? $field['default']))
                                                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                                            />
                                                            <span class="text-xs text-gray-500 dark:text-gray-400">Enabled</span>
                                                        </label>

                                                    @elseif ($field['type'] === 'number')
                                                        <input
                                                            type="number"
                                                            wire:model.live="widgetPropertyValues.{{ $field['key'] }}"
                                                            wire:change="updateWidgetProperty('{{ $field['key'] }}', $event.target.value)"
                                                            value="{{ $widgetPropertyValues[$field['key']] ?? $field['default'] }}"
                                                            class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                                            placeholder="{{ $field['placeholder'] ?? '' }}"
                                                        />

                                                    @else
                                                        {{-- text (default) --}}
                                                        <input
                                                            type="text"
                                                            wire:model.live="widgetPropertyValues.{{ $field['key'] }}"
                                                            wire:change="updateWidgetProperty('{{ $field['key'] }}', $event.target.value)"
                                                            value="{{ $widgetPropertyValues[$field['key']] ?? $field['default'] }}"
                                                            class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                                            placeholder="{{ $field['placeholder'] ?? '' }}"
                                                        />
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="removeWidget('{{ $selectedWidget['id'] }}')"
                                        class="w-full flex items-center justify-center gap-1.5 rounded-md border border-red-200 py-2 text-xs text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                    >
                                        <x-heroicon-o-trash class="h-3.5 w-3.5" />
                                        Remove widget
                                    </button>
                                </div>
                            </section>
                        @endif
                    @else
                        <div class="text-center py-10 text-gray-400 dark:text-gray-600">
                            <x-heroicon-o-cursor-arrow-rays class="h-10 w-10 mx-auto mb-3 opacity-30" />
                            <p class="text-xs">Select a widget on the canvas to edit its properties.</p>
                        </div>
                    @endif
                @endif

                {{-- ── Menu tab ────────────────────────────────────── --}}
                @if ($activeTab === 'menu')
                    <section>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400">Navigation Items</h4>
                            <button
                                type="button"
                                wire:click="addMenuItem"
                                class="flex items-center gap-1 rounded-md bg-primary-50 dark:bg-primary-900/20 px-2.5 py-1 text-[11px] font-semibold text-primary-600 dark:text-primary-400 hover:bg-primary-100 transition-colors"
                            >
                                <x-heroicon-o-plus class="h-3 w-3" />
                                Add item
                            </button>
                        </div>

                        <div class="space-y-2" id="menu-editor">
                            @foreach ($menuItems as $item)
                                <div
                                    wire:key="menu-{{ $item['id'] }}"
                                    class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-3 space-y-2"
                                >
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-[11px] font-semibold text-gray-500">Item</span>
                                        <button
                                            type="button"
                                            wire:click="removeMenuItem('{{ $item['id'] }}')"
                                            class="text-gray-300 hover:text-red-400 transition-colors"
                                        >
                                            <x-heroicon-o-x-mark class="h-3.5 w-3.5" />
                                        </button>
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-gray-400 block mb-0.5">Label</label>
                                        <input
                                            type="text"
                                            value="{{ $item['label'] }}"
                                            wire:change="updateMenuItem('{{ $item['id'] }}', 'label', $event.target.value)"
                                            class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-2 py-1 text-gray-700 dark:text-gray-300"
                                        />
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-gray-400 block mb-0.5">URL</label>
                                        <input
                                            type="text"
                                            value="{{ $item['url'] }}"
                                            wire:change="updateMenuItem('{{ $item['id'] }}', 'url', $event.target.value)"
                                            class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-2 py-1 font-mono text-gray-700 dark:text-gray-300"
                                        />
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-gray-400 block mb-0.5">Icon (heroicon name)</label>
                                        <input
                                            type="text"
                                            value="{{ $item['icon'] }}"
                                            wire:change="updateMenuItem('{{ $item['id'] }}', 'icon', $event.target.value)"
                                            class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-2 py-1 font-mono text-gray-700 dark:text-gray-300"
                                            placeholder="heroicon-o-home"
                                        />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- ── Components tab ─────────────────────────────── --}}
                @if ($activeTab === 'components')
                    @if ($activeComponentKey === '')
                        <div class="text-center py-10 text-gray-400 dark:text-gray-600">
                            <x-heroicon-o-swatch class="h-10 w-10 mx-auto mb-3 opacity-30" />
                            <p class="text-xs">Click <strong class="text-gray-500">Style</strong> next to any component in the left panel to open it here.</p>
                        </div>
                    @else
                        @php($componentDef = \App\Platform\Ui\ComponentRegistry::get($activeComponentKey))
                        @if ($componentDef)
                            {{-- Component header --}}
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400">
                                    {{ $componentDef['label'] }}
                                </h4>
                                <span class="text-[10px] font-mono text-gray-400 bg-gray-100 dark:bg-white/5 px-1.5 py-0.5 rounded">{{ $activeComponentKey }}</span>
                            </div>
                            <p class="text-[10px] text-gray-400 mb-4 font-mono truncate" title="{{ $componentDef['selector'] }}">{{ $componentDef['selector'] }}</p>

                            {{-- Panel selector --}}
                            <section class="mb-4">
                                <label class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 block mb-1">Panel</label>
                                <select
                                    wire:model.live="componentPanel"
                                    class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                >
                                    <option value="admin">admin</option>
                                    <option value="titanpro">titanpro</option>
                                    <option value="titanstudio">titanstudio</option>
                                    <option value="titansolo">titansolo</option>
                                    <option value="zeropay">zeropay</option>
                                    <option value="titannexus">titannexus</option>
                                </select>
                            </section>

                            {{-- Token editor --}}
                            <section>
                                <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Design Tokens</h4>
                                <div class="space-y-3">
                                    @foreach ($componentDef['tokens'] as $token)
                                        <div>
                                            <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">
                                                {{ $token['label'] }}
                                                @if(($componentTokenValues[$token['key']] ?? $token['default']) !== $token['default'])
                                                    <span class="ml-1 text-[10px] text-primary-500">● override</span>
                                                @endif
                                            </label>
                                            @if ($token['type'] === 'color')
                                                <div class="flex items-center gap-2">
                                                    <input
                                                        type="color"
                                                        wire:model.live="componentTokenValues.{{ $token['key'] }}"
                                                        class="h-7 w-10 cursor-pointer rounded border border-gray-200 dark:border-white/10 p-0.5 flex-shrink-0"
                                                    />
                                                    <input
                                                        type="text"
                                                        wire:model.live="componentTokenValues.{{ $token['key'] }}"
                                                        class="flex-1 text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1 font-mono text-gray-700 dark:text-gray-300"
                                                        placeholder="{{ $token['default'] }}"
                                                    />
                                                </div>
                                            @elseif ($token['type'] === 'select')
                                                <select
                                                    wire:model.live="componentTokenValues.{{ $token['key'] }}"
                                                    class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                                >
                                                    @foreach ($token['options'] as $optVal => $optLabel)
                                                        <option value="{{ $optVal }}">{{ $optLabel }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input
                                                    type="text"
                                                    wire:model.live="componentTokenValues.{{ $token['key'] }}"
                                                    class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 font-mono text-gray-700 dark:text-gray-300"
                                                    placeholder="{{ $token['default'] }}"
                                                />
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </section>

                            {{-- Save / Reset actions --}}
                            <section class="mt-4 space-y-2">
                                <button
                                    type="button"
                                    wire:click="saveComponentOverrides"
                                    class="w-full flex items-center justify-center gap-1.5 rounded-md bg-primary-600 py-2 text-xs font-semibold text-white hover:bg-primary-700 transition-colors"
                                >
                                    <x-heroicon-o-check class="h-3.5 w-3.5" />
                                    Save overrides
                                </button>
                                <button
                                    type="button"
                                    wire:click="resetComponentOverrides"
                                    wire:confirm="Reset all overrides for this component to theme defaults?"
                                    class="w-full flex items-center justify-center gap-1.5 rounded-md border border-gray-200 dark:border-white/10 py-2 text-xs text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                                >
                                    <x-heroicon-o-arrow-path class="h-3.5 w-3.5" />
                                    Reset to theme defaults
                                </button>
                            </section>

                            {{-- Preset management --}}
                            <section class="mt-5 pt-4 border-t border-gray-200 dark:border-white/10">
                                <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Presets</h4>

                                {{-- Save new preset --}}
                                <div class="flex gap-2 mb-3">
                                    <input
                                        type="text"
                                        wire:model.live="newPresetName"
                                        class="flex-1 text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                        placeholder="Preset name…"
                                    />
                                    <button
                                        type="button"
                                        wire:click="saveComponentPreset"
                                        class="flex items-center gap-1 rounded-md bg-primary-50 dark:bg-primary-900/20 px-2.5 py-1 text-[11px] font-semibold text-primary-600 dark:text-primary-400 hover:bg-primary-100 transition-colors flex-shrink-0"
                                    >
                                        <x-heroicon-o-bookmark class="h-3 w-3" />
                                        Save
                                    </button>
                                </div>

                                {{-- Apply existing preset --}}
                                @if (count($availablePresets) > 0)
                                    <div class="flex gap-2">
                                        <select
                                            wire:model.live="selectedPreset"
                                            class="flex-1 text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                        >
                                            <option value="">Select preset…</option>
                                            @foreach ($availablePresets as $preset)
                                                <option value="{{ $preset }}">{{ $preset }}</option>
                                            @endforeach
                                        </select>
                                        <button
                                            type="button"
                                            wire:click="applyComponentPreset"
                                            class="flex items-center gap-1 rounded-md border border-gray-200 dark:border-white/10 px-2.5 py-1 text-[11px] font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors flex-shrink-0"
                                        >
                                            <x-heroicon-o-play class="h-3 w-3" />
                                            Apply
                                        </button>
                                    </div>
                                @else
                                    <p class="text-[10px] text-gray-400">No presets saved yet for this component.</p>
                                @endif
                            </section>
                        @endif
                    @endif
                @endif

                {{-- ── Marketplace tab ─────────────────────────────── --}}
                @if ($activeTab === 'marketplace')

                    {{-- Marketplace sub-tab strip --}}
                    <div class="flex gap-1 border-b border-gray-100 dark:border-white/10 pb-2 mb-4 overflow-x-auto">
                        @foreach (['browse' => 'Browse', 'install' => 'Install', 'share' => 'Share', 'import' => 'Import'] as $sub => $subLabel)
                            <button
                                type="button"
                                wire:click="$set('marketplaceTab', '{{ $sub }}')"
                                class="flex-none rounded-full px-3 py-1 text-[11px] font-semibold transition-colors
                                    {{ $marketplaceTab === $sub
                                        ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400'
                                        : 'text-gray-500 hover:bg-gray-100 dark:hover:bg-white/5' }}"
                            >
                                {{ $subLabel }}
                            </button>
                        @endforeach
                    </div>

                    {{-- ── Browse --}}
                    @if ($marketplaceTab === 'browse')
                        <section>
                            <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Curated Theme Packs</h4>
                            <div class="space-y-2">
                                @foreach (\App\Support\ThemePackManager::builtinThemes() as $key => $theme)
                                    <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-3">
                                        {{-- Color swatch strip --}}
                                        <div class="flex gap-1 mb-2">
                                            @foreach (['primary_color', 'secondary_color', 'accent_color', 'surface_color'] as $colorKey)
                                                <div
                                                    class="h-5 flex-1 rounded"
                                                    style="background: {{ e($theme['tokens'][$colorKey] ?? '#eee') }}"
                                                    title="{{ $colorKey }}"
                                                ></div>
                                            @endforeach
                                        </div>

                                        <div class="flex items-start justify-between gap-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-gray-700 dark:text-gray-200 truncate">{{ $theme['name'] }}</p>
                                                <p class="text-[10px] text-gray-400">{{ $theme['author'] }} · v{{ $theme['version'] }}</p>
                                                {{-- Tags --}}
                                                <div class="flex flex-wrap gap-1 mt-1">
                                                    @foreach ($theme['tags'] as $tag)
                                                        <span class="inline-block rounded-full bg-gray-200 dark:bg-white/10 px-1.5 py-0.5 text-[9px] text-gray-500 dark:text-gray-400">{{ $tag }}</span>
                                                    @endforeach
                                                </div>
                                                {{-- Star rating --}}
                                                <div class="flex items-center gap-0.5 mt-1">
                                                    @php($fullStars = (int) floor($theme['rating']); $hasHalf = ($theme['rating'] - $fullStars) >= 0.5)
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $fullStars)
                                                            <span class="text-amber-400 text-[11px]">★</span>
                                                        @elseif ($i == $fullStars + 1 && $hasHalf)
                                                            <span class="text-amber-300 text-[11px]">★</span>
                                                        @else
                                                            <span class="text-gray-300 text-[11px]">★</span>
                                                        @endif
                                                    @endfor
                                                    <span class="ml-0.5 text-[9px] text-gray-400">{{ number_format($theme['rating'], 1) }}</span>
                                                </div>
                                            </div>
                                            <button
                                                type="button"
                                                wire:click="applyBuiltinTheme('{{ $key }}')"
                                                class="flex-shrink-0 flex items-center gap-1 rounded-md bg-primary-50 dark:bg-primary-900/20 px-2.5 py-1.5 text-[11px] font-semibold text-primary-600 dark:text-primary-400 hover:bg-primary-100 transition-colors"
                                            >
                                                <x-heroicon-o-paint-brush class="h-3 w-3" />
                                                Apply
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- ── Install (upload ZIP) --}}
                    @if ($marketplaceTab === 'install')
                        <section class="space-y-4">
                            <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400">Install from ZIP</h4>
                            <p class="text-[10px] text-gray-400 leading-relaxed">
                                Upload a <code class="bg-gray-100 dark:bg-white/10 rounded px-0.5">.zip</code> containing
                                <code class="bg-gray-100 dark:bg-white/10 rounded px-0.5">theme.json</code>,
                                <code class="bg-gray-100 dark:bg-white/10 rounded px-0.5">meta.json</code>, and optionally
                                <code class="bg-gray-100 dark:bg-white/10 rounded px-0.5">preview.png</code>.
                            </p>

                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Theme pack ZIP</label>
                                <input
                                    type="file"
                                    wire:model="themeZipUpload"
                                    accept=".zip"
                                    class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                />
                                @error('themeZipUpload') <p class="mt-1 text-[11px] text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <button
                                type="button"
                                wire:click="previewZip"
                                wire:loading.attr="disabled"
                                class="w-full flex items-center justify-center gap-1.5 rounded-md border border-gray-200 dark:border-white/10 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                            >
                                <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                Validate &amp; Preview
                            </button>

                            @if (!empty($zipPreview))
                                <div class="rounded-lg border border-primary-200 dark:border-primary-900/40 bg-primary-50 dark:bg-primary-900/10 p-3 space-y-2">
                                    <p class="text-xs font-semibold text-primary-700 dark:text-primary-400">
                                        {{ $zipPreview['meta']['name'] ?? 'Theme' }}
                                        <span class="ml-1 text-[10px] font-normal text-primary-500">v{{ $zipPreview['meta']['version'] ?? '1.0.0' }}</span>
                                    </p>
                                    <p class="text-[10px] text-gray-500">by {{ $zipPreview['meta']['author'] ?? 'Unknown' }}</p>
                                    {{-- Token swatches --}}
                                    <div class="flex gap-1">
                                        @foreach (['primary_color', 'secondary_color', 'accent_color', 'surface_color'] as $ck)
                                            @if (!empty($zipPreview['tokens'][$ck]))
                                                <div class="h-5 flex-1 rounded border border-white/20" style="background: {{ e($zipPreview['tokens'][$ck]) }}" title="{{ $ck }}"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="installFromZip"
                                        class="w-full flex items-center justify-center gap-1.5 rounded-md bg-primary-600 py-2 text-xs font-semibold text-white hover:bg-primary-700 transition-colors"
                                    >
                                        <x-heroicon-o-arrow-down-tray class="h-3.5 w-3.5" />
                                        Install Theme
                                    </button>
                                </div>
                            @endif
                        </section>
                    @endif

                    {{-- ── Share --}}
                    @if ($marketplaceTab === 'share')
                        <section class="space-y-4">
                            <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400">Share Active Theme</h4>
                            <p class="text-[10px] text-gray-400 leading-relaxed">
                                Generate a public share link that lets anyone preview and install your current theme.
                            </p>

                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Theme name (optional)</label>
                                <input
                                    type="text"
                                    wire:model.live="shareThemeName"
                                    class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 text-gray-700 dark:text-gray-300"
                                    placeholder="My Custom Theme"
                                />
                            </div>

                            <button
                                type="button"
                                wire:click="shareTheme"
                                class="w-full flex items-center justify-center gap-1.5 rounded-md bg-primary-600 py-2 text-xs font-semibold text-white hover:bg-primary-700 transition-colors"
                            >
                                <x-heroicon-o-share class="h-3.5 w-3.5" />
                                Generate Share Link
                            </button>

                            @if ($generatedShareUrl !== '')
                                <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-3">
                                    <p class="text-[10px] font-semibold text-gray-500 mb-1">Share URL</p>
                                    <p class="text-[11px] font-mono text-primary-600 dark:text-primary-400 break-all select-all">{{ $generatedShareUrl }}</p>
                                </div>
                            @endif

                            {{-- Export ZIP --}}
                            <div class="pt-3 border-t border-gray-100 dark:border-white/10">
                                <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Export as ZIP</h4>
                                <p class="text-[10px] text-gray-400 mb-3 leading-relaxed">
                                    Download the active theme as an installable <code class="bg-gray-100 dark:bg-white/10 rounded px-0.5">.zip</code> pack you can share with others.
                                </p>
                                <button
                                    type="button"
                                    wire:click="exportTheme"
                                    class="w-full flex items-center justify-center gap-1.5 rounded-md border border-gray-200 dark:border-white/10 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                                >
                                    <x-heroicon-o-arrow-down-tray class="h-3.5 w-3.5" />
                                    Download Theme ZIP
                                </button>
                            </div>
                        </section>
                    @endif

                    {{-- ── Import from URL --}}
                    @if ($marketplaceTab === 'import')
                        <section class="space-y-4">
                            <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400">Import from Share Link</h4>
                            <p class="text-[10px] text-gray-400 leading-relaxed">
                                Paste a share link generated by the Share tab to preview and install a theme.
                            </p>

                            <div>
                                <label class="text-xs text-gray-600 dark:text-gray-400 block mb-1">Share URL</label>
                                <input
                                    type="url"
                                    wire:model.live="importUrl"
                                    class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 font-mono text-gray-700 dark:text-gray-300"
                                    placeholder="https://yoursite.com/theme/import/abc123"
                                />
                            </div>

                            <button
                                type="button"
                                wire:click="previewImport"
                                class="w-full flex items-center justify-center gap-1.5 rounded-md border border-gray-200 dark:border-white/10 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                            >
                                <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                Preview Theme
                            </button>

                            @if (!empty($importPreview))
                                <div class="rounded-lg border border-primary-200 dark:border-primary-900/40 bg-primary-50 dark:bg-primary-900/10 p-3 space-y-2">
                                    <p class="text-xs font-semibold text-primary-700 dark:text-primary-400">
                                        {{ $importPreview['meta']['name'] ?? 'Shared Theme' }}
                                    </p>
                                    <p class="text-[10px] text-gray-500">by {{ $importPreview['meta']['author'] ?? 'Unknown' }}</p>
                                    {{-- Token swatches --}}
                                    <div class="flex gap-1">
                                        @foreach (['primary_color', 'secondary_color', 'accent_color', 'surface_color'] as $ck)
                                            @if (!empty($importPreview['tokens'][$ck]))
                                                <div class="h-5 flex-1 rounded border border-white/20" style="background: {{ e($importPreview['tokens'][$ck]) }}" title="{{ $ck }}"></div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="installFromUrl"
                                        class="w-full flex items-center justify-center gap-1.5 rounded-md bg-primary-600 py-2 text-xs font-semibold text-white hover:bg-primary-700 transition-colors"
                                    >
                                        <x-heroicon-o-arrow-down-tray class="h-3.5 w-3.5" />
                                        Install This Theme
                                    </button>
                                </div>
                            @endif
                        </section>
                    @endif

                @endif

            </div>
        </aside>
    </div>

    {{--
        Alpine.js data component for the drag-and-drop canvas.
        Uses a lightweight inline sort that falls back gracefully if
        SortableJS is not loaded (no hard JS dependency).
    --}}
    <script>
        function studioCanvas(wire) {
            return {
                sortable: null,
                sortableReadyListener: null,

                init() {
                    const el = document.getElementById('studio-canvas');
                    if (!el) return;

                    this.sortableReadyListener = () => this.initialiseSortable(el, wire);
                    this.initialiseSortable(el, wire);

                    if (! this.sortable) {
                        window.addEventListener('titan-ui-studio:sortable-ready', this.sortableReadyListener, { once: true });
                    }
                },

                initialiseSortable(el, wire) {
                    const SortableLibrary = window.Sortable;

                    if (! SortableLibrary || this.sortable) {
                        return;
                    }

                    this.sortable = new SortableLibrary(el, {
                        animation: 150,
                        handle: '.drag-handle',
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        onEnd: () => {
                            const ids = Array.from(el.querySelectorAll('[data-id]'))
                                .map(item => item.dataset.id);
                            wire.reorderWidgets(ids);
                        },
                    });
                },

                destroy() {
                    if (this.sortableReadyListener) {
                        window.removeEventListener('titan-ui-studio:sortable-ready', this.sortableReadyListener);
                        this.sortableReadyListener = null;
                    }

                    if (this.sortable) {
                        this.sortable.destroy();
                        this.sortable = null;
                    }
                },
            };
        }
    </script>
</x-filament-panels::page>
