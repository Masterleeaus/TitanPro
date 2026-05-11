@forelse($items ?? [] as $item)
    <label class="flex items-start gap-3 rounded-xl border border-black/5 dark:border-white/10 bg-black/[0.02] dark:bg-white/[0.02] p-3">
        <input class="mt-1" type="checkbox" name="training_data[]" value="{{ $item->id }}" checked>
        <div class="min-w-0 flex-1">
            <div class="text-sm font-semibold">{{ $item->title ?? __('Text Item') }}</div>
            <div class="text-xs opacity-70">{{ $item->content ?? $item->text ?? '' }}</div>
        </div>
    </label>
@empty
    <div class="rounded-xl border border-dashed border-black/10 dark:border-white/10 p-4 text-xs opacity-70">{{ __('No text items yet.') }}</div>
@endforelse
