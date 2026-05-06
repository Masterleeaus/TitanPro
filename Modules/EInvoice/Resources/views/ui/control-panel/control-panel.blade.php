{{-- Titan one-page AI controlled Money Manager panel. --}}
<div class="titan-module-control-panel" data-module="{{ $module ?? 'money-manager' }}">
    <x-einvoice::ui.header>
        <div class="space-y-1">
            <h1 class="text-2xl font-semibold">{{ $title ?? 'Titan Money Panel' }}</h1>
            <p class="text-sm opacity-70">Money Manager: invoices, late follow-ups, receivables, accounting, GST/compliance, and AI control.</p>
        </div>
    </x-einvoice::ui.header>

    <x-einvoice::ui.metrics-grid>
        @foreach (($metrics ?? []) as $metric)
            <div class="titan-metric-card" data-metric="{{ $metric }}">{{ str($metric)->headline() }}</div>
        @endforeach
    </x-einvoice::ui.metrics-grid>

    <x-einvoice::ui.workspace>
        <div class="titan-workspace-tabs">
            @foreach (($tabs ?? []) as $tab)
                <section class="titan-workspace-tab" data-tab="{{ $tab['key'] ?? '' }}">
                    <h2>{{ $tab['label'] ?? '' }}</h2>
                    <p>{{ $tab['description'] ?? '' }}</p>
                </section>
            @endforeach
        </div>
    </x-einvoice::ui.workspace>

    <x-einvoice::ui.agent-panel>
        <strong>{{ $agent ?? 'TitanZero Money' }}</strong>
        <span> handles invoice automation, late follow-up, receivables, accounting journals, GST, and compliance. Payments stay in the external ZeroPay system.</span>
    </x-einvoice::ui.agent-panel>

    <x-einvoice::ui.bottom-tabs>
        <span>Boundary: Titan Money does invoicing + accounting + follow-up only. ZeroPay owns payment sessions, PayID, QR, card, webhooks, and settlement.</span>
    </x-einvoice::ui.bottom-tabs>
</div>
