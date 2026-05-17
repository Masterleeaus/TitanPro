{{-- Demo one-page module control panel. UI components are composed here. --}}
<x-zeropay-module::workspace>
    <x-zeropay-module::header title="Demo Module" assistant="Zero" />
    <x-zeropay-module::metrics-grid />
    <x-zeropay-module::agent-panel assistant="Zero" agent="demo.agent" mode="demo" />
    <x-zeropay-module::bottom-tabs />
</x-zeropay-module::workspace>
