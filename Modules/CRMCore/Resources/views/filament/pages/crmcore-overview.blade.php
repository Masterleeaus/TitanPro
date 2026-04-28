@php
    use App\Models\Attachment;
    use App\Models\Customer;
    use App\Models\Property;
    use Modules\CRMCore\Models\CRMCoreActivityLog;
    use Modules\CRMCore\Models\Deal;
    use Modules\CRMCore\Models\DealPipeline;
    use Modules\CRMCore\Models\Lead;
    use Modules\CRMCore\Filament\Resources\LeadScoringResource;
    use Modules\CRMCore\Filament\Resources\DealProjectResource;
    use Modules\CRMCore\Filament\Resources\ClientPipelineResource;
    use Modules\CRMCore\Filament\Resources\CRMCoreActivityLogResource;
    use App\Filament\Resources\CustomerResource;
    use App\Filament\Resources\PropertyResource;
    use App\Filament\Resources\AttachmentResource;

    $customersCount = Customer::query()->count();
    $leadsCount = Lead::query()->count();
    $dealsCount = Deal::query()->count();
    $propertiesCount = Property::query()->count();
    $attachmentsCount = class_exists(Attachment::class) ? Attachment::query()->count() : 0;
    $pipelineCount = DealPipeline::query()->count();
    $openDealValue = Deal::query()->whereNull('crmcore_converted_to_project_at')->sum('value');

    $customers = Customer::query()->latest()->limit(8)->get();
    $leads = Lead::query()->with(['leadStatus', 'leadSource'])->latest()->limit(8)->get();
    $deals = Deal::query()->with(['stage', 'pipeline', 'crmcoreCustomer'])->latest()->limit(8)->get();
    $properties = Property::query()->with('customer')->latest()->limit(8)->get();
    $pipelines = DealPipeline::query()->withCount('deals')->latest()->limit(8)->get();
    $attachments = class_exists(Attachment::class) ? Attachment::query()->latest()->limit(6)->get() : collect();
    $activity = CRMCoreActivityLog::query()->latest()->limit(8)->get();

    $url = fn ($resource, $page = 'index') => $resource::getUrl($page);
@endphp

