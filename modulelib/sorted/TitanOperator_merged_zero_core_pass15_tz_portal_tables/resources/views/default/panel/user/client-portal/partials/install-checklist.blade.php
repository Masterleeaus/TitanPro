<div class="rounded-2xl border border-heading-foreground/10 p-6">
    <h2 class="mb-4 text-lg font-semibold">{{ __('Install + QA Checklist') }}</h2>
    <ol class="list-decimal space-y-2 ps-5 text-sm text-heading-foreground/70">
        @foreach(($installChecklist ?? []) as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ol>
</div>
