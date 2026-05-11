<x-filament-panels::page>
    @vite('resources/js/filament/ui-studio.js')

    {{--
        UI Studio — split-screen visual design surface.
        Left  : controls (component tree + property editor)
        Right : live sandboxed panel preview
    --}}

    <style>
        .ui-studio-shell {
            display: grid;
            grid-template-columns: minmax(220px, 16fr) minmax(260px, 24fr) minmax(420px, 60fr);
            grid-template-areas: "catalogue editor preview";
            grid-template-rows: 1fr;
            height: calc(100vh - 10rem);
            min-height: 520px;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid rgba(0,0,0,0.08);
        }
        @media (max-width: 1024px) {
            .ui-studio-shell { grid-template-columns: 1fr; grid-template-areas: "catalogue" "editor" "preview"; grid-template-rows: auto auto auto; height: auto; }
        }
        .studio-panel {
            overflow-y: auto;
            overflow-x: hidden;
        }
        .ui-studio-catalogue-panel { grid-area: catalogue; }
        .ui-studio-editor-panel { grid-area: editor; }
        .ui-studio-preview-panel { grid-area: preview; }
        .ui-preview-frame {
            width: 100%;
            height: 100%;
            border: 0;
            background: white;
        }
        .ui-preview-shell {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            height: 100%;
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.5);
            transition: max-width 0.2s ease;
        }
        .ui-preview-shell[data-frame="desktop"] { max-width: 100%; }
        .ui-preview-shell[data-frame="tablet"] { max-width: 820px; }
        .ui-preview-shell[data-frame="mobile"] { max-width: 430px; }
    </style>

    <div class="ui-studio-shell bg-white dark:bg-gray-900 shadow-sm">

        {{-- ── LEFT PANEL: Component tree ───────────────────────────── --}}
        <aside class="studio-panel ui-studio-catalogue-panel border-r border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-gray-950 flex flex-col">
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

        {{-- ── RIGHT PANEL: Live preview sandbox ─────────────────────── --}}
        <main class="studio-panel ui-studio-preview-panel bg-gray-100 dark:bg-gray-800 flex flex-col">
            <div class="px-4 py-2.5 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-white/10 text-xs text-gray-500 dark:text-gray-400 space-y-2">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="font-semibold">Live Preview Sandbox</span>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="text-[11px] text-gray-500 dark:text-gray-400">Panel</label>
                        <select wire:model.live="previewPanel" class="text-[11px] rounded border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-2 py-1 text-gray-700 dark:text-gray-300">
                            @foreach ($this->previewPanelOptions() as $panelId => $panelMeta)
                                <option value="{{ $panelId }}">{{ $panelMeta['label'] ?? $panelId }}</option>
                            @endforeach
                        </select>
                        <div class="inline-flex rounded-md border border-gray-200 dark:border-white/10 overflow-hidden">
                            @foreach (['desktop' => 'Desktop', 'tablet' => 'Tablet', 'mobile' => 'Mobile'] as $frameSize => $label)
                                <button
                                    type="button"
                                    wire:click="setPreviewFrameSize('{{ $frameSize }}')"
                                    class="px-2.5 py-1 text-[11px] {{ $previewFrameSize === $frameSize ? 'bg-primary-500 text-white' : 'bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                        <label class="inline-flex items-center gap-1 text-[11px] text-gray-500 dark:text-gray-400">
                            <input type="checkbox" wire:model.live="syncPreviewScroll" class="rounded border-gray-300 dark:border-white/10" />
                            Sync scroll
                        </label>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-2">
                    <p id="ui-preview-current-url" class="truncate text-[10px] text-gray-400">{{ $this->previewPanelUrl() }}</p>
                    <span class="text-[10px] font-semibold {{ $this->hasUnsavedThemeChanges() ? 'text-amber-500' : 'text-emerald-500' }}">
                        {{ $this->hasUnsavedThemeChanges() ? 'Unsaved theme changes' : 'Theme is saved' }}
                    </span>
                </div>
            </div>

            <div class="flex-1 p-4 overflow-y-auto">
                <div class="ui-preview-shell shadow-sm bg-white dark:bg-gray-900" data-frame="{{ $previewFrameSize }}">
                    <iframe
                        id="ui-studio-preview-iframe"
                        class="ui-preview-frame"
                        src="{{ $this->previewPanelUrl() }}"
                        sandbox="allow-forms allow-same-origin allow-scripts"
                        referrerpolicy="same-origin"
                    ></iframe>
                </div>
            </div>

            <div
                id="ui-studio-preview-payload"
                data-preview-url="{{ $this->previewPanelUrl() }}"
                data-preview-frame="{{ $previewFrameSize }}"
                data-sync-scroll="{{ $syncPreviewScroll ? '1' : '0' }}"
                data-preview-css='@json($this->previewCssVariables())'
                hidden
            ></div>
        </main>

        {{-- ── RIGHT PANEL: Property editor ─────────────────────────── --}}
        <aside class="studio-panel ui-studio-editor-panel border-l border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 flex flex-col">

            {{-- Tab strip --}}
            <div class="flex border-b border-gray-200 dark:border-white/10 overflow-x-auto">
                @foreach (['branding' => 'Branding', 'layout' => 'Layout', 'menu' => 'Menu', 'roles' => 'Roles', 'components' => 'Components', 'marketplace' => 'Marketplace'] as $tab => $tabLabel)
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

                {{-- ── Role Profiles tab ───────────────────────────── --}}
                @if ($activeTab === 'roles')
                    @php
                        $supportedRoles = \App\Models\RoleUIProfile::SUPPORTED_ROLES;
                        $allNavItems = [
                            'Dashboard', 'Jobs', 'Customers', 'Invoices', 'Site Settings',
                        ];
                        $allWidgets = [
                            'kpi-grid-card'        => 'KPI Grid',
                            'map-card'             => 'Live Map',
                            'stat-card'            => 'Stat Card',
                            'chart-bar-card'       => 'Bar Chart',
                            'chart-line-card'      => 'Line Chart',
                            'recent-activity-card' => 'Recent Activity',
                            'table-card'           => 'Data Table',
                            'alert-notice-card'    => 'Alert / Notice',
                        ];
                    @endphp

                    <section>
                        <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-3">Select a Role</h4>
                        <div class="grid grid-cols-1 gap-1.5 mb-4">
                            @foreach ($supportedRoles as $roleSlug => $roleLabel)
                                <button
                                    type="button"
                                    wire:click="selectRole('{{ $roleSlug }}')"
                                    class="flex items-center justify-between rounded-lg border px-3 py-2 text-xs transition-colors
                                        {{ $selectedRole === $roleSlug
                                            ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/10 text-primary-700 dark:text-primary-300'
                                            : 'border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5' }}"
                                >
                                    <span class="font-medium">{{ $roleLabel }}</span>
                                    @if (isset($roleProfiles[$roleSlug]) && array_filter([
                                        $roleProfiles[$roleSlug]['primary_color'] ?? '',
                                        $roleProfiles[$roleSlug]['secondary_color'] ?? '',
                                        $roleProfiles[$roleSlug]['accent_color'] ?? '',
                                        $roleProfiles[$roleSlug]['surface_color'] ?? '',
                                    ]))
                                        <span class="text-[10px] text-green-500">customised</span>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        @if ($selectedRole && isset($supportedRoles[$selectedRole]))
                            @php $profile = $roleProfiles[$selectedRole] ?? []; @endphp
                            <div class="rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-3 space-y-4">
                                <h5 class="text-[11px] font-bold uppercase tracking-widest text-gray-500">
                                    {{ $supportedRoles[$selectedRole] }} — Theme Overrides
                                </h5>
                                <p class="text-[10px] text-gray-400 -mt-2">Leave blank to inherit the platform default.</p>

                                {{-- Color overrides --}}
                                @php
                                    $colorDefaults = [
                                        'primary_color'   => '#2563eb',
                                        'secondary_color' => '#0f172a',
                                        'accent_color'    => '#14b8a6',
                                        'surface_color'   => '#f8fafc',
                                    ];
                                @endphp
                                @foreach ([
                                    'primary_color'   => 'Primary',
                                    'secondary_color' => 'Secondary',
                                    'accent_color'    => 'Accent',
                                    'surface_color'   => 'Surface',
                                ] as $colorField => $colorLabel)
                                    @php $colorVal = $profile[$colorField] ?? ''; @endphp
                                    <div class="flex items-center justify-between gap-2">
                                        <label class="text-xs text-gray-600 dark:text-gray-400 flex-1">{{ $colorLabel }}</label>
                                        <input
                                            type="color"
                                            value="{{ $colorVal ?: $colorDefaults[$colorField] }}"
                                            wire:change="updateRoleProfile('{{ $selectedRole }}', '{{ $colorField }}', $event.target.value)"
                                            class="h-7 w-10 cursor-pointer rounded border border-gray-200 dark:border-white/10 p-0.5 {{ $colorVal ? '' : 'opacity-50' }}"
                                            title="{{ $colorLabel }} override for {{ $selectedRole }}"
                                        />
                                        <input
                                            type="text"
                                            value="{{ $colorVal }}"
                                            wire:change="updateRoleProfile('{{ $selectedRole }}', '{{ $colorField }}', $event.target.value)"
                                            maxlength="7"
                                            placeholder="#inherit"
                                            class="w-20 rounded border border-gray-200 dark:border-white/10 bg-white dark:bg-gray-900 px-2 py-1 text-[11px] font-mono text-gray-700 dark:text-gray-300"
                                        />
                                    </div>
                                @endforeach

                                {{-- Hidden nav items --}}
                                <div>
                                    <h6 class="text-[11px] font-semibold text-gray-500 mb-2">Hidden Navigation Items</h6>
                                    <div class="space-y-1">
                                        @foreach ($allNavItems as $navItem)
                                            @php $isHidden = in_array($navItem, $profile['hidden_nav_items'] ?? [], true); @endphp
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    @checked($isHidden)
                                                    wire:click="toggleNavItem('{{ $selectedRole }}', '{{ $navItem }}')"
                                                    class="rounded border-gray-300 dark:border-white/20 text-primary-600"
                                                />
                                                <span class="text-xs text-gray-600 dark:text-gray-400 {{ $isHidden ? 'line-through opacity-50' : '' }}">
                                                    {{ $navItem }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Widget layout --}}
                                <div>
                                    <h6 class="text-[11px] font-semibold text-gray-500 mb-2">Enabled Dashboard Widgets</h6>
                                    <div class="space-y-1">
                                        @foreach ($allWidgets as $widgetType => $widgetLabel)
                                            @php $isEnabled = in_array($widgetType, $profile['widget_layout'] ?? [], true); @endphp
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    @checked($isEnabled)
                                                    wire:click="updateRoleWidgetLayout('{{ $selectedRole }}', '{{ $widgetType }}', {{ $isEnabled ? 'false' : 'true' }})"
                                                    class="rounded border-gray-300 dark:border-white/20 text-primary-600"
                                                />
                                                <span class="text-xs text-gray-600 dark:text-gray-400">{{ $widgetLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400 dark:text-gray-600">
                                <x-heroicon-o-user-group class="h-8 w-8 mx-auto mb-2 opacity-30" />
                                <p class="text-xs">Select a role above to configure its UI profile.</p>
                            </div>
                        @endif
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
                                    @foreach ($this->previewPanelOptions() as $panelId => $panelMeta)
                                        <option value="{{ $panelId }}">{{ $panelMeta['label'] ?? $panelId }}</option>
                                    @endforeach
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

    {{-- ── AI Theme Generator Modal ──────────────────────────────────────── --}}
    @if ($showAiModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
            wire:key="ai-theme-modal"
        >
            <div class="w-full max-w-lg rounded-2xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-white/10 mx-4">

                {{-- Modal header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-white/10">
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-sparkles class="h-4 w-4 text-amber-500" />
                        AI Theme Generator
                    </h2>
                    <button
                        type="button"
                        wire:click="closeAiModal"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                    >
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>

                <div class="px-6 py-5">

                    {{-- ── Step 1: Prompt input ────────────────────────── --}}
                    @if ($aiModalStep === 'prompt')
                        <div class="space-y-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                Describe the admin panel style you want. The AI will generate a complete colour palette, typography, and design tokens.
                            </p>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-300 block mb-1.5">
                                    Describe your design
                                </label>
                                <textarea
                                    wire:model="aiPrompt"
                                    rows="3"
                                    class="w-full text-sm rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2 text-gray-700 dark:text-gray-300 resize-none focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none"
                                    placeholder="e.g. Create a luxury dark fintech dashboard with gold accents"
                                ></textarea>
                                <p class="mt-1 text-[11px] text-gray-400">Examples: "clean SaaS admin", "dark cyberpunk ops dashboard", "warm earthy CRM"</p>
                            </div>

                            @if ($aiErrorMessage !== '')
                                <div class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-3">
                                    <p class="text-xs text-red-600 dark:text-red-400">{{ $aiErrorMessage }}</p>
                                </div>
                            @endif

                            <button
                                type="button"
                                wire:click="generateAiTheme"
                                class="w-full flex items-center justify-center gap-2 rounded-lg bg-amber-500 hover:bg-amber-600 py-2.5 text-sm font-semibold text-white transition-colors"
                            >
                                <x-heroicon-o-sparkles class="h-4 w-4" />
                                Generate Design System
                            </button>
                        </div>
                    @endif

                    {{-- ── Step 2: Generating (loading spinner) ─────────── --}}
                    @if ($aiModalStep === 'generating')
                        <div class="flex flex-col items-center justify-center py-12 space-y-4">
                            <div class="relative h-12 w-12">
                                <div class="absolute inset-0 rounded-full border-4 border-amber-100 dark:border-amber-900/30"></div>
                                <div class="absolute inset-0 rounded-full border-4 border-t-amber-500 animate-spin"></div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Generating your design system…</p>
                                <p class="text-xs text-gray-400 mt-1">This usually takes a few seconds.</p>
                            </div>
                        </div>
                    @endif

                    {{-- ── Step 3: Preview ──────────────────────────────── --}}
                    @if ($aiModalStep === 'preview' && count($aiGeneratedTheme) > 0)
                        <div class="space-y-4">
                            {{-- Description --}}
                            @if (!empty($aiGeneratedTheme['description']))
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic leading-relaxed">
                                    "{{ $aiGeneratedTheme['description'] }}"
                                </p>
                            @endif

                            {{-- Colour palette --}}
                            <div>
                                <h3 class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-2">Colour Palette</h3>
                                <div class="grid grid-cols-5 gap-2">
                                    @foreach ([
                                        'primary_color'   => 'Primary',
                                        'secondary_color' => 'Secondary',
                                        'accent_color'    => 'Accent',
                                        'surface_color'   => 'Surface',
                                        'sidebar_color'   => 'Sidebar',
                                    ] as $colorKey => $colorLabel)
                                        @php($colorVal = $aiGeneratedTheme[$colorKey] ?? '#cccccc')
                                        <div class="text-center">
                                            <div
                                                class="h-9 rounded-md border border-gray-200 dark:border-white/10 mb-1"
                                                style="background: {{ e($this->safeColor($colorVal, '#cccccc')) }}"
                                            ></div>
                                            <p class="text-[9px] font-medium text-gray-500">{{ $colorLabel }}</p>
                                            <p class="text-[9px] font-mono text-gray-400">{{ $colorVal }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Typography + tokens --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-lg border border-gray-100 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Typography</p>
                                    <p class="text-xs text-gray-700 dark:text-gray-300 font-medium">{{ $aiGeneratedTheme['font_heading'] ?? '—' }}</p>
                                    <p class="text-[10px] text-gray-400">Heading · weight {{ $aiGeneratedTheme['heading_weight'] ?? '—' }}</p>
                                    <p class="text-xs text-gray-700 dark:text-gray-300 font-medium mt-1">{{ $aiGeneratedTheme['font_body'] ?? '—' }}</p>
                                    <p class="text-[10px] text-gray-400">Body</p>
                                </div>
                                <div class="rounded-lg border border-gray-100 dark:border-white/10 bg-gray-50 dark:bg-white/5 p-3">
                                    <p class="text-[11px] font-semibold uppercase tracking-widest text-gray-400 mb-1.5">Tokens</p>
                                    <p class="text-[10px] text-gray-500 mb-0.5">Radius</p>
                                    <p class="text-xs font-mono text-gray-700 dark:text-gray-300">{{ $aiGeneratedTheme['border_radius'] ?? '—' }}</p>
                                    <p class="text-[10px] text-gray-500 mt-1.5 mb-0.5">Button hover</p>
                                    <p class="text-xs font-mono text-gray-700 dark:text-gray-300">{{ $aiGeneratedTheme['button_hover'] ?? '—' }}</p>
                                </div>
                            </div>

                            {{-- Action buttons --}}
                            <div class="flex gap-2 pt-1">
                                <button
                                    type="button"
                                    wire:click="acceptAiTheme"
                                    class="flex-1 flex items-center justify-center gap-1.5 rounded-lg bg-primary-600 hover:bg-primary-700 py-2.5 text-xs font-semibold text-white transition-colors"
                                >
                                    <x-heroicon-o-check class="h-3.5 w-3.5" />
                                    Accept & Apply
                                </button>
                                <button
                                    type="button"
                                    wire:click="regenerateAiTheme"
                                    class="flex items-center justify-center gap-1.5 rounded-lg border border-gray-200 dark:border-white/10 px-3 py-2.5 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                                    title="Edit prompt and regenerate"
                                >
                                    <x-heroicon-o-arrow-path class="h-3.5 w-3.5" />
                                    Regenerate
                                </button>
                                <button
                                    type="button"
                                    wire:click="discardAiTheme"
                                    class="flex items-center justify-center gap-1.5 rounded-lg border border-red-200 dark:border-red-800 px-3 py-2.5 text-xs font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                    title="Discard and close"
                                >
                                    <x-heroicon-o-trash class="h-3.5 w-3.5" />
                                    Discard
                                </button>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif

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

    <script>
        (() => {
            const iframe = document.getElementById('ui-studio-preview-iframe');
            const payload = document.getElementById('ui-studio-preview-payload');
            const urlLabel = document.getElementById('ui-preview-current-url');
            if (!iframe || !payload) {
                return;
            }

            let syncScrollEnabled = payload.dataset.syncScroll === '1';
            let currentIframeUrl = '';
            let syncing = false;
            const isSameOriginFrame = () => {
                try {
                    return iframe.contentWindow?.location?.origin === window.location.origin;
                } catch (error) {
                    return false;
                }
            };

            const injectBridge = () => {
                if (!isSameOriginFrame()) {
                    return;
                }

                try {
                    const doc = iframe.contentDocument;
                    if (!doc || doc.documentElement.dataset.titanPreviewBridgeInjected === '1') {
                        return;
                    }

                    doc.documentElement.dataset.titanPreviewBridgeInjected = '1';
                    const script = doc.createElement('script');
                    script.id = 'titan-preview-bridge';
                    script.textContent = `
                        (function () {
                            if (window.__titanPreviewBridgeInstalled) return;
                            window.__titanPreviewBridgeInstalled = true;
                            window.addEventListener('message', function (event) {
                                if (event.origin !== window.location.origin) return;
                                var data = event.data || {};
                                if (data.type !== 'titan-ui-theme-vars' || !data.payload) return;
                                var root = document.documentElement;
                                Object.keys(data.payload).forEach(function (key) {
                                    root.style.setProperty(key, String(data.payload[key]));
                                });
                            });
                        })();
                    `;
                    doc.head.appendChild(script);
                } catch (error) {
                    // Ignore cross-origin or transient iframe access issues.
                }
            };

            const postTheme = () => {
                try {
                    const vars = JSON.parse(payload.dataset.previewCss ?? '{}');
                    iframe.contentWindow?.postMessage(
                        {
                            type: 'titan-ui-theme-vars',
                            payload: vars,
                        },
                        window.location.origin
                    );
                } catch (error) {
                    // Ignore malformed payload data.
                }
            };

            const syncPreviewScroll = (sourceEl) => {
                if (!syncScrollEnabled || syncing) {
                    return;
                }

                if (!isSameOriginFrame()) {
                    return;
                }

                try {
                    const iframeWindow = iframe.contentWindow;
                    const iframeDoc = iframe.contentDocument;
                    if (!iframeWindow || !iframeDoc) {
                        return;
                    }

                    const sourceMax = sourceEl.scrollHeight - sourceEl.clientHeight;
                    const targetMax = iframeDoc.documentElement.scrollHeight - iframeWindow.innerHeight;
                    const ratio = sourceMax > 0 ? sourceEl.scrollTop / sourceMax : 0;

                    syncing = true;
                    iframeWindow.scrollTo({ top: ratio * Math.max(targetMax, 0), behavior: 'auto' });
                    window.setTimeout(() => {
                        syncing = false;
                    }, 32);
                } catch (error) {
                    syncing = false;
                }
            };

            const refreshUrlLabel = () => {
                if (!isSameOriginFrame()) {
                    return;
                }

                try {
                    const iframeUrl = iframe.contentWindow?.location?.href;
                    if (!iframeUrl || iframeUrl === currentIframeUrl) {
                        return;
                    }

                    currentIframeUrl = iframeUrl;
                    if (urlLabel) {
                        urlLabel.textContent = iframeUrl;
                    }
                } catch (error) {
                    // Ignore inaccessible iframe URLs.
                }
            };

            const applyState = () => {
                const nextUrl = payload.dataset.previewUrl ?? '';
                const nextFrame = payload.dataset.previewFrame ?? 'desktop';
                syncScrollEnabled = payload.dataset.syncScroll === '1';

                const shell = iframe.closest('.ui-preview-shell');
                if (shell) {
                    shell.dataset.frame = nextFrame;
                }

                if (nextUrl && iframe.src !== nextUrl) {
                    iframe.src = nextUrl;
                    currentIframeUrl = '';
                }

                injectBridge();
                postTheme();
                refreshUrlLabel();
            };

            const controls = document.querySelectorAll('.ui-studio-catalogue-panel, .ui-studio-editor-panel');
            controls.forEach((control) => {
                control.addEventListener('scroll', () => syncPreviewScroll(control));
            });

            iframe.addEventListener('load', () => {
                injectBridge();
                postTheme();
                refreshUrlLabel();
            });

            const observer = new MutationObserver(applyState);
            observer.observe(payload, {
                attributes: true,
                attributeFilter: ['data-preview-url', 'data-preview-frame', 'data-sync-scroll', 'data-preview-css'],
            });

            applyState();
            window.setInterval(refreshUrlLabel, 400);
        })();
    </script>
</x-filament-panels::page>
