import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

import ControlPanelRenderer from '@/components/ControlPanelRenderer.vue';
import BarChart from '@/components/widgets/BarChart.vue';
import ChatThread from '@/components/widgets/ChatThread.vue';
import DataTable from '@/components/widgets/DataTable.vue';
import JsonWidget from '@/components/widgets/JsonWidget.vue';
import LineChart from '@/components/widgets/LineChart.vue';
import LogList from '@/components/widgets/LogList.vue';
import McpServerList from '@/components/widgets/McpServerList.vue';
import MetricCard from '@/components/widgets/MetricCard.vue';
import ProjectList from '@/components/widgets/ProjectList.vue';
import SettingsForm from '@/components/widgets/SettingsForm.vue';
import ToolCallCard from '@/components/widgets/ToolCallCard.vue';
import type { ControlPanelWidget } from '@/types/control-panel';

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function makeWidget(
    overrides: Partial<ControlPanelWidget> = {},
): ControlPanelWidget {
    return { id: '1', kind: 'metric-card', ...overrides };
}

// ---------------------------------------------------------------------------
// MetricCard
// ---------------------------------------------------------------------------

describe('MetricCard', () => {
    it('renders value and title', () => {
        const wrapper = mount(MetricCard, {
            props: {
                widget: makeWidget({
                    title: 'Revenue',
                    data: { value: '$1,200', change: '+10%', trend: 'up' },
                }),
            },
        });
        expect(wrapper.text()).toContain('Revenue');
        expect(wrapper.text()).toContain('$1,200');
        expect(wrapper.text()).toContain('+10%');
    });

    it('renders neutral trend with fallback dash', () => {
        const wrapper = mount(MetricCard, {
            props: { widget: makeWidget({ data: {} }) },
        });
        expect(wrapper.text()).toContain('—');
    });
});

// ---------------------------------------------------------------------------
// LineChart
// ---------------------------------------------------------------------------

describe('LineChart', () => {
    it('renders SVG polyline for each dataset', () => {
        const wrapper = mount(LineChart, {
            props: {
                widget: makeWidget({
                    kind: 'line-chart',
                    title: 'Sales',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar'],
                        datasets: [{ label: 'Revenue', data: [10, 20, 15] }],
                    },
                }),
            },
        });
        expect(wrapper.find('svg').exists()).toBe(true);
        expect(wrapper.find('polyline').exists()).toBe(true);
    });

    it('shows empty state when no datasets', () => {
        const wrapper = mount(LineChart, {
            props: {
                widget: makeWidget({
                    kind: 'line-chart',
                    data: { labels: [], datasets: [] },
                }),
            },
        });
        expect(wrapper.text()).toContain('No data');
    });
});

// ---------------------------------------------------------------------------
// BarChart
// ---------------------------------------------------------------------------

describe('BarChart', () => {
    it('renders rects for bar data', () => {
        const wrapper = mount(BarChart, {
            props: {
                widget: makeWidget({
                    kind: 'bar-chart',
                    title: 'Jobs by Type',
                    data: {
                        labels: ['A', 'B', 'C'],
                        datasets: [{ label: 'Count', data: [3, 7, 5] }],
                    },
                }),
            },
        });
        expect(wrapper.find('svg').exists()).toBe(true);
        const rects = wrapper.findAll('rect');
        expect(rects.length).toBe(3);
    });

    it('shows empty state when no datasets', () => {
        const wrapper = mount(BarChart, {
            props: {
                widget: makeWidget({
                    kind: 'bar-chart',
                    data: { labels: [], datasets: [] },
                }),
            },
        });
        expect(wrapper.text()).toContain('No data');
    });
});

// ---------------------------------------------------------------------------
// DataTable
// ---------------------------------------------------------------------------

