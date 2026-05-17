import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';

// ---------------------------------------------------------------------------
// Hoist mocks — vi.mock() is always hoisted before imports by vitest.
// ---------------------------------------------------------------------------

vi.mock('@inertiajs/vue3', () => ({
    usePage: vi.fn(() => ({ props: { role_ui: null } })),
    Head: { template: '<span></span>' },
}));

vi.mock('@/layouts/AppLayout.vue', () => ({
    default: { template: '<div data-testid="app-layout"><slot /></div>' },
}));

// Import after mocks are registered so the component gets the mocked modules.
import { usePage } from '@inertiajs/vue3';
import Dashboard from '@/pages/Dashboard.vue';

// ---------------------------------------------------------------------------
// Constants — must match PLATFORM_DEFAULT_WIDGETS in Dashboard.vue.
// ---------------------------------------------------------------------------

const ALL_WIDGET_TYPES = [
    'kpi-grid-card',
    'stat-card',
    'recent-activity-card',
    'alert-notice-card',
    'chart-bar-card',
    'chart-line-card',
    'map-card',
    'table-card',
] as const;

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function makeRoleUi(widget_layout: string[], role = 'technician') {
    return { role, hidden_nav_items: [] as string[], widget_layout, theme: {} as Record<string, string> };
}

function mountWithRoleUi(roleUi: ReturnType<typeof makeRoleUi> | null) {
    vi.mocked(usePage).mockReturnValue({ props: { role_ui: roleUi } } as ReturnType<typeof usePage>);
    return mount(Dashboard);
}

// ---------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------

describe('Dashboard', () => {
    beforeEach(() => {
        vi.mocked(usePage).mockReturnValue({ props: { role_ui: null } } as ReturnType<typeof usePage>);
    });

    it('renders all platform-default widgets when role_ui is absent', () => {
        const wrapper = mountWithRoleUi(null);
        const cards = wrapper.findAll('[data-widget-type]');
        expect(cards).toHaveLength(ALL_WIDGET_TYPES.length);
        const types = cards.map((c) => c.attributes('data-widget-type'));
        for (const type of ALL_WIDGET_TYPES) {
            expect(types).toContain(type);
        }
    });

    it('falls back to platform defaults when widget_layout is empty', () => {
        const wrapper = mountWithRoleUi(makeRoleUi([]));
        const cards = wrapper.findAll('[data-widget-type]');
        expect(cards).toHaveLength(ALL_WIDGET_TYPES.length);
    });

    it('renders only the widgets listed in widget_layout', () => {
        const layout = ['stat-card', 'recent-activity-card'];
        const wrapper = mountWithRoleUi(makeRoleUi(layout));
        const cards = wrapper.findAll('[data-widget-type]');
        expect(cards).toHaveLength(2);
        expect(cards.map((c) => c.attributes('data-widget-type'))).toEqual(layout);
    });

    it('preserves the order defined in widget_layout', () => {
        const layout = ['alert-notice-card', 'kpi-grid-card', 'map-card'];
        const wrapper = mountWithRoleUi(makeRoleUi(layout));
        const cards = wrapper.findAll('[data-widget-type]');
        expect(cards.map((c) => c.attributes('data-widget-type'))).toEqual(layout);
    });

    it('silently skips unknown widget types in widget_layout', () => {
        const layout = ['kpi-grid-card', 'unknown-widget-xyz'];
        const wrapper = mountWithRoleUi(makeRoleUi(layout));
        const cards = wrapper.findAll('[data-widget-type]');
        expect(cards).toHaveLength(1);
        expect(cards[0].attributes('data-widget-type')).toBe('kpi-grid-card');
    });

    it('finance / bookkeeper role sees only configured finance widgets', () => {
        const financeLayout = ['kpi-grid-card', 'chart-bar-card', 'chart-line-card', 'table-card'];
        const wrapper = mountWithRoleUi(makeRoleUi(financeLayout, 'bookkeeper'));
        const cards = wrapper.findAll('[data-widget-type]');
        expect(cards).toHaveLength(4);
        expect(cards.map((c) => c.attributes('data-widget-type'))).toEqual(financeLayout);
    });

    it('dispatch role sees map-card first as configured', () => {
        const dispatchLayout = ['map-card', 'kpi-grid-card', 'recent-activity-card'];
        const wrapper = mountWithRoleUi(makeRoleUi(dispatchLayout, 'dispatcher'));
        const cards = wrapper.findAll('[data-widget-type]');
        expect(cards).toHaveLength(3);
        expect(cards[0].attributes('data-widget-type')).toBe('map-card');
        expect(cards.map((c) => c.attributes('data-widget-type'))).toEqual(dispatchLayout);
    });

    it('technician role sees minimal widget set', () => {
        const techLayout = ['stat-card', 'alert-notice-card'];
        const wrapper = mountWithRoleUi(makeRoleUi(techLayout, 'technician'));
        const cards = wrapper.findAll('[data-widget-type]');
        expect(cards).toHaveLength(2);
        expect(cards.map((c) => c.attributes('data-widget-type'))).toEqual(techLayout);
    });
});
