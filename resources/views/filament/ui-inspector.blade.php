{{--
    Visual UI Inspector — injected into every Filament panel via a render hook.

    • Floating wrench button (bottom-right) toggles inspector on / off.
    • When active: hover any Filament component → dashed border + label tooltip.
    • Click component → property sidebar opens for live editing.
    • Sidebar fields: spacing, border-radius, shadow, colours, font, gradient,
      glassmorphism, animation preset.
    • Changes are applied instantly via CSS custom-property injection (no reload).
    • Overrides are persisted to localStorage + the /titan/ui-inspector/overrides API.
    • "Reset component" reverts to theme defaults.

    Requires: Alpine.js (provided by Filament), csrf meta tag.
--}}

{{-- Alpine.js inspector controller --}}
<div
    x-data="titanUiInspector"
    id="titan-ui-inspector-root"
    style="position:relative;z-index:9999"
>

    {{-- ── Hover highlight overlay ──────────────────────────────────────── --}}
    <div
        x-show="active && hoveredEl && !sidebarOpen"
        x-bind:style="{
            position: 'absolute',
            pointerEvents: 'none',
            boxSizing: 'border-box',
            border: '2px dashed #6366f1',
            borderRadius: '4px',
            zIndex: 9998,
            transition: 'all 0.1s ease',
            ...overlayStyle
        }"
        style="display:none"
    ></div>

    {{-- Selected highlight overlay (stays visible while sidebar open) --}}
    <div
        x-show="active && selectedEl && sidebarOpen"
        x-bind:style="{
            position: 'absolute',
            pointerEvents: 'none',
            boxSizing: 'border-box',
            border: '2px solid #6366f1',
            borderRadius: '4px',
            boxShadow: '0 0 0 4px rgba(99,102,241,0.12)',
            zIndex: 9998,
            ...overlayStyle
        }"
        style="display:none"
    ></div>

    {{-- ── Tooltip ───────────────────────────────────────────────────────── --}}
    <div
        x-show="active && hoveredEl && !sidebarOpen"
        x-bind:style="{
            position: 'absolute',
            zIndex: 9999,
            pointerEvents: 'none',
            ...tooltipStyle
        }"
        style="display:none"
    >
        <span
            class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold shadow"
            style="background:#6366f1;color:#fff;white-space:nowrap;line-height:1.6"
            x-text="hoveredLabel || 'Component'"
        ></span>
    </div>

    {{-- ── Property Sidebar ─────────────────────────────────────────────── --}}
    <aside
        id="titan-inspector-sidebar"
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-x-8"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-8"
        class="fixed top-0 right-0 h-full overflow-y-auto shadow-2xl"
        style="width:320px;z-index:10000;background:#1e1e2e;color:#cdd6f4;font-family:system-ui,sans-serif;font-size:13px"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b" style="border-color:rgba(255,255,255,0.08);background:#181825">
            <div>
                <p class="font-semibold text-sm" style="color:#cba6f7">Inspector</p>
                <p class="text-[11px] truncate max-w-[220px]" style="color:#7f849c" x-text="selectedLabel"></p>
            </div>
            <button @click="closeSidebar()" class="rounded p-1 hover:bg-white/10 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="px-4 py-4 space-y-5">

            {{-- Padding --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Padding</label>
                <input
                    type="range" min="0" max="6" step="0.25"
                    :value="sliderValue('padding')"
                    @input="setSlider('padding', 'rem', $event)"
                    class="w-full accent-indigo-500"
                >
                <div class="flex justify-between text-[10px] mt-1" style="color:#585b70">
                    <span>0</span>
                    <span x-text="props['padding'] || '0rem'"></span>
                    <span>6rem</span>
                </div>
                {{-- Visual spacing preview --}}
                <div class="mt-2 flex items-center justify-center rounded" style="border:1px dashed rgba(255,255,255,0.1);height:40px;background:rgba(255,255,255,0.03)">
                    <div :style="{ padding: props['padding'] || '0', background:'rgba(99,102,241,0.2)', borderRadius:'4px', minWidth:'24px', minHeight:'16px' }"></div>
                </div>
            </div>

            {{-- Margin --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Margin</label>
                <input
                    type="range" min="0" max="6" step="0.25"
                    :value="sliderValue('margin')"
                    @input="setSlider('margin', 'rem', $event)"
                    class="w-full accent-indigo-500"
                >
                <div class="flex justify-between text-[10px] mt-1" style="color:#585b70">
                    <span>0</span>
                    <span x-text="props['margin'] || '0rem'"></span>
                    <span>6rem</span>
                </div>
            </div>

            {{-- Border Radius --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Border Radius</label>
                <input
                    type="range" min="0" max="3" step="0.125"
                    :value="sliderValue('border-radius')"
                    @input="setSlider('border-radius', 'rem', $event)"
                    class="w-full accent-indigo-500"
                >
                <div class="flex justify-between text-[10px] mt-1" style="color:#585b70">
                    <span>0</span>
                    <span x-text="props['border-radius'] || '0rem'"></span>
                    <span>3rem</span>
                </div>
            </div>

            {{-- Box Shadow --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Box Shadow</label>
                <div class="grid grid-cols-3 gap-1.5">
                    <template x-for="preset in shadowPresets" :key="preset">
                        <button
                            @click="applyShadowPreset(preset)"
                            class="rounded py-1.5 text-[11px] font-medium transition-colors"
                            :style="{
                                background: shadowPreset === preset ? '#6366f1' : 'rgba(255,255,255,0.06)',
                                color: shadowPreset === preset ? '#fff' : '#a6adc8',
                                border: '1px solid ' + (shadowPreset === preset ? '#6366f1' : 'rgba(255,255,255,0.1)')
                            }"
                            x-text="preset"
                        ></button>
                    </template>
                </div>
                <input
                    type="text"
                    x-model="props['box-shadow']"
                    @input="applyProp('box-shadow', $event.target.value)"
                    placeholder="Custom shadow value…"
                    class="mt-2 w-full rounded px-2 py-1.5 text-xs"
                    style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#cdd6f4;outline:none"
                >
            </div>

            {{-- Background Color --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Background</label>
                <div class="flex gap-2 items-center">
                    <input
                        type="color"
                        :value="props['background-color'] || '#ffffff'"
                        @input="applyProp('background-color', $event.target.value)"
                        class="h-8 w-12 rounded cursor-pointer border-0"
                        style="background:transparent;padding:0"
                    >
                    <input
                        type="text"
                        x-model="props['background-color']"
                        @input="applyProp('background-color', $event.target.value)"
                        placeholder="#ffffff or rgba(…)"
                        class="flex-1 rounded px-2 py-1.5 text-xs"
                        style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#cdd6f4;outline:none"
                    >
                </div>
            </div>

            {{-- Text Color --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Text Color</label>
                <div class="flex gap-2 items-center">
                    <input
                        type="color"
                        :value="props['color'] || '#000000'"
                        @input="applyProp('color', $event.target.value)"
                        class="h-8 w-12 rounded cursor-pointer border-0"
                        style="background:transparent;padding:0"
                    >
                    <input
                        type="text"
                        x-model="props['color']"
                        @input="applyProp('color', $event.target.value)"
                        placeholder="#000000 or rgb(…)"
                        class="flex-1 rounded px-2 py-1.5 text-xs"
                        style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#cdd6f4;outline:none"
                    >
                </div>
            </div>

            {{-- Font Size --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Font Size</label>
                <input
                    type="range" min="0.5" max="3" step="0.125"
                    :value="sliderValue('font-size')"
                    @input="setSlider('font-size', 'rem', $event)"
                    class="w-full accent-indigo-500"
                >
                <div class="flex justify-between text-[10px] mt-1" style="color:#585b70">
                    <span>0.5rem</span>
                    <span x-text="props['font-size'] || 'default'"></span>
                    <span>3rem</span>
                </div>
            </div>

            {{-- Font Weight --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Font Weight</label>
                <div class="grid grid-cols-4 gap-1">
                    <template x-for="w in ['300','400','500','600','700','800','900']" :key="w">
                        <button
                            @click="applyProp('font-weight', w)"
                            class="rounded py-1 text-[11px] font-medium transition-colors"
                            :style="{
                                background: props['font-weight'] === w ? '#6366f1' : 'rgba(255,255,255,0.06)',
                                color: props['font-weight'] === w ? '#fff' : '#a6adc8',
                                border: '1px solid ' + (props['font-weight'] === w ? '#6366f1' : 'rgba(255,255,255,0.1)')
                            }"
                            :class="{ 'font-black': w === '900', 'font-bold': w === '700', 'font-semibold': w === '600' }"
                            x-text="w"
                        ></button>
                    </template>
                </div>
            </div>

            {{-- Gradient Builder --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Gradient</label>
                <div class="flex gap-2 mb-2">
                    <button
                        @click="gradientType = 'linear'; applyGradient()"
                        class="flex-1 py-1 rounded text-[11px] font-medium transition-colors"
                        :style="{ background: gradientType === 'linear' ? '#6366f1' : 'rgba(255,255,255,0.06)', color: gradientType === 'linear' ? '#fff' : '#a6adc8', border: '1px solid ' + (gradientType === 'linear' ? '#6366f1' : 'rgba(255,255,255,0.1)') }"
                    >Linear</button>
                    <button
                        @click="gradientType = 'radial'; applyGradient()"
                        class="flex-1 py-1 rounded text-[11px] font-medium transition-colors"
                        :style="{ background: gradientType === 'radial' ? '#6366f1' : 'rgba(255,255,255,0.06)', color: gradientType === 'radial' ? '#fff' : '#a6adc8', border: '1px solid ' + (gradientType === 'radial' ? '#6366f1' : 'rgba(255,255,255,0.1)') }"
                    >Radial</button>
                </div>
                <div class="flex gap-2 items-center mb-2">
                    <div>
                        <p class="text-[10px] mb-1" style="color:#585b70">From</p>
                        <input type="color" x-model="gradientFrom" @input="applyGradient()" class="h-8 w-12 rounded cursor-pointer border-0" style="background:transparent;padding:0">
                    </div>
                    <div>
                        <p class="text-[10px] mb-1" style="color:#585b70">To</p>
                        <input type="color" x-model="gradientTo" @input="applyGradient()" class="h-8 w-12 rounded cursor-pointer border-0" style="background:transparent;padding:0">
                    </div>
                    <div x-show="gradientType === 'linear'" class="flex-1">
                        <p class="text-[10px] mb-1" style="color:#585b70">Angle <span x-text="gradientAngle + '°'"></span></p>
                        <input type="range" min="0" max="360" step="5" x-model="gradientAngle" @input="applyGradient()" class="w-full accent-indigo-500">
                    </div>
                </div>
                {{-- Gradient preview --}}
                <div class="h-6 w-full rounded" :style="{ background: gradientPreview() }"></div>
                <button
                    @click="applyProp('--gradient', ''); gradientFrom = '#6366f1'; gradientTo = '#8b5cf6'"
                    class="mt-1.5 w-full py-1 text-[11px] rounded transition-colors"
                    style="background:rgba(255,255,255,0.06);color:#a6adc8;border:1px solid rgba(255,255,255,0.1)"
                >Clear gradient</button>
            </div>

            {{-- Glassmorphism --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Glassmorphism</label>
                <div class="flex items-center gap-3">
                    <button
                        @click="props['--glass'] = props['--glass'] ? '' : String(glassBlur); applyGlass()"
                        class="relative inline-flex h-5 w-9 rounded-full transition-colors"
                        :style="{ background: props['--glass'] ? '#6366f1' : 'rgba(255,255,255,0.12)' }"
                        role="switch"
                    >
                        <span
                            class="absolute top-0.5 h-4 w-4 rounded-full transition-transform"
                            :style="{ background: '#fff', transform: props['--glass'] ? 'translateX(1.25rem)' : 'translateX(0.125rem)' }"
                        ></span>
                    </button>
                    <span class="text-[11px]" style="color:#a6adc8">Blur</span>
                    <input
                        type="range" min="0" max="40" step="2"
                        x-model="glassBlur"
                        @input="if(props['--glass']) applyGlass()"
                        class="flex-1 accent-indigo-500"
                    >
                    <span class="text-[11px] w-10 text-right" style="color:#585b70" x-text="glassBlur + 'px'"></span>
                </div>
            </div>

            {{-- Animation Preset --}}
            <div>
                <label class="block text-[11px] font-semibold uppercase tracking-widest mb-2" style="color:#7f849c">Animation</label>
                <div class="grid grid-cols-3 gap-1.5">
                    <template x-for="anim in animationPresets" :key="anim">
                        <button
                            @click="applyProp('--animation', anim)"
                            class="rounded py-1.5 text-[11px] font-medium transition-colors"
                            :style="{
                                background: props['--animation'] === anim ? '#6366f1' : 'rgba(255,255,255,0.06)',
                                color: props['--animation'] === anim ? '#fff' : '#a6adc8',
                                border: '1px solid ' + (props['--animation'] === anim ? '#6366f1' : 'rgba(255,255,255,0.1)')
                            }"
                            x-text="anim"
                        ></button>
                    </template>
                </div>
            </div>

        </div>

        {{-- Footer actions --}}
        <div class="sticky bottom-0 border-t p-4 flex gap-2" style="border-color:rgba(255,255,255,0.08);background:#181825">
            <button
                @click="resetComponent()"
                class="flex-1 rounded-lg py-2 text-xs font-semibold transition-colors"
                style="background:rgba(243,139,168,0.12);color:#f38ba8;border:1px solid rgba(243,139,168,0.2)"
            >
                Reset
            </button>
            <button
                @click="save()"
                class="flex-1 rounded-lg py-2 text-xs font-semibold transition-colors"
                style="background:#6366f1;color:#fff;border:1px solid #6366f1"
            >
                Save
            </button>
        </div>
    </aside>

    {{-- ── Floating toggle button ───────────────────────────────────────── --}}
    <button
        @click="toggle()"
        title="Toggle UI Inspector"
        class="fixed flex items-center justify-center rounded-full shadow-xl transition-all"
        :class="{ 'ring-2 ring-indigo-400 ring-offset-2': active }"
        style="bottom:1.5rem;right:1.5rem;z-index:10001;width:44px;height:44px;background:#6366f1;color:#fff;border:0;cursor:pointer"
        :style="{ background: active ? '#4f46e5' : '#6366f1' }"
    >
        {{-- Wrench / paint-brush icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
        </svg>
    </button>

</div>

{{-- Inspector CSS + script --}}
<style>
body.titan-inspector-active * {
    cursor: crosshair !important;
}
body.titan-inspector-active #titan-ui-inspector-root * {
    cursor: auto !important;
}
body.titan-inspector-active #titan-inspector-sidebar * {
    cursor: auto !important;
}
</style>
<script src="{{ asset('js/titan/ui-inspector.js') }}" defer></script>