describe('DataTable', () => {
    it('renders column headers and rows', () => {
        const wrapper = mount(DataTable, {
            props: {
                widget: makeWidget({
                    kind: 'data-table',
                    title: 'Invoices',
                    data: [
                        { id: 1, name: 'Alice', status: 'paid' },
                        { id: 2, name: 'Bob', status: 'pending' },
                    ],
                }),
            },
        });
        expect(wrapper.text()).toContain('id');
        expect(wrapper.text()).toContain('Alice');
        expect(wrapper.text()).toContain('Bob');
    });

    it('shows empty state for no rows', () => {
        const wrapper = mount(DataTable, {
            props: { widget: makeWidget({ kind: 'data-table', data: [] }) },
        });
        expect(wrapper.text()).toContain('No data');
    });

    it('emits rowClick when a row is clicked', async () => {
        const wrapper = mount(DataTable, {
            props: {
                widget: makeWidget({
                    kind: 'data-table',
                    data: [{ id: 99, name: 'Test' }],
                }),
            },
        });
        await wrapper.find('tbody tr').trigger('click');
        expect(wrapper.emitted('rowClick')).toBeTruthy();
        expect(
            (wrapper.emitted('rowClick')![0][0] as Record<string, unknown>).id,
        ).toBe(99);
    });
});

// ---------------------------------------------------------------------------
// LogList
// ---------------------------------------------------------------------------

describe('LogList', () => {
    it('renders plain string logs', () => {
        const wrapper = mount(LogList, {
            props: {
                widget: makeWidget({
                    kind: 'log-list',
                    data: ['Log line one', 'Log line two'],
                }),
            },
        });
        expect(wrapper.text()).toContain('Log line one');
        expect(wrapper.text()).toContain('Log line two');
    });

    it('renders structured log with level badge', () => {
        const wrapper = mount(LogList, {
            props: {
                widget: makeWidget({
                    kind: 'log-list',
                    data: [
                        { level: 'error', message: 'Boom', timestamp: '12:00' },
                    ],
                }),
            },
        });
        expect(wrapper.text()).toContain('error');
        expect(wrapper.text()).toContain('Boom');
    });

    it('shows empty state for no logs', () => {
        const wrapper = mount(LogList, {
            props: { widget: makeWidget({ kind: 'log-list', data: [] }) },
        });
        expect(wrapper.text()).toContain('No log entries');
    });
});

// ---------------------------------------------------------------------------
// ChatThread
// ---------------------------------------------------------------------------

describe('ChatThread', () => {
    it('renders messages', () => {
        const wrapper = mount(ChatThread, {
            props: {
                widget: makeWidget({
                    kind: 'chat-thread',
                    title: 'Support Chat',
                    data: [
                        { id: 'm1', role: 'user', content: 'Hello there' },
                        {
                            id: 'm2',
                            role: 'assistant',
                            content: 'Hi, how can I help?',
                        },
                    ],
                }),
            },
        });
        expect(wrapper.text()).toContain('Hello there');
        expect(wrapper.text()).toContain('Hi, how can I help?');
    });

    it('shows empty state when no messages', () => {
        const wrapper = mount(ChatThread, {
            props: { widget: makeWidget({ kind: 'chat-thread', data: [] }) },
        });
        expect(wrapper.text()).toContain('No messages');
    });
});

// ---------------------------------------------------------------------------
// ToolCallCard
// ---------------------------------------------------------------------------

describe('ToolCallCard', () => {
    it('shows correct status badge for all 4 states', () => {
        const statuses = ['queued', 'running', 'completed', 'failed'] as const;
        for (const status of statuses) {
            const wrapper = mount(ToolCallCard, {
                props: {
                    widget: makeWidget({
                        kind: 'tool-call',
                        data: { id: 'tc1', name: 'search_jobs', status },
                    }),
                },
            });
            const text = wrapper.text().toLowerCase();
            expect(text).toContain(status);
        }
    });

    it('renders tool name', () => {
        const wrapper = mount(ToolCallCard, {
            props: {
                widget: makeWidget({
                    kind: 'tool-call',
                    data: {
                        id: 'tc2',
                        name: 'get_customer',
                        status: 'completed',
                    },
                }),
            },
        });
        expect(wrapper.text()).toContain('get_customer');
    });

    it('expands arguments on toggle', async () => {
        const wrapper = mount(ToolCallCard, {
            props: {
                widget: makeWidget({
                    kind: 'tool-call',
                    data: {
                        id: 'tc3',
                        name: 'fn',
                        arguments: { key: 'val' },
                        status: 'completed',
                    },
                }),
            },
        });
        await wrapper.find('button').trigger('click');
        expect(wrapper.text()).toContain('"key"');
    });
});

