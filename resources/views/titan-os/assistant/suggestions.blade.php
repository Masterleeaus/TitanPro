{{--
  Titan Zero Suggestions

  Renders a horizontal list of suggestion buttons.  Each button has a
  `data-suggestion` attribute which is consumed by titan-zero-assistant.js.
--}}
<div class="titan-zero-suggestions flex flex-wrap gap-1">
    @foreach ($suggestions ?? [] as $suggestion)
        <button type="button" data-suggestion="{{ $suggestion }}" class="text-xs px-2 py-1 bg-gray-200 rounded">
            {{ $suggestion }}
        </button>
    @endforeach
</div>