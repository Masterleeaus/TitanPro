<x-filament-panels::page>
    <div class="space-y-6">
        <section class="grid grid-cols-1 gap-4 xl:grid-cols-12">
            <div class="xl:col-span-8 space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <x-titan::module.metric-card key="records" label="Records" value="{{ $this->recordsCount ?? '—' }}" />
                    <x-titan::module.metric-card key="open_tasks" label="Open Tasks" value="{{ $this->openTasksCount ?? '—' }}" />
                    <x-titan::module.metric-card key="agent_health" label="Agent Health" value="{{ $this->agentHealth ?? 'Ready' }}" />
                </div>
                <x-titan::module.widget-grid module="example-module" />
            </div>
            <aside class="xl:col-span-4 space-y-4">
                <x-titan-agents::module-chat module="example-module" agent="example.module.agent" mode="grounded" voice="true" channel-control="true" placeholder="Ask or command this module…" />
                <x-titan::module.shortcuts-card module="example-module" />
                <x-titan::module.settings-card module="example-module" />
            </aside>
        </section>
        <section>
            <x-titan::module.table-tabs-card module="example-module" manifest="Modules/ExampleModule/Filament/Tables/table-tabs.json" />
        </section>
    </div>
</x-filament-panels::page>
