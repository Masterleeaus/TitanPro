<x-filament-panels::page>
    {{--
        UI Studio — three-panel visual design surface.
        Left  : component tree / layer list
        Centre: drag-and-drop canvas preview with live iframe thumbnails
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
            <div class="flex items-center justify-between gap-2 px-4 py-2.5 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-white/10 text-xs text-gray-500 dark:text-gray-400">
                <span class="font-semibold">Canvas Preview</span>
                <div class="flex items-center gap-2">
                    <select
                        wire:model.live="previewPanel"
                        class="text-[11px] rounded border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-2 py-1 text-gray-600 dark:text-gray-300"
                    >
                        @foreach ($this->previewPanelOptions() as $panelId => $panelLabel)
                            <option value="{{ $panelId }}">{{ $panelLabel }}</option>
                        @endforeach
                    </select>
                    <button
                        type="button"
                        wire:click="refreshPreview"
                        aria-label="Reload previews"
                        class="inline-flex items-center gap-1 rounded border border-gray-200 dark:border-white/10 px-2 py-1 text-[11px] text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors"
                    >
                        <x-heroicon-o-arrow-path class="h-3.5 w-3.5" />
                        Reload previews
                    </button>
                    <span class="hidden lg:inline text-[11px] text-gray-400">Drag rows to reorder · Click to select · Resize columns in properties panel</span>
                </div>
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

                                {{-- Widget preview iframe --}}
                                <div class="mx-4 mb-4 h-28 rounded-lg bg-gray-50 dark:bg-white/5 border border-dashed border-gray-200 dark:border-white/10 overflow-hidden">
                                    <iframe
                                        class="ui-widget-preview-iframe h-full w-full border-0"
                                        data-widget-id="{{ $widget['id'] }}"
                                        src="{{ $this->previewPanelUrl($widget['id']) }}"
                                        title="{{ $widget['label'] }} live preview"
                                        sandbox="allow-same-origin allow-scripts"
                                        referrerpolicy="same-origin"
                                        loading="lazy"
                                    ></iframe>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div
                id="ui-studio-preview-payload"
                data-preview-css='@json($this->previewCssVariables())'
                hidden
            ></div>
        </main>

        {{-- ── RIGHT PANEL: Property editor ─────────────────────────── --}}
        <aside class="studio-panel border-l border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 flex flex-col">

            {{-- Tab strip --}}
            <div class="flex border-b border-gray-200 dark:border-white/10">
                @foreach (['branding' => 'Branding', 'layout' => 'Layout', 'menu' => 'Menu', 'components' => 'Components'] as $tab => $tabLabel)
                    <button
                        type="button"
                        wire:click="selectTab('{{ $tab }}')"
                        class="flex-1 py-2.5 text-xs font-semibold transition-colors
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
                        <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Custom CSS</h4>
                        <textarea
                            wire:model.live="customCss"
                            rows="6"
                            class="w-full text-xs rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1.5 font-mono text-gray-700 dark:text-gray-300 resize-y"
                            placeholder="/* custom overrides */"
                        ></textarea>
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

                init() {
                    const el = document.getElementById('studio-canvas');
                    if (!el) return;

                    // If SortableJS is available (loaded via a CDN or package), initialise it.
                    if (typeof Sortable !== 'undefined') {
                        this.sortable = Sortable.create(el, {
                            animation: 150,
                            handle: '.drag-handle',
                            ghostClass: 'sortable-ghost',
                            chosenClass: 'sortable-chosen',
                            onEnd: (evt) => {
                                const ids = Array.from(el.querySelectorAll('[data-id]'))
                                    .map(el => el.dataset.id);
                                wire.reorderWidgets(ids);
                            },
                        });
                    }
                },

                destroy() {
                    if (this.sortable) {
                        this.sortable.destroy();
                        this.sortable = null;
                    }
                },
            };
        }
    </script>
    <script>
        (() => {
            const payload = document.getElementById('ui-studio-preview-payload');
            if (!payload) {
                return;
            }

            const frameSelector = '.ui-widget-preview-iframe';

            const themePayload = () => {
                try {
                    return JSON.parse(payload.dataset.previewCss ?? '{}');
                } catch (error) {
                    console.warn('UI Studio preview payload parse failed.', error);
                    return {};
                }
            };

            const installBridge = (frame) => {
                try {
                    const doc = frame.contentDocument;
                    if (!doc || !doc.head || !doc.documentElement || doc.getElementById('titan-preview-bridge')) {
                        return;
                    }

                    const bridge = doc.createElement('script');
                    bridge.id = 'titan-preview-bridge';
                    bridge.textContent = `
                        (function () {
                            if (window.titanPreviewBridgeInstalled) return;
                            window.titanPreviewBridgeInstalled = true;
                            window.addEventListener('message', function (event) {
                                if (event.origin !== window.location.origin) return;
                                if (event.source !== window.parent) return;
                                var data = event.data || {};
                                if (data.type !== 'titan-ui-theme-vars' || !data.payload) return;
                                var root = document.documentElement;
                                Object.keys(data.payload).forEach(function (key) {
                                    root.style.setProperty(key, String(data.payload[key]));
                                });
                            });
                        })();
                    `;
                    doc.head.appendChild(bridge);
                } catch (error) {
                    // Ignore inaccessible frame documents.
                }
            };

            const applyThemeToFrame = (frame) => {
                const vars = themePayload();
                installBridge(frame);

                frame.contentWindow?.postMessage(
                    {
                        type: 'titan-ui-theme-vars',
                        payload: vars,
                    },
                    window.location.origin,
                );

                try {
                    const doc = frame.contentDocument;
                    if (!doc?.documentElement) {
                        return;
                    }

                    Object.keys(vars).forEach((key) => {
                        doc.documentElement.style.setProperty(key, String(vars[key]));
                    });
                } catch (error) {
                    console.debug('UI Studio preview iframe style sync skipped.', error);
                }
            };

            const applyThemeToFrames = () => {
                document.querySelectorAll(frameSelector).forEach((frame) => applyThemeToFrame(frame));
            };

            const bindFrameLoadListeners = () => {
                document.querySelectorAll(frameSelector).forEach((frame) => {
                    if (frame.dataset.previewListenerBound === '1') {
                        return;
                    }

                    frame.dataset.previewListenerBound = '1';
                    frame.addEventListener('load', () => applyThemeToFrame(frame));
                });
            };

            const observer = new MutationObserver(() => {
                bindFrameLoadListeners();
                applyThemeToFrames();
            });

            observer.observe(payload, {
                attributes: true,
                attributeFilter: ['data-preview-css'],
            });

            bindFrameLoadListeners();
            applyThemeToFrames();
        })();
    </script>
</x-filament-panels::page>