// ---------------------------------------------------------------------------
// SettingsForm
// ---------------------------------------------------------------------------

describe('SettingsForm', () => {
    it('renders labeled inputs from props', () => {
        const wrapper = mount(SettingsForm, {
            props: {
                widget: makeWidget({
                    kind: 'settings-form',
                    title: 'App Settings',
                    props: {
                        appName: {
                            label: 'App Name',
                            type: 'text',
                            value: 'Titan',
                        },
                        enabled: {
                            label: 'Enabled',
                            type: 'boolean',
                            value: true,
                        },
                    },
                }),
            },
        });
        expect(wrapper.text()).toContain('App Name');
        expect(wrapper.text()).toContain('Enabled');
    });

    it('emits save event with current values when form is submitted', async () => {
        const wrapper = mount(SettingsForm, {
            props: {
                widget: makeWidget({
                    kind: 'settings-form',
                    props: {
                        siteName: {
                            label: 'Site Name',
                            type: 'text',
                            value: 'My Site',
                        },
                    },
                }),
            },
        });
        await wrapper.find('form').trigger('submit');
        expect(wrapper.emitted('save')).toBeTruthy();
        const payload = wrapper.emitted('save')![0][0] as Record<
            string,
            unknown
        >;
        expect(payload.siteName).toBe('My Site');
    });

    it('shows empty state when no props', () => {
        const wrapper = mount(SettingsForm, {
            props: { widget: makeWidget({ kind: 'settings-form', props: {} }) },
        });
        expect(wrapper.text()).toContain('No fields defined');
    });
});

// ---------------------------------------------------------------------------
// McpServerList
// ---------------------------------------------------------------------------

describe('McpServerList', () => {
    it('renders server names and statuses', () => {
        const wrapper = mount(McpServerList, {
            props: {
                widget: makeWidget({
                    kind: 'mcp-server-list',
                    data: [
                        {
                            name: 'core-mcp',
                            url: 'https://mcp.example.com',
                            status: 'connected',
                            capabilities: ['tools', 'prompts'],
                        },
                        {
                            name: 'offline-mcp',
                            url: 'https://mcp2.example.com',
                            status: 'disconnected',
                        },
                    ],
                }),
            },
        });
        expect(wrapper.text()).toContain('core-mcp');
        expect(wrapper.text()).toContain('offline-mcp');
        expect(wrapper.text()).toContain('Connected');
    });

    it('emits connect event for disconnected server', async () => {
        const wrapper = mount(McpServerList, {
            props: {
                widget: makeWidget({
                    kind: 'mcp-server-list',
                    data: [
                        {
                            name: 'srv',
                            url: 'https://srv.example.com',
                            status: 'disconnected',
                        },
                    ],
                }),
            },
        });
        await wrapper.find('button').trigger('click');
        expect(wrapper.emitted('connect')).toBeTruthy();
    });

    it('shows empty state when no servers', () => {
        const wrapper = mount(McpServerList, {
            props: {
                widget: makeWidget({ kind: 'mcp-server-list', data: [] }),
            },
        });
        expect(wrapper.text()).toContain('No servers configured');
    });
});

// ---------------------------------------------------------------------------
// ProjectList
// ---------------------------------------------------------------------------

