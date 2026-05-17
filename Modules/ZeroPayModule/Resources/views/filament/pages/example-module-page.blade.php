<x-filament-panels::page>
    <div class="space-y-6">
        <section class="grid grid-cols-1 gap-4 xl:grid-cols-12">
            <div class="xl:col-span-8 space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <x-titan::module.metric-card key="records" label="Records" value="{{ $this->recordsCount ?? '—' }}" />
                    <x-titan::module.metric-card key="open_tasks" label="Open Tasks" value="{{ $this->openTasksCount ?? '—' }}" />
                    <x-titan::module.metric-card key="agent_health" label="Agent Health" value="{{ $this->agentHealth ?? 'Ready' }}" />
                </div>
                <x-titan::module.widget-grid module="zeropay-module" />
            </div>
            <aside class="xl:col-span-4 space-y-4">
                <x-titan-agents::module-chat module="zeropay-module" agent="zeropay.module.agent" mode="grounded" voice="true" channel-control="true" placeholder="Ask or command this module…" />
                <x-titan::module.shortcuts-card module="zeropay-module" />
                <x-titan::module.settings-card module="zeropay-module" />
            </aside>
        </section>
        <section>
            <x-titan::module.table-tabs-card module="zeropay-module" manifest="Modules/ZeroPayModule/Filament/Tables/table-tabs.json" />
        </section>
    </div>
</x-filament-panels::page>
