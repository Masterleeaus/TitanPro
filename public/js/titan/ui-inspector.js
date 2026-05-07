/**
 * Titan Visual UI Inspector
 *
 * Alpine.js component that provides hover-select, click-to-edit, and a live
 * property sidebar for any Filament component rendered on screen.
 *
 * Usage: registered automatically via the `ui-inspector` blade view which is
 * injected into every Filament panel via a render hook. Toggle on/off with the
 * floating wrench button in the bottom-right corner.
 *
 * Persistence: CSS property overrides are persisted via
 *   POST /titan/ui-inspector/overrides  (upsert)
 *   DELETE /titan/ui-inspector/overrides/{key}  (reset)
 *
 * They are also written to localStorage for instant reload-free application.
 */
(() => {
    'use strict';

    /* ── Constants ─────────────────────────────────────────────────────── */

    const STORAGE_KEY = 'titan_ui_overrides';
    const API_BASE    = '/titan/ui-inspector/overrides';

    /**
     * Filament component selectors and human-readable labels.
     * The inspector cycles through these (most specific first) when the user
     * hovers over an element so it highlights the nearest logical component.
     */
    const COMPONENT_SELECTORS = [
        { selector: '[data-ui-key]',              label: (el) => el.dataset.uiKey },
        { selector: '.fi-wi-stats-overview-stat', label: () => 'Stats Card' },
        { selector: '.fi-wi',                     label: (el) => el.querySelector('[class*="fi-wi-"]')?.className.match(/fi-wi-([\w-]+)/)?.[1] ?? 'Widget' },
        { selector: '.fi-ta',                     label: () => 'Table' },
        { selector: '.fi-fo',                     label: () => 'Form' },
        { selector: '.fi-card',                   label: () => 'Card' },
        { selector: '.fi-section',                label: () => 'Section' },
        { selector: '.fi-sidebar',                label: () => 'Sidebar' },
        { selector: '.fi-topbar',                 label: () => 'Topbar' },
        { selector: '[class*="filament-"]',       label: (el) => el.className.match(/filament-([\w-]+)/)?.[1] ?? 'Filament Component' },
    ];

    /** CSS properties exposed in the sidebar, in display order. */
    const PROPERTY_DEFS = [
        { key: 'padding',             label: 'Padding',         type: 'spacing',   unit: 'rem', min: 0, max: 6,  step: 0.25 },
        { key: 'margin',              label: 'Margin',          type: 'spacing',   unit: 'rem', min: 0, max: 6,  step: 0.25 },
        { key: 'border-radius',       label: 'Border Radius',   type: 'slider',    unit: 'rem', min: 0, max: 3,  step: 0.125 },
        { key: 'box-shadow',          label: 'Box Shadow',      type: 'shadow' },
        { key: 'background-color',    label: 'Background',      type: 'color' },
        { key: 'color',               label: 'Text Color',      type: 'color' },
        { key: 'font-size',           label: 'Font Size',       type: 'slider',    unit: 'rem', min: 0.5, max: 3, step: 0.125 },
        { key: 'font-weight',         label: 'Font Weight',     type: 'select',    options: ['300','400','500','600','700','800','900'] },
        { key: '--gradient',          label: 'Gradient',        type: 'gradient' },
        { key: '--glass',             label: 'Glassmorphism',   type: 'glass' },
        { key: '--animation',         label: 'Animation',       type: 'animation' },
    ];

    const SHADOW_PRESETS = {
        'none':   'none',
        'sm':     '0 1px 2px rgba(0,0,0,.08)',
        'md':     '0 4px 12px rgba(0,0,0,.12)',
        'lg':     '0 8px 24px rgba(0,0,0,.16)',
        'xl':     '0 16px 48px rgba(0,0,0,.20)',
        'inner':  'inset 0 2px 4px rgba(0,0,0,.10)',
    };

    const ANIMATION_PRESETS = [
        'none',
        'fadeIn',
        'slideUp',
        'scaleIn',
        'bounceIn',
        'pulse',
    ];

    const ANIMATION_CSS = {
        none:      '',
        fadeIn:    'opacity 0.4s ease',
        slideUp:   'transform 0.4s ease, opacity 0.4s ease',
        scaleIn:   'transform 0.3s ease',
        bounceIn:  'transform 0.5s cubic-bezier(.36,.07,.19,.97)',
        pulse:     'transform 0.6s ease-in-out infinite alternate',
    };

    /* ── Utilities ──────────────────────────────────────────────────────── */

    /** Generate a stable key for an element based on its selector path. */
    function elementKey(el) {
        if (el.dataset.uiKey) return el.dataset.uiKey;
        const parts = [];
        let cur = el;
        while (cur && cur !== document.body) {
            const tag = cur.tagName.toLowerCase();
            const idx = Array.from(cur.parentElement?.children ?? []).indexOf(cur);
            const cls = (cur.className && typeof cur.className === 'string')
                ? '.' + cur.className.trim().split(/\s+/).filter(c => /^fi-|filament/.test(c)).slice(0, 2).join('.')
                : '';
            parts.unshift(`${tag}${cls}[${idx}]`);
            cur = cur.parentElement;
            if (parts.length >= 4) break;
        }
        return parts.join('>');
    }

    /** Find the nearest Filament component ancestor (or self). */
    function nearestComponent(el) {
        for (const { selector, label } of COMPONENT_SELECTORS) {
            const match = el.closest(selector);
            if (match) return { el: match, label: label(match), key: elementKey(match) };
        }
        return null;
    }

    /** Merge a property map into the element's inline style. */
    function applyProps(el, props) {
        for (const [prop, value] of Object.entries(props)) {
            if (!value && value !== 0) {
                el.style.removeProperty(prop);
            } else if (prop === '--gradient') {
                el.style.setProperty('background', value);
            } else if (prop === '--glass') {
                if (value) {
                    el.style.setProperty('backdrop-filter', `blur(${value}px)`);
                    el.style.setProperty('-webkit-backdrop-filter', `blur(${value}px)`);
                    el.style.setProperty('background-color', 'rgba(255,255,255,0.15)');
                } else {
                    el.style.removeProperty('backdrop-filter');
                    el.style.removeProperty('-webkit-backdrop-filter');
                }
            } else if (prop === '--animation') {
                el.style.setProperty('transition', ANIMATION_CSS[value] ?? '');
                if (value === 'pulse') {
                    el.style.setProperty('animation', 'titanPulse 1.2s ease-in-out infinite alternate');
                } else {
                    el.style.removeProperty('animation');
                }
            } else {
                el.style.setProperty(prop, value);
            }
        }
    }

    /** Remove all inspector-applied styles from an element. */
    function clearProps(el, props) {
        const ALL_PROPS = ['padding','margin','border-radius','box-shadow','background-color','color','font-size','font-weight','backdrop-filter','-webkit-backdrop-filter','transition','animation','background'];
        for (const prop of ALL_PROPS) {
            el.style.removeProperty(prop);
        }
    }

    /* ── localStorage helpers ───────────────────────────────────────────── */

    function loadStorage() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY) ?? '{}'); }
        catch { return {}; }
    }

    function saveStorage(data) {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(data)); }
        catch { /* quota exceeded */ }
    }

    /* ── API helpers ────────────────────────────────────────────────────── */

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    }

    async function apiUpsert(componentKey, properties) {
        try {
            await fetch(API_BASE, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
                body: JSON.stringify({ component_key: componentKey, properties }),
            });
        } catch { /* offline — localStorage already saved */ }
    }

    async function apiReset(componentKey) {
        try {
            await fetch(`${API_BASE}/${encodeURIComponent(componentKey)}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken() },
            });
        } catch { /* offline */ }
    }

    /* ── Apply persisted overrides on page load ─────────────────────────── */

    function applyAllStoredOverrides() {
        const data = loadStorage();
        for (const [key, props] of Object.entries(data)) {
            // Best-effort: find element by data-ui-key or skip (Livewire may re-render later)
            const el = document.querySelector(`[data-ui-key="${CSS.escape(key)}"]`);
            if (el) applyProps(el, props);
        }
    }

    // Run on load + after Livewire re-renders
    document.addEventListener('DOMContentLoaded', applyAllStoredOverrides);
    document.addEventListener('livewire:navigated', applyAllStoredOverrides);
    document.addEventListener('livewire:load', applyAllStoredOverrides);

    /* ── Inject animation keyframe ──────────────────────────────────────── */

    if (!document.getElementById('titan-inspector-keyframes')) {
        const style = document.createElement('style');
        style.id = 'titan-inspector-keyframes';
        style.textContent = `
            @keyframes titanPulse {
                from { transform: scale(1); }
                to   { transform: scale(1.03); }
            }
        `;
        document.head.appendChild(style);
    }

    /* ── Alpine.js Component ────────────────────────────────────────────── */

    document.addEventListener('alpine:init', () => {
        Alpine.data('titanUiInspector', () => ({
            /* State */
            active: false,           // inspector on/off
            hoveredEl: null,         // current hovered element
            hoveredLabel: '',
            hoveredKey: '',
            selectedEl: null,        // clicked / selected element
            selectedLabel: '',
            selectedKey: '',
            sidebarOpen: false,
            overlayStyle: {},        // position of the hover highlight box
            tooltipStyle: {},
            sidebarLoading: false,

            /* Property editing */
            props: {
                padding: '',
                margin: '',
                'border-radius': '',
                'box-shadow': 'none',
                'background-color': '',
                color: '',
                'font-size': '',
                'font-weight': '400',
                '--gradient': '',
                '--glass': '',
                '--animation': 'none',
            },
            shadowPreset: 'none',
            gradientType: 'linear',
            gradientFrom: '#6366f1',
            gradientTo: '#8b5cf6',
            gradientAngle: 135,
            glassBlur: 8,

            /* Persisted store */
            store: {},

            /* ── Lifecycle ───────────────────────────────────────────── */

            init() {
                this.store = loadStorage();
                this._onMouseMove = this._handleMouseMove.bind(this);
                this._onMouseOut  = this._handleMouseOut.bind(this);
                this._onClick     = this._handleClick.bind(this);
                this._onKey       = this._handleKey.bind(this);
                this._onLivewire  = applyAllStoredOverrides;

                document.addEventListener('livewire:navigated', this._onLivewire);
            },

            destroy() {
                this.disable();
                document.removeEventListener('livewire:navigated', this._onLivewire);
            },

            /* ── Toggle ──────────────────────────────────────────────── */

            toggle() {
                this.active ? this.disable() : this.enable();
            },

            enable() {
                this.active = true;
                document.addEventListener('mousemove', this._onMouseMove);
                document.addEventListener('mouseout',  this._onMouseOut);
                document.addEventListener('click',     this._onClick, true);
                document.addEventListener('keydown',   this._onKey);
                document.body.classList.add('titan-inspector-active');
            },

            disable() {
                this.active = false;
                this.sidebarOpen = false;
                this.hoveredEl = null;
                this.selectedEl = null;
                document.removeEventListener('mousemove', this._onMouseMove);
                document.removeEventListener('mouseout',  this._onMouseOut);
                document.removeEventListener('click',     this._onClick, true);
                document.removeEventListener('keydown',   this._onKey);
                document.body.classList.remove('titan-inspector-active');
            },

            /* ── Mouse handling ──────────────────────────────────────── */

            _handleMouseMove(e) {
                const comp = nearestComponent(e.target);
                if (!comp) {
                    this.hoveredEl = null;
                    this.overlayStyle = {};
                    return;
                }
                if (comp.el === this.hoveredEl) return;  // no change

                this.hoveredEl    = comp.el;
                this.hoveredLabel = comp.label;
                this.hoveredKey   = comp.key;
                this._updateOverlay(comp.el);
            },

            _handleMouseOut(e) {
                if (!e.relatedTarget || e.relatedTarget === document.body) {
                    this.hoveredEl    = null;
                    this.overlayStyle = {};
                }
            },

            _handleClick(e) {
                if (!this.active) return;
                const comp = nearestComponent(e.target);
                if (!comp) return;

                // Don't prevent the inspector's own sidebar clicks
                if (e.target.closest('#titan-inspector-sidebar')) return;

                e.preventDefault();
                e.stopPropagation();

                this.selectedEl    = comp.el;
                this.selectedLabel = comp.label;
                this.selectedKey   = comp.key;

                this._loadComponentProps(comp.el, comp.key);
                this.sidebarOpen = true;
            },

            _handleKey(e) {
                if (e.key === 'Escape') {
                    this.sidebarOpen = false;
                    this.selectedEl  = null;
                }
            },

            /* ── Overlay ─────────────────────────────────────────────── */

            _updateOverlay(el) {
                const rect = el.getBoundingClientRect();
                this.overlayStyle = {
                    top:    `${rect.top  + window.scrollY}px`,
                    left:   `${rect.left + window.scrollX}px`,
                    width:  `${rect.width}px`,
                    height: `${rect.height}px`,
                };
                this.tooltipStyle = {
                    top:  `${rect.top + window.scrollY - 28}px`,
                    left: `${rect.left + window.scrollX}px`,
                };
            },

            /* ── Sidebar ─────────────────────────────────────────────── */

            _loadComponentProps(el, key) {
                // Start from stored overrides for this key, fill rest from computed style
                const stored = this.store[key] ?? {};
                const computed = window.getComputedStyle(el);

                this.props = {
                    padding:           stored['padding']          ?? computed.padding         ?? '',
                    margin:            stored['margin']           ?? computed.margin           ?? '',
                    'border-radius':   stored['border-radius']    ?? computed.borderRadius     ?? '',
                    'box-shadow':      stored['box-shadow']       ?? computed.boxShadow        ?? 'none',
                    'background-color':stored['background-color'] ?? computed.backgroundColor ?? '',
                    color:             stored['color']            ?? computed.color            ?? '',
                    'font-size':       stored['font-size']        ?? computed.fontSize         ?? '',
                    'font-weight':     stored['font-weight']      ?? computed.fontWeight       ?? '400',
                    '--gradient':      stored['--gradient']       ?? '',
                    '--glass':         stored['--glass']          ?? '',
                    '--animation':     stored['--animation']      ?? 'none',
                };

                // Determine active shadow preset
                this.shadowPreset = Object.keys(SHADOW_PRESETS)
                    .find(k => SHADOW_PRESETS[k] === this.props['box-shadow']) ?? 'none';
            },

            /* ── Live application ────────────────────────────────────── */

            applyProp(prop, value) {
                if (!this.selectedEl) return;
                this.props[prop] = value;
                this._applyAll();
            },

            applyShadowPreset(preset) {
                this.shadowPreset = preset;
                this.props['box-shadow'] = SHADOW_PRESETS[preset] ?? 'none';
                this._applyAll();
            },

            applyGradient() {
                const val = this.gradientType === 'radial'
                    ? `radial-gradient(circle, ${this.gradientFrom}, ${this.gradientTo})`
                    : `linear-gradient(${this.gradientAngle}deg, ${this.gradientFrom}, ${this.gradientTo})`;
                this.props['--gradient'] = val;
                this._applyAll();
            },

            applyGlass() {
                this.props['--glass'] = this.glassBlur > 0 ? String(this.glassBlur) : '';
                this._applyAll();
            },

            _applyAll() {
                if (!this.selectedEl) return;
                applyProps(this.selectedEl, this.props);
            },

            /* ── Save / Reset ────────────────────────────────────────── */

            async save() {
                if (!this.selectedEl) return;
                this.store[this.selectedKey] = { ...this.props };
                saveStorage(this.store);
                await apiUpsert(this.selectedKey, this.props);
            },

            async resetComponent() {
                if (!this.selectedEl) return;
                clearProps(this.selectedEl, this.props);
                delete this.store[this.selectedKey];
                saveStorage(this.store);
                await apiReset(this.selectedKey);

                // Reload from computed style
                this._loadComponentProps(this.selectedEl, this.selectedKey);
            },

            closeSidebar() {
                this.sidebarOpen = false;
            },

            /* ── Computed ────────────────────────────────────────────── */

            get propertyDefs() { return PROPERTY_DEFS; },
            get shadowPresets() { return Object.keys(SHADOW_PRESETS); },
            get animationPresets() { return ANIMATION_PRESETS; },

            sliderValue(prop) {
                const raw = this.props[prop] ?? '';
                return parseFloat(raw) || 0;
            },

            setSlider(prop, unit, e) {
                const val = e.target?.value ?? e;
                this.applyProp(prop, `${val}${unit}`);
            },

            gradientPreview() {
                return this.gradientType === 'radial'
                    ? `radial-gradient(circle, ${this.gradientFrom}, ${this.gradientTo})`
                    : `linear-gradient(${this.gradientAngle}deg, ${this.gradientFrom}, ${this.gradientTo})`;
            },
        }));
    });
})();
