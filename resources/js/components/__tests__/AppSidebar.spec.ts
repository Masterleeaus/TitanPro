import { mount } from '@vue/test-utils';
import { reactive } from 'vue';
import { beforeEach, describe, expect, it, vi } from 'vitest';

const pageState = reactive({
    url: '/dashboard',
    props: {
        auth: {
            roles: ['owner'],
        },
        plan: {
            current: 'starter',
        },
        role_ui: null as null | {
            hidden_nav_items: string[];
        },
    },
});

vi.mock('@inertiajs/vue3', () => ({
    Link: {
        props: ['href'],
        template: '<a :href="href"><slot /></a>',
    },
    usePage: vi.fn(() => pageState),
}));

vi.mock('@/routes', () => ({
    dashboard: () => '/dashboard',
}));

import AppSidebar from '../AppSidebar.vue';

const globalStubs = {
    AppLogo: { template: '<div>Logo</div>' },
    NavUser: { template: '<div>User</div>' },
    Sidebar: { template: '<div><slot /></div>' },
    SidebarContent: { template: '<div><slot /></div>' },
    SidebarFooter: { template: '<div><slot /></div>' },
    SidebarGroup: { template: '<div><slot /></div>' },
    SidebarGroupContent: { template: '<div><slot /></div>' },
    SidebarGroupLabel: { template: '<div><slot /></div>' },
    SidebarHeader: { template: '<div><slot /></div>' },
    SidebarMenu: { template: '<div><slot /></div>' },
    SidebarMenuButton: { template: '<div><slot /></div>' },
    SidebarMenuItem: { template: '<div><slot /></div>' },
};

describe('AppSidebar', () => {
    beforeEach(() => {
        pageState.url = '/dashboard';
        pageState.props.auth.roles = ['owner'];
        pageState.props.plan.current = 'starter';
        pageState.props.role_ui = null;
    });

    it('hides nav items listed in role_ui.hidden_nav_items and drops empty groups', () => {
        pageState.props.role_ui = {
            hidden_nav_items: [
                'Dashboard',
                'TitanGo Panel',
                'GroundZero Panel',
                'ZeroPay Panel',
                'TitanStudio Panel',
                'TitanNexus Panel',
                'TitanSolo Panel',
                'Github Repo',
                'Documentation',
            ],
        };

        const wrapper = mount(AppSidebar, {
            global: {
                stubs: globalStubs,
            },
        });

        expect(wrapper.text()).not.toContain('Dashboard');
        expect(wrapper.text()).not.toContain('TitanGo Panel');
        expect(wrapper.text()).not.toContain('Documentation');
        expect(wrapper.text()).not.toContain('Platform');
    });

    it('renders the default navigation when no role UI profile exists', () => {
        const wrapper = mount(AppSidebar, {
            global: {
                stubs: globalStubs,
            },
        });

        expect(wrapper.text()).toContain('Platform');
        expect(wrapper.text()).toContain('Dashboard');
        expect(wrapper.text()).toContain('TitanGo Panel');
        expect(wrapper.text()).toContain('GroundZero Panel');
        expect(wrapper.text()).toContain('ZeroPay Panel');
        expect(wrapper.text()).toContain('TitanStudio Panel');
        expect(wrapper.text()).toContain('TitanNexus Panel');
        expect(wrapper.text()).toContain('TitanSolo Panel');
        expect(wrapper.text()).toContain('Github Repo');
        expect(wrapper.text()).toContain('Documentation');
    });
});