describe('ProjectList', () => {
    it('renders project names and statuses', () => {
        const wrapper = mount(ProjectList, {
            props: {
                widget: makeWidget({
                    kind: 'project-list',
                    data: [
                        {
                            id: 1,
                            name: 'Website Redesign',
                            status: 'In Progress',
                            assignee: 'Alice',
                            dueDate: '2026-06-01',
                        },
                        { id: 2, name: 'API Migration', status: 'Completed' },
                    ],
                }),
            },
        });
        expect(wrapper.text()).toContain('Website Redesign');
        expect(wrapper.text()).toContain('API Migration');
        expect(wrapper.text()).toContain('Alice');
    });

    it('emits open when a project card is clicked', async () => {
        const wrapper = mount(ProjectList, {
            props: {
                widget: makeWidget({
                    kind: 'project-list',
                    data: [{ id: 42, name: 'Test Project', status: 'active' }],
                }),
            },
        });
        await wrapper.find('button').trigger('click');
        expect(wrapper.emitted('open')).toBeTruthy();
        const payload = wrapper.emitted('open')![0][0] as { id: number };
        expect(payload.id).toBe(42);
    });

    it('shows empty state for no projects', () => {
        const wrapper = mount(ProjectList, {
            props: { widget: makeWidget({ kind: 'project-list', data: [] }) },
        });
        expect(wrapper.text()).toContain('No projects');
    });
});

// ---------------------------------------------------------------------------
// JsonWidget (fallback)
// ---------------------------------------------------------------------------

describe('JsonWidget', () => {
    it('renders JSON dump of data', () => {
        const wrapper = mount(JsonWidget, {
            props: {
                widget: makeWidget({
                    title: 'Unknown Widget',
                    data: { foo: 'bar' },
                }),
            },
        });
        expect(wrapper.text()).toContain('"foo"');
        expect(wrapper.text()).toContain('"bar"');
    });
});

// ---------------------------------------------------------------------------
// ControlPanelRenderer
// ---------------------------------------------------------------------------

describe('ControlPanelRenderer', () => {
    it('shows empty state when no widgets', () => {
        const wrapper = mount(ControlPanelRenderer, {
            props: { widgets: [] },
        });
        expect(wrapper.text()).toContain('No dashboard generated yet');
    });

    it('renders MetricCard for metric-card kind', () => {
        const wrapper = mount(ControlPanelRenderer, {
            props: {
                widgets: [
                    makeWidget({
                        id: 'w1',
                        kind: 'metric-card',
                        title: 'Revenue',
                        data: { value: 99 },
                    }),
                ],
            },
        });
        expect(wrapper.text()).toContain('Revenue');
        expect(wrapper.text()).toContain('99');
    });

    it('falls back to JsonWidget for unknown kind', () => {
        const wrapper = mount(ControlPanelRenderer, {
            props: {
                widgets: [
                    {
                        id: 'w2',
                        kind: 'unknown-kind' as ControlPanelWidget['kind'],
                        data: { x: 1 },
                    },
                ],
            },
        });
        expect(wrapper.text()).toContain('"x"');
    });

    it('renders all 10 known kinds without error', () => {
        const widgets: ControlPanelWidget[] = [
            { id: '1', kind: 'metric-card', data: { value: 1 } },
            { id: '2', kind: 'line-chart', data: { labels: [], datasets: [] } },
            { id: '3', kind: 'bar-chart', data: { labels: [], datasets: [] } },
            { id: '4', kind: 'data-table', data: [] },
            { id: '5', kind: 'log-list', data: [] },
            { id: '6', kind: 'chat-thread', data: [] },
            {
                id: '7',
                kind: 'tool-call',
                data: { id: 'x', name: 'fn', status: 'queued' },
            },
            { id: '8', kind: 'settings-form', props: {} },
            { id: '9', kind: 'mcp-server-list', data: [] },
            { id: '10', kind: 'project-list', data: [] },
        ];
        expect(() =>
            mount(ControlPanelRenderer, { props: { widgets } }),
        ).not.toThrow();
    });
});