<x-filament-panels::page>
    <div
        x-data="{ tab: localStorage.getItem('crmcore.customerPanel.tab') || 'customers', locked: JSON.parse(localStorage.getItem('crmcore.customerPanel.locked') || '{}'), lock(key){ this.locked[key] = !this.locked[key]; localStorage.setItem('crmcore.customerPanel.locked', JSON.stringify(this.locked)); }, setTab(key){ this.tab = key; localStorage.setItem('crmcore.customerPanel.tab', key); } }"
        class="space-y-6"
    >
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['key' => 'customers', 'label' => 'Total Customers', 'value' => $customersCount, 'hint' => 'Click to open the Customers tab.'],
                ['key' => 'leads', 'label' => 'Open Leads', 'value' => $leadsCount, 'hint' => 'Action: add, score, and follow up leads.'],
                ['key' => 'deals', 'label' => 'Deals', 'value' => $dealsCount, 'hint' => 'Open value: $' . number_format((float) $openDealValue, 2)],
                ['key' => 'properties', 'label' => 'Properties', 'value' => $propertiesCount, 'hint' => 'Click to manage cleaning locations.'],
            ] as $card)
                <a href="#customer-panel-table" @click.prevent="setTab('{{ $card['key'] }}'); location.hash='customer-panel-table'" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-primary-500 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-3">
                        <div><p class="text-sm text-gray-500">{{ $card['label'] }}</p><p class="mt-2 text-3xl font-bold">{{ $card['value'] }}</p></div>
                        <button type="button" @click.stop.prevent="lock('{{ $card['key'] }}')" class="rounded-lg px-2 py-1 text-xs ring-1 ring-gray-300 dark:ring-gray-700" x-text="locked.{{ $card['key'] }} ? 'Locked' : 'Lock'"></button>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">{{ $card['hint'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 lg:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div><h2 class="text-lg font-semibold">Cleaning Growth Actions</h2><p class="text-sm text-gray-500">Fast actions for cleaning sales, follow-up, conversion, and file handling.</p></div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ $url(LeadScoringResource::class, 'create') }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500">Add Lead</a>
                        <a href="{{ $url(DealProjectResource::class, 'create') }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white hover:bg-primary-500">Add Deal</a>
                        <a href="{{ $url(CustomerResource::class, 'create') }}" class="rounded-lg px-3 py-2 text-sm font-semibold ring-1 ring-gray-300 hover:bg-gray-50 dark:ring-gray-700 dark:hover:bg-gray-800">Add Customer</a>
                        <a href="{{ $url(PropertyResource::class, 'create') }}" class="rounded-lg px-3 py-2 text-sm font-semibold ring-1 ring-gray-300 hover:bg-gray-50 dark:ring-gray-700 dark:hover:bg-gray-800">Add Property</a>
                    </div>
                </div>
                <div class="mt-4 grid gap-3 md:grid-cols-3">
                    <button type="button" @click="setTab('leads')" class="rounded-lg border border-dashed border-gray-300 p-4 text-left hover:border-primary-500 dark:border-gray-700"><p class="font-medium">AI lead boost</p><p class="mt-1 text-xs text-gray-500">Open leads and prep prospecting/follow-up.</p></button>
                    <button type="button" @click="setTab('deals')" class="rounded-lg border border-dashed border-gray-300 p-4 text-left hover:border-primary-500 dark:border-gray-700"><p class="font-medium">Convert quote to job</p><p class="mt-1 text-xs text-gray-500">Move won cleaning deals into jobs.</p></button>
                    <button type="button" @click="setTab('pipeline')" class="rounded-lg border border-dashed border-gray-300 p-4 text-left hover:border-primary-500 dark:border-gray-700"><p class="font-medium">Pipeline health</p><p class="mt-1 text-xs text-gray-500">Review stages and stuck opportunities.</p></button>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between gap-3">
                    <div><h2 class="text-lg font-semibold">Attachments</h2><p class="text-sm text-gray-500">{{ $attachmentsCount }} uploaded files</p></div>
                    <a href="{{ $url(AttachmentResource::class, 'create') }}" class="rounded-lg px-3 py-2 text-sm font-semibold ring-1 ring-gray-300 hover:bg-gray-50 dark:ring-gray-700 dark:hover:bg-gray-800">Add File</a>
                </div>
                <div class="mt-4 space-y-2">
                    @forelse ($attachments as $attachment)
                        <div class="rounded-lg bg-gray-50 p-3 text-sm dark:bg-gray-800"><span class="font-medium">{{ $attachment->filename ?? 'Attachment' }}</span><span class="block text-xs text-gray-500">{{ $attachment->tag ?? $attachment->mime_type ?? 'file' }}</span></div>
                    @empty
                        <p class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500 dark:bg-gray-800">No attachments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div id="customer-panel-table" class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-200 p-4 dark:border-gray-800">
                <div class="flex flex-wrap gap-2">
                    @foreach (['customers' => 'Customers', 'deals' => 'Deals', 'leads' => 'Leads', 'properties' => 'Properties', 'pipeline' => 'Pipeline', 'activity' => 'Activity Log'] as $key => $label)
                        <button type="button" @click="setTab('{{ $key }}')" class="rounded-lg px-3 py-2 text-sm font-medium" :class="tab === '{{ $key }}' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700'">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <div class="p-4">
                <div x-show="tab === 'customers'" x-cloak>
                    <div class="mb-3 flex items-center justify-between"><h3 class="font-semibold">Customers</h3><a href="{{ $url(CustomerResource::class, 'create') }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white">Add Customer</a></div>
                    <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b dark:border-gray-800"><th class="py-2">Name</th><th>Email</th><th>Phone</th><th>Notes</th></tr></thead><tbody>@forelse($customers as $customer)<tr class="border-b dark:border-gray-800"><td class="py-2 font-medium">{{ $customer->full_name }}</td><td>{{ $customer->email }}</td><td>{{ $customer->phone ?? $customer->mobile }}</td><td>{{ \Illuminate\Support\Str::limit($customer->notes, 60) }}</td></tr>@empty<tr><td class="py-4 text-gray-500" colspan="4">No customers yet.</td></tr>@endforelse</tbody></table></div>
                </div>
                <div x-show="tab === 'deals'" x-cloak>
                    <div class="mb-3 flex items-center justify-between"><h3 class="font-semibold">Deals</h3><a href="{{ $url(DealProjectResource::class, 'create') }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white">Add Deal</a></div>
                    <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b dark:border-gray-800"><th class="py-2">Deal</th><th>Customer</th><th>Stage</th><th>Value</th><th>Service</th></tr></thead><tbody>@forelse($deals as $deal)<tr class="border-b dark:border-gray-800"><td class="py-2 font-medium">{{ $deal->title }}</td><td>{{ $deal->crmcoreCustomer?->full_name }}</td><td>{{ $deal->stage?->name }}</td><td>${{ number_format((float) $deal->value, 2) }}</td><td>{{ $deal->crmcore_service_interest }}</td></tr>@empty<tr><td class="py-4 text-gray-500" colspan="5">No deals yet.</td></tr>@endforelse</tbody></table></div>
                </div>
                <div x-show="tab === 'leads'" x-cloak>
                    <div class="mb-3 flex items-center justify-between"><h3 class="font-semibold">Leads</h3><a href="{{ $url(LeadScoringResource::class, 'create') }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white">Add Lead</a></div>
                    <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b dark:border-gray-800"><th class="py-2">Lead</th><th>Contact</th><th>Status</th><th>Source</th><th>Score</th></tr></thead><tbody>@forelse($leads as $lead)<tr class="border-b dark:border-gray-800"><td class="py-2 font-medium">{{ $lead->title }}</td><td>{{ $lead->contact_name }}<span class="block text-xs text-gray-500">{{ $lead->contact_email }}</span></td><td>{{ $lead->leadStatus?->name }}</td><td>{{ $lead->leadSource?->name }}</td><td>{{ $lead->crmcore_score }}</td></tr>@empty<tr><td class="py-4 text-gray-500" colspan="5">No leads yet.</td></tr>@endforelse</tbody></table></div>
                </div>
                <div x-show="tab === 'properties'" x-cloak>
                    <div class="mb-3 flex items-center justify-between"><h3 class="font-semibold">Properties</h3><a href="{{ $url(PropertyResource::class, 'create') }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white">Add Property</a></div>
                    <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b dark:border-gray-800"><th class="py-2">Property</th><th>Customer</th><th>City</th><th>State</th><th>Notes</th></tr></thead><tbody>@forelse($properties as $property)<tr class="border-b dark:border-gray-800"><td class="py-2 font-medium">{{ $property->name ?? $property->full_address }}</td><td>{{ $property->customer?->full_name }}</td><td>{{ $property->city }}</td><td>{{ $property->state }}</td><td>{{ \Illuminate\Support\Str::limit($property->notes, 50) }}</td></tr>@empty<tr><td class="py-4 text-gray-500" colspan="5">No properties yet.</td></tr>@endforelse</tbody></table></div>
                </div>
                <div x-show="tab === 'pipeline'" x-cloak>
                    <div class="mb-3 flex items-center justify-between"><h3 class="font-semibold">Pipeline</h3><a href="{{ $url(ClientPipelineResource::class) }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white">Open Pipeline</a></div>
                    <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead><tr class="border-b dark:border-gray-800"><th class="py-2">Pipeline</th><th>Deals</th><th>Active</th><th>Description</th></tr></thead><tbody>@forelse($pipelines as $pipeline)<tr class="border-b dark:border-gray-800"><td class="py-2 font-medium">{{ $pipeline->name }}</td><td>{{ $pipeline->deals_count }}</td><td>{{ $pipeline->is_active ? 'Yes' : 'No' }}</td><td>{{ \Illuminate\Support\Str::limit($pipeline->description, 80) }}</td></tr>@empty<tr><td class="py-4 text-gray-500" colspan="4">No pipelines yet.</td></tr>@endforelse</tbody></table></div>
                </div>
                <div x-show="tab === 'activity'" x-cloak>
                    <div class="mb-3 flex items-center justify-between"><h3 class="font-semibold">Activity Log</h3><a href="{{ $url(CRMCoreActivityLogResource::class) }}" class="rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white">Open Activity</a></div>
                    <div class="space-y-2">@forelse($activity as $log)<div class="rounded-lg bg-gray-50 p-3 text-sm dark:bg-gray-800"><span class="font-medium">{{ $log->description ?? $log->event ?? 'CRM activity' }}</span><span class="block text-xs text-gray-500">{{ optional($log->created_at)->diffForHumans() }}</span></div>@empty<p class="rounded-lg bg-gray-50 p-3 text-sm text-gray-500 dark:bg-gray-800">No activity yet.</p>@endforelse</div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
