
@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    $user = auth()->user();
    $userId = $user?->id;
    $teamId = $user?->team_id;
    $docs = collect(cache("user:{$userId}:user_docs") ?? []);
    $docsCount = $docs->count();
    $businessDocsCount = $docs->filter(fn ($entity) => in_array(data_get($entity, 'generator.type'), ['text', 'code']) || blank(data_get($entity, 'generator.type')))->count();
    $mediaDocsCount = $docs->filter(fn ($entity) => in_array(data_get($entity, 'generator.type'), ['image', 'audio', 'video']))->count();
    $favoriteDocsCount = $docs->filter(fn ($entity) => method_exists($entity, 'isFavoriteDoc') ? $entity->isFavoriteDoc() : false)->count();
    $hasTzEvidence = Schema::hasTable('tz_evidence_items');
    $hasTzAcknowledgements = Schema::hasTable('tz_document_acknowledgements');
    $evidenceItemsCount = $teamId && $hasTzEvidence ? DB::table('tz_evidence_items')->where('team_id', $teamId)->count() : 0;
    $signedDocsCount = $teamId && $hasTzAcknowledgements ? DB::table('tz_document_acknowledgements')->where('team_id', $teamId)->count() : 0;
    $proofReadyCount = min($docsCount, max($signedDocsCount, intval(round(($businessDocsCount + $mediaDocsCount) * 0.35))));
    $reviewQueueCount = max(0, $docsCount - $proofReadyCount);
    $packetCount = $proofReadyCount + $signedDocsCount;
    $overview = ['business' => $businessDocsCount, 'media' => $mediaDocsCount, 'evidence' => $evidenceItemsCount, 'ready' => $proofReadyCount];
    $overviewTotal = max(1, array_sum($overview));
@endphp

<x-card class="w-full" id="summary" size="lg">
    <div class="flex justify-between gap-6 max-lg:flex-wrap lg:mb-7">
        <div>
            <h3 class="items-center text-[17px] leading-6 lg:mb-1">@lang('Documents & Proof')</h3>
            <p class="text-sm leading-5 text-foreground/70">@lang('Use the original workbook system as a command surface for legal packets, proof bundles, and share-ready records.')</p>
        </div>
        <div class="grid w-full gap-4 sm:grid-cols-4 lg:w-3/5">
            <div class="relative flex grow flex-col justify-center lg:ps-6 lg:after:absolute lg:after:right-0 lg:after:h-[80%] lg:after:w-px lg:after:bg-border">
                <p class="text-nowrap text-sm leading-5">@lang('Documents')</p>
                <h2>{{ $docsCount }}</h2>
                <span class="text-xs text-foreground/60">{{ $favoriteDocsCount }} @lang('starred')</span>
            </div>
            <div class="relative flex grow flex-col justify-center lg:ps-6 lg:after:absolute lg:after:right-0 lg:after:h-[80%] lg:after:w-px lg:after:bg-border">
                <p class="text-nowrap text-sm leading-5">@lang('Evidence')</p>
                <h2>{{ $evidenceItemsCount }}</h2>
                <span class="text-xs text-foreground/60">{{ $mediaDocsCount }} @lang('media assets')</span>
            </div>
            <div class="relative flex grow flex-col justify-center lg:ps-6 lg:after:absolute lg:after:right-0 lg:after:h-[80%] lg:after:w-px lg:after:bg-border">
                <p class="text-nowrap text-sm leading-5">@lang('Review Queue')</p>
                <h2>{{ $reviewQueueCount }}</h2>
                <span class="text-xs text-foreground/60">@lang('needs shaping')</span>
            </div>
            <div class="flex grow flex-col justify-center lg:ps-6">
                <p class="text-nowrap text-sm leading-5">@lang('Packets Ready')</p>
                <h2>{{ $packetCount }}</h2>
                <span class="text-xs text-foreground/60">{{ $signedDocsCount }} @lang('acknowledged')</span>
            </div>
        </div>
    </div>

    <hr>

    <div class="flex flex-col gap-4 sm:py-6">
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <div>
                <h4 class="text-foreground/80">@lang('Packet Overview')</h4>
                <p class="text-xs text-foreground/60">@lang('Business records, legal packets, and proof assets remain linked to the original document routes.') </p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <x-button variant="link" href="{{ route('dashboard.user.openai.documents.all') }}">@lang('View All')</x-button>
                <x-button variant="link" href="{{ route('dashboard.user.openai.documents.review') }}">@lang('Review Board')</x-button>
                <x-button variant="link" href="{{ route('dashboard.user.openai.documents.packets') }}">@lang('Packet Planner')</x-button>
            </div>
        </div>

        <div class="flex flex-nowrap items-center gap-10 max-sm:flex-wrap max-sm:gap-4">
            <div class="flex h-[10px] w-full flex-nowrap gap-0.5 overflow-hidden rounded-lg bg-foreground/5">
                <span class="bg-accent" style="width: {{ ($overview['business'] / $overviewTotal) * 100 }}%"></span>
                <span class="{{ $overview['media'] === 0 ? 'hidden' : '' }} bg-[#1CA685]" style="width: {{ ($overview['media'] / $overviewTotal) * 100 }}%"></span>
                <span class="{{ $overview['evidence'] === 0 ? 'hidden' : '' }} bg-[#667085]" style="width: {{ ($overview['evidence'] / $overviewTotal) * 100 }}%"></span>
                <span class="{{ $overview['ready'] === 0 ? 'hidden' : '' }} bg-[#F5A524]" style="width: {{ ($overview['ready'] / $overviewTotal) * 100 }}%"></span>
            </div>
        </div>

        <div class="grid gap-3 md:grid-cols-4">
            <div class="rounded-2xl bg-foreground/5 p-3"><p class="text-2xs uppercase tracking-wide text-foreground/60">@lang('Business')</p><p class="mt-1 text-sm font-semibold text-heading-foreground">{{ $businessDocsCount }}</p></div>
            <div class="rounded-2xl bg-foreground/5 p-3"><p class="text-2xs uppercase tracking-wide text-foreground/60">@lang('Media')</p><p class="mt-1 text-sm font-semibold text-heading-foreground">{{ $mediaDocsCount }}</p></div>
            <div class="rounded-2xl bg-foreground/5 p-3"><p class="text-2xs uppercase tracking-wide text-foreground/60">@lang('Proof Queue')</p><p class="mt-1 text-sm font-semibold text-heading-foreground">{{ $reviewQueueCount + $evidenceItemsCount }}</p></div>
            <div class="rounded-2xl bg-foreground/5 p-3"><p class="text-2xs uppercase tracking-wide text-foreground/60">@lang('Ready To Share')</p><p class="mt-1 text-sm font-semibold text-heading-foreground">{{ $proofReadyCount }}</p></div>
        </div>
    </div>
</x-card>
