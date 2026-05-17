/**
 * Settings.vue grid-editor drag interaction tests
 *
 * Strategy
 * --------
 * Settings.vue has heavy Inertia.js / router dependencies that do not exist in
 * the Vitest environment, so we stub them at the module level. The component
 * under test is then mounted with minimal props and PlatformLayout replaced by
 * a transparent slot wrapper.
 *
 * Drag behaviour is exercised by:
 *   1. Triggering `pointerdown` on the relevant drag-handle element.
 *   2. Dispatching `pointermove` on `window` with a controlled `clientX/Y`.
 *   3. Triggering `pointerup` on `window` to commit the drag and push history.
 *   4. Asserting the CSS custom-property values in the preview container's
 *      inline style (the `:style="previewVars"` binding).
 */

import { mount } from '@vue/test-utils';
import { reactive } from 'vue';
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';
import { layoutTokenDefaults } from '../layoutTokens';

// ---------------------------------------------------------------------------
// Module-level stubs
// ---------------------------------------------------------------------------

// Inertia provides router/form/page hooks that require a full app context.
// We replace them with lightweight stubs so Settings.vue can render in isolation.
vi.mock('@inertiajs/vue3', () => {
    const formFactory = (initial: Record<string, unknown>) =>
        reactive({
            ...initial,
            processing: false,
            errors: {} as Record<string, string>,
            post: vi.fn(),
        });

    return {
        useForm: vi.fn(formFactory),
        usePage: vi.fn(() => reactive({ props: { flash: {} } })),
        Head: { template: '<span />' },
        router: { visit: vi.fn() },
    };
});

// ---------------------------------------------------------------------------
// Test helpers
// ---------------------------------------------------------------------------

/** Default prop value for every test. */
const defaultSettings = {
    app_name: 'Test App',
    logo_url: null,
    favicon_url: null,
    primary_color: '#2563eb',
    support_email: null,
    footer_text: null,
    custom_css: null,
};

/**
 * Reads the value of a CSS custom property from the preview container.
 * The container is the first element with `:style="previewVars"` (identified by
 * the `bg-slate-950` class that is unique to it in the template).
 */
function getPreviewVar(wrapper: ReturnType<typeof mount>, property: string): string {
    // The preview container applies previewVars as inline style.
    // We look for the div that contains the grid overlay and the sidebar preview.
    const container = wrapper.find('.bg-slate-950.relative.overflow-hidden');
    const style = (container.element as HTMLElement).style;
    return style.getPropertyValue(property).trim();
}

/**
 * Dispatches a PointerEvent on `window` for use after `startDrag` has
 * registered its global listeners.
 */
function dispatchWindowPointerEvent(type: string, init: PointerEventInit): void {
    window.dispatchEvent(new PointerEvent(type, { bubbles: true, ...init }));
}

// ---------------------------------------------------------------------------
// Test suite
// ---------------------------------------------------------------------------

