{{--
  Missing Module Placeholder

  Displayed when no module provider exists for the given app key.
--}}
<div class="p-4 text-center text-gray-600">
    <p class="text-lg font-semibold mb-2">Workspace not available</p>
    <p class="text-sm">The {{ $app?->name ?? ucfirst($appKey) }} module is not installed yet. Contact your administrator or check back later.</p>
</div>