{{--
  Titan OS App Switcher

  A modal overlay listing all registered apps.  It is hidden by default
  and toggled via a custom event fired from the header button.  The list of
  applications is pulled from the AppRegistry.  States like locked,
  upgrade_required or coming_soon should be represented visually in future
  passes.
--}}
@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Request;
    // Retrieve all apps and derive unique categories for filtering.  A category
    // called "core" is always present; if no category is defined on an app
    // default it to 'other'.
    $apps = \App\Support\TitanOS\AppRegistry::all();
    $categories = collect($apps)
        ->filter(fn($a) => ! $a->hidden)
        ->map(fn($a) => $a->category ?: 'other')
        ->unique()
        ->values();
    // Determine the active app based on the current URL path.  This allows
    // highlighting the current panel in the switcher.
    $currentPath = Request::path();
@endphp
<div
    class="titan-os-app-switcher fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden"
    data-titan-os-launcher>
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-4 max-w-4xl w-full overflow-y-auto" data-titan-os-launcher-panel>
        <h2 class="text-lg font-semibold mb-4">Applications</h2>
        <!-- Category tabs -->
        <div class="flex flex-wrap gap-2 mb-4" data-category-buttons>
            <button
                type="button"
                data-category-button
                data-category="all"
                class="px-3 py-1 rounded text-sm bg-gray-100 dark:bg-gray-700"
            >All</button>
            @foreach($categories as $cat)
                <button
                    type="button"
                    data-category-button
                    data-category="{{ $cat }}"
                    class="px-3 py-1 rounded text-sm capitalize bg-gray-100 dark:bg-gray-700"
                >{{ str_replace('_', ' ', $cat) }}</button>
            @endforeach
        </div>
        <!-- Search input -->
        <input
            type="text"
            data-launcher-search
            placeholder="Search apps..."
            class="mb-4 w-full p-2 border rounded dark:bg-gray-800 dark:border-gray-700"
        />
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach ($apps as $app)
                @continue($app->hidden)
                @php
                    $isDisabled = (! $app->enabled) || $app->disabled;
                    $isComingSoon = $app->coming_soon;
                    $isLocked = $app->locked;
                    $isUpgrade = $app->upgrade_required;
                    // Determine CSS classes based on state. Disabled apps are dimmed
                    // and non-interactive. Coming-soon / locked / upgrade cards remain
                    // clickable so users can reach the placeholder workspace screens.
                    $cardClasses = 'titan-os-app-card p-4 border flex flex-col items-center justify-center rounded ';
                    if ($isDisabled) {
                        $cardClasses .= 'opacity-50 cursor-not-allowed pointer-events-none ';
                    } elseif ($isComingSoon || $isLocked || $isUpgrade) {
                        $cardClasses .= 'opacity-75 hover:bg-gray-100 dark:hover:bg-gray-800 ';
                    } else {
                        $cardClasses .= 'hover:bg-gray-100 dark:hover:bg-gray-800 ';
                    }
                    // Active highlight if current path contains the app key or panel name
                    $isActive = Str::contains($currentPath, $app->key) || Str::contains($currentPath, $app->panel);
                    if ($isActive) {
                        $cardClasses .= 'ring-2 ring-blue-500 ';
                    }
                    $filterString = Str::lower($app->name.' '.$app->label);
                    $cat = $app->category ?: 'other';
                    // Compute state for data attributes: reflects locked, coming_soon,
                    // upgrade_required, disabled or enabled
                    $state = $isLocked ? 'locked' : ($isComingSoon ? 'coming_soon' : ($isUpgrade ? 'upgrade_required' : ($isDisabled ? 'disabled' : 'enabled')));
                @endphp
                @php
                    // Determine the link destination.  Enabled apps link to their panel
                    // route or custom route if defined.  Other states link to the
                    // workspace placeholder.  Prepend a slash to the panel name when
                    // using the panel route.  Use url() helper to generate the
                    // absolute URL.
                    $href = '';
                    if ($state === 'enabled') {
                        if (! empty($app->route)) {
                            $href = url($app->route);
                        } elseif (! empty($app->panel)) {
                            $href = url('/' . $app->panel);
                        } else {
                            $href = url('/os/workspace/' . $app->key);
                        }
                    } else {
                        $href = url('/os/workspace/' . $app->key);
                    }
                @endphp
                <a
                    href="{{ $href }}"
                    class="{{ $cardClasses }}"
                    data-app-card
                    data-name="{{ $filterString }}"
                    data-category="{{ $cat }}"
                    data-state="{{ $state }}"
                    data-panel="{{ $app->panel }}"
                    data-key="{{ $app->key }}"
                >
                    <div class="text-2xl mb-2">{{ $app->icon }}</div>
                    <div class="font-medium">{{ $app->name }}</div>
                    <div class="text-sm text-gray-500">{{ $app->label }}</div>
                    @if($isLocked)
                        <span class="text-xs text-gray-500 mt-1">Locked</span>
                    @elseif($isComingSoon)
                        <span class="text-xs text-gray-500 mt-1">Coming Soon</span>
                    @elseif($isUpgrade)
                        <span class="text-xs text-gray-500 mt-1">Upgrade Required</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>