describe('Settings.vue – grid editor', () => {
    // Dynamically import the component so mocks are in place before the
    // module is evaluated.
    let Settings: Awaited<ReturnType<typeof import('../Settings.vue')>>['default'];

    beforeEach(async () => {
        const mod = await import('../Settings.vue');
        Settings = mod.default;
    });

    afterEach(() => {
        vi.resetModules();
    });

    // -----------------------------------------------------------------------
    // mount helper
    // -----------------------------------------------------------------------

    function mountSettings(customCss: string | null = null) {
        return mount(Settings, {
            props: {
                platform: { ...defaultSettings, custom_css: customCss },
            },
            global: {
                stubs: {
                    // Replace the full layout shell with a transparent slot wrapper
                    PlatformLayout: { template: '<div><slot /></div>' },
                },
            },
        });
    }

    it('mounts without missing-prop warnings for platform settings', () => {
        const errorSpy = vi.spyOn(console, 'error').mockImplementation(() => {});
        mountSettings();
        const combinedErrors = errorSpy.mock.calls.flat().join(' ');
        expect(combinedErrors).not.toContain('Missing required prop');
        errorSpy.mockRestore();
    });

    // -----------------------------------------------------------------------
    // Sidebar width drag
    // -----------------------------------------------------------------------

    describe('sidebar width drag', () => {
        it('increases sidebarWidth when dragged right', async () => {
            const wrapper = mountSettings();

            // Find the sidebar drag handle ("⋮" button with title "Drag to resize sidebar width")
            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            expect(handle.exists()).toBe(true);

            // Start dragging from x=0
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });

            // Move 48px to the right (48 is divisible by snap=8 → no extra rounding)
            dispatchWindowPointerEvent('pointermove', { clientX: 48, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--sidebar-width');
            expect(newWidth).toBe('304px'); // 256 + 48 = 304
        });

        it('decreases sidebarWidth when dragged left', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });

            dispatchWindowPointerEvent('pointermove', { clientX: -32, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--sidebar-width');
            expect(newWidth).toBe('224px'); // 256 - 32 = 224
        });

        it('clamps sidebarWidth to the minimum of 192px', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });

            // Drag far left beyond minimum
            dispatchWindowPointerEvent('pointermove', { clientX: -200, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--sidebar-width');
            expect(newWidth).toBe('192px');
        });

        it('clamps sidebarWidth to the maximum of 384px', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });

            // Drag far right beyond maximum
            dispatchWindowPointerEvent('pointermove', { clientX: 500, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--sidebar-width');
            expect(newWidth).toBe('384px');
        });

        it('pushes a history entry when the drag is released', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 40, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            // After a committed drag, the Undo button should be enabled
            const allButtons = wrapper.findAll('button');
            const undoButton = allButtons.find((b) => b.text().trim() === 'Undo');
            expect(undoButton?.attributes('disabled')).toBeUndefined();
        });
    });

    // -----------------------------------------------------------------------
    // Content width drag
    // -----------------------------------------------------------------------

    describe('content width drag', () => {
        it('increases contentMaxWidth when dragged right', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize content width"]');
            expect(handle.exists()).toBe(true);

            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 80, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--content-max-width');
            expect(newWidth).toBe('1040px'); // 960 + 80 = 1040
        });

        it('clamps contentMaxWidth to the minimum of 720px', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize content width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: -500, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--content-max-width');
            expect(newWidth).toBe('720px');
        });

        it('clamps contentMaxWidth to the maximum of 1440px', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize content width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 2000, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--content-max-width');
            expect(newWidth).toBe('1440px');
        });
    });

    // -----------------------------------------------------------------------
    // Snap-to-grid behaviour
    // -----------------------------------------------------------------------

    describe('snap-to-grid behaviour', () => {
        it('snaps sidebarWidth to 8px increments (default snap size)', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });

            // Drag by 13px – nearest 8-multiple is 16 → 256 + 16 = 272
            dispatchWindowPointerEvent('pointermove', { clientX: 13, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--sidebar-width');
            const numeric = parseInt(newWidth, 10);
            expect(numeric % 8).toBe(0); // must be on an 8px grid
        });

        it('snaps sidebarWidth to 4px increments when snap size is 4', async () => {
            const wrapper = mountSettings();

            // Change snap size to 4
            const select = wrapper.find('select');
            await select.setValue('4');
            await wrapper.vm.$nextTick();

            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });

            // Drag 13px → nearest 4-multiple relative to 256 is 268 (256 + 12)
            dispatchWindowPointerEvent('pointermove', { clientX: 13, clientY: 0 });
            await wrapper.vm.$nextTick();

            const newWidth = getPreviewVar(wrapper, '--sidebar-width');
            const numeric = parseInt(newWidth, 10);
            expect(numeric % 4).toBe(0); // must be on a 4px grid
        });

        it('snaps sectionGap to 8px increments when dragging the row-resize handle', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize row spacing"]');
            expect(handle.exists()).toBe(true);

            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });

            // Drag down 11px → nearest 8-multiple is 8 → 24 + 8 = 32
            dispatchWindowPointerEvent('pointermove', { clientX: 0, clientY: 11 });
            await wrapper.vm.$nextTick();

            const gap = getPreviewVar(wrapper, '--layout-section-gap');
            const numeric = parseInt(gap, 10);
            expect(numeric % 8).toBe(0);
        });
    });

    // -----------------------------------------------------------------------
    // Undo / redo
    // -----------------------------------------------------------------------

    describe('undo / redo', () => {
        it('undo restores the previous token values', async () => {
            const wrapper = mountSettings();

            // Perform a committed drag
            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 48, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe('304px');

            // Click Undo
            const undoButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Undo');
            await undoButton!.trigger('click');
            await wrapper.vm.$nextTick();

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe('256px');
        });

        it('redo re-applies the undone change', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 48, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            const undoButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Undo');
            await undoButton!.trigger('click');
            await wrapper.vm.$nextTick();

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe('256px');

            const redoButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Redo');
            await redoButton!.trigger('click');
            await wrapper.vm.$nextTick();

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe('304px');
        });

        it('Undo button is disabled when there is nothing to undo', async () => {
            const wrapper = mountSettings();

            const undoButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Undo');
            expect(undoButton?.attributes('disabled')).toBeDefined();
        });

        it('Redo button is disabled when there is nothing to redo', async () => {
            const wrapper = mountSettings();

            const redoButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Redo');
            expect(redoButton?.attributes('disabled')).toBeDefined();
        });

        it('Redo is disabled after a new drag (future history is pruned)', async () => {
            const wrapper = mountSettings();

            // First drag
            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 48, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            // Undo
            const undoButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Undo');
            await undoButton!.trigger('click');
            await wrapper.vm.$nextTick();

            // Second drag (prunes the future)
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 16, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            const redoButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Redo');
            expect(redoButton?.attributes('disabled')).toBeDefined();
        });

        it('keyboard Ctrl+Z triggers undo', async () => {
            const wrapper = mountSettings();

            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 48, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            window.dispatchEvent(new KeyboardEvent('keydown', { key: 'z', ctrlKey: true, bubbles: true }));
            await wrapper.vm.$nextTick();

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe('256px');
        });

        it('keyboard Ctrl+Y triggers redo', async () => {
            const wrapper = mountSettings();

            // Drag → undo via keyboard → redo via keyboard
            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 48, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            window.dispatchEvent(new KeyboardEvent('keydown', { key: 'z', ctrlKey: true, bubbles: true }));
            await wrapper.vm.$nextTick();

            window.dispatchEvent(new KeyboardEvent('keydown', { key: 'y', ctrlKey: true, bubbles: true }));
            await wrapper.vm.$nextTick();

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe('304px');
        });
    });

    // -----------------------------------------------------------------------
    // Reset behaviour
    // -----------------------------------------------------------------------

    describe('reset behaviour', () => {
        it('"Reset layout" restores all tokens to their defaults', async () => {
            const wrapper = mountSettings();

            // Mutate sidebarWidth and contentMaxWidth via drags
            const sidebarHandle = wrapper.find('[title="Drag to resize sidebar width"]');
            await sidebarHandle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 80, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            const contentHandle = wrapper.find('[title="Drag to resize content width"]');
            await contentHandle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 160, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            // Both values should be non-default now
            expect(getPreviewVar(wrapper, '--sidebar-width')).not.toBe(`${layoutTokenDefaults.sidebarWidth}px`);
            expect(getPreviewVar(wrapper, '--content-max-width')).not.toBe(`${layoutTokenDefaults.contentMaxWidth}px`);

            // Click Reset layout
            const resetButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Reset layout');
            await resetButton!.trigger('click');
            await wrapper.vm.$nextTick();

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe(`${layoutTokenDefaults.sidebarWidth}px`);
            expect(getPreviewVar(wrapper, '--content-max-width')).toBe(`${layoutTokenDefaults.contentMaxWidth}px`);
            expect(getPreviewVar(wrapper, '--layout-section-gap')).toBe(`${layoutTokenDefaults.sectionGap}px`);
        });

        it('"Reset layout" pushes a history entry so it can be undone', async () => {
            const wrapper = mountSettings();

            // Mutate first
            const handle = wrapper.find('[title="Drag to resize sidebar width"]');
            await handle.trigger('pointerdown', { clientX: 0, clientY: 0 });
            dispatchWindowPointerEvent('pointermove', { clientX: 80, clientY: 0 });
            dispatchWindowPointerEvent('pointerup', {});
            await wrapper.vm.$nextTick();

            const mutatedWidth = getPreviewVar(wrapper, '--sidebar-width');

            // Reset
            const resetButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Reset layout');
            await resetButton!.trigger('click');
            await wrapper.vm.$nextTick();

            // Undo the reset
            const undoButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Undo');
            await undoButton!.trigger('click');
            await wrapper.vm.$nextTick();

            // Should return to the mutated value, not the initial default
            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe(mutatedWidth);
        });

        it('resets tokens to defaults even when custom_css had existing values', async () => {
            // Mount with a CSS block containing custom values
            const customCss =
                '/* titan-layout-tokens:start */\n:root {\n  --grid-columns: 12;\n  --sidebar-width: 320px;\n  --content-max-width: 960px;\n  --card-min-height: 176px;\n  --widget-primary-span: 8;\n  --widget-secondary-span: 4;\n  --widget-primary-height: 224px;\n  --widget-secondary-height: 176px;\n  --layout-section-gap: 24px;\n}\n/* titan-layout-tokens:end */';
            const wrapper = mountSettings(customCss);

            // Should start with 320px (from the CSS)
            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe('320px');

            const resetButton = wrapper.findAll('button').find((b) => b.text().trim() === 'Reset layout');
            await resetButton!.trigger('click');
            await wrapper.vm.$nextTick();

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe(`${layoutTokenDefaults.sidebarWidth}px`);
        });
    });

    // -----------------------------------------------------------------------
    // Token initialisation from custom_css
    // -----------------------------------------------------------------------

    describe('token initialisation', () => {
        it('reads persisted tokens from custom_css on mount', async () => {
            const customCss =
                '/* titan-layout-tokens:start */\n:root {\n  --grid-columns: 12;\n  --sidebar-width: 320px;\n  --content-max-width: 1100px;\n  --card-min-height: 176px;\n  --widget-primary-span: 8;\n  --widget-secondary-span: 4;\n  --widget-primary-height: 224px;\n  --widget-secondary-height: 176px;\n  --layout-section-gap: 32px;\n}\n/* titan-layout-tokens:end */';

            const wrapper = mountSettings(customCss);

            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe('320px');
            expect(getPreviewVar(wrapper, '--content-max-width')).toBe('1100px');
            expect(getPreviewVar(wrapper, '--layout-section-gap')).toBe('32px');
        });

        it('falls back to defaults when custom_css is null', async () => {
            const wrapper = mountSettings(null);
            expect(getPreviewVar(wrapper, '--sidebar-width')).toBe(`${layoutTokenDefaults.sidebarWidth}px`);
        });
    });
});
