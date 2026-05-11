@php
    $brandboardStats = [
        'status' => __('MVP Ready'),
        'assets' => 24,
        'kits' => 5,
        'direction' => __('Clean Logo'),
        'updated' => __('Today'),
    ];

    $brandboardDirections = [
        ['label' => __('Clean Logo'), 'icon' => 'sparkles'],
        ['label' => __('Trust Badge'), 'icon' => 'shield-check'],
        ['label' => __('Service Badge'), 'icon' => 'rosette-discount-check'],
        ['label' => __('Arrival Card'), 'icon' => 'id-badge-2'],
        ['label' => __('Brand Icon'), 'icon' => 'shapes'],
        ['label' => __('Vehicle Mark'), 'icon' => 'truck'],
    ];

    $brandboardKits = [
        [
            'title' => __('Primary Logo Set'),
            'desc' => __('Horizontal, stacked, icon and monochrome logo variants.'),
            'route' => route('dashboard.user.brandboard.kits'),
            'thumb' => 'BB',
        ],
        [
            'title' => __('Trust Badge Set'),
            'desc' => __('Insurance, guarantee, vetted staff and quality mark badges.'),
            'route' => route('dashboard.user.brandboard.kits'),
            'thumb' => 'TB',
        ],
        [
            'title' => __('Service Badge Set'),
            'desc' => __('Cleaning, garden, handyman and home-service category badges.'),
            'route' => route('dashboard.user.brandboard.kits'),
            'thumb' => 'SB',
        ],
        [
            'title' => __('Arrival Card'),
            'desc' => __('Friendly customer-facing staff arrival and trust card.'),
            'route' => route('dashboard.user.brandboard.kits'),
            'thumb' => 'AC',
        ],
    ];

    $brandboardAssets = [
        ['name' => __('Titan Clean Primary Logo'), 'type' => __('Logo')],
        ['name' => __('Police Checked Badge'), 'type' => __('Badge')],
        ['name' => __('Cleaner Arrival Card'), 'type' => __('Card')],
        ['name' => __('App Icon Square'), 'type' => __('Icon')],
    ];

    $brandboardChecklist = [
        __('Brand profile saved'),
        __('Palette approved'),
        __('Logo generated'),
        __('Kits created'),
        __('Library reviewed'),
    ];
@endphp

<x-card class="w-full" id="brandboard-hub" size="lg" x-data="{ tab: 'overview' }">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-accent/[8%] px-3 py-1 text-xs font-semibold text-accent">
                <x-tabler-palette class="size-4" />
                <span>{{ __('Campaign Creative Studio') }}</span>
            </div>
            <h3 class="mb-1 text-[17px] leading-6">{{ __('Campaign Creative Studio') }}</h3>
            <p class="max-w-2xl text-sm text-foreground/70">
                {{ __('Create campaign graphics, ads, email visuals and brand assets from one customer dashboard card.') }}
            </p>
        </div>

        <x-button href="{{ route('dashboard.user.brandboard.index') }}">
            <x-tabler-arrow-up-right class="size-4" />
            <span>{{ __('Open Full Campaign Creative Studio') }}</span>
        </x-button>
    </div>

    <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-card border border-border p-4">
            <p class="mb-1 text-xs uppercase tracking-wide text-foreground/50">{{ __('Status') }}</p>
            <div class="flex items-center gap-2">
                <span class="inline-block size-2 rounded-full bg-green-500"></span>
                <strong>{{ $brandboardStats['status'] }}</strong>
            </div>
        </div>
        <div class="rounded-card border border-border p-4">
            <p class="mb-1 text-xs uppercase tracking-wide text-foreground/50">{{ __('Assets') }}</p>
            <strong class="text-2xl">{{ $brandboardStats['assets'] }}</strong>
        </div>
        <div class="rounded-card border border-border p-4">
            <p class="mb-1 text-xs uppercase tracking-wide text-foreground/50">{{ __('Active Kit') }}</p>
            <strong>{{ $brandboardStats['kits'] }} {{ __('Ready') }}</strong>
        </div>
        <div class="rounded-card border border-border p-4">
            <p class="mb-1 text-xs uppercase tracking-wide text-foreground/50">{{ __('Updated') }}</p>
            <strong>{{ $brandboardStats['updated'] }}</strong>
        </div>
    </div>

    <div class="mt-6 inline-flex flex-wrap gap-2 rounded-full border border-border p-1">
        <button class="rounded-full px-4 py-2 text-sm transition"
            :class="tab === 'overview' ? 'bg-foreground text-background' : 'text-foreground/70'"
            @click="tab = 'overview'">{{ __('Overview') }}</button>
        <button class="rounded-full px-4 py-2 text-sm transition"
            :class="tab === 'kits' ? 'bg-foreground text-background' : 'text-foreground/70'"
            @click="tab = 'kits'">{{ __('Kits') }}</button>
        <button class="rounded-full px-4 py-2 text-sm transition"
            :class="tab === 'library' ? 'bg-foreground text-background' : 'text-foreground/70'"
            @click="tab = 'library'">{{ __('Library') }}</button>
        <button class="rounded-full px-4 py-2 text-sm transition"
            :class="tab === 'canvas' ? 'bg-foreground text-background' : 'text-foreground/70'"
            @click="tab = 'canvas'">{{ __('Canvas') }}</button>
    </div>

    <div class="mt-6" x-show="tab === 'overview'">
        <div class="grid gap-4 lg:grid-cols-[1.4fr,.8fr]">
            <div class="rounded-card border border-border p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="m-0 text-base">{{ __('Brand Snapshot') }}</h4>
                    <span class="rounded-full bg-accent/[8%] px-3 py-1 text-xs font-semibold text-accent">{{ __('Clean / Trustworthy') }}</span>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-card bg-foreground/5 p-4">
                        <p class="mb-2 text-xs uppercase tracking-wide text-foreground/50">{{ __('Palette') }}</p>
                        <div class="flex items-center gap-3">
                            <span class="size-8 rounded-full border border-border" style="background:#2563eb"></span>
                            <span class="size-8 rounded-full border border-border" style="background:#14b8a6"></span>
                            <span class="size-8 rounded-full border border-border" style="background:#0f172a"></span>
                        </div>
                    </div>
                    <div class="rounded-card bg-foreground/5 p-4">
                        <p class="mb-2 text-xs uppercase tracking-wide text-foreground/50">{{ __('Active Direction') }}</p>
                        <strong>{{ $brandboardStats['direction'] }}</strong>
                    </div>
                    <div class="rounded-card bg-foreground/5 p-4 md:col-span-2">
                        <p class="mb-3 text-xs uppercase tracking-wide text-foreground/50">{{ __('Progress') }}</p>
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach ($brandboardChecklist as $item)
                                <div class="inline-flex items-center gap-2 text-sm">
                                    <x-tabler-circle-check class="size-4 text-green-500" />
                                    <span>{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-2">
                    <x-button href="{{ route('dashboard.user.brandboard.profile') }}">
                        <x-tabler-id-badge-2 class="size-4" />
                        <span>{{ __('Edit Profile') }}</span>
                    </x-button>
                    <x-button variant="link" href="{{ route('dashboard.user.brandboard.kits') }}">
                        <span>{{ __('Open Kits') }}</span>
                        <x-tabler-chevron-right class="size-4 rtl:rotate-180" />
                    </x-button>
                    <x-button variant="link" href="{{ route('dashboard.user.brandboard.library') }}">
                        <span>{{ __('View Library') }}</span>
                        <x-tabler-chevron-right class="size-4 rtl:rotate-180" />
                    </x-button>
                </div>
            </div>

            <div class="rounded-card border border-border p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="m-0 text-base">{{ __('Brand Directions') }}</h4>
                    <x-button variant="link" href="{{ route('dashboard.user.brandboard.canvas') }}">
                        <span>{{ __('Launch') }}</span>
                        <x-tabler-arrow-up-right class="size-4" />
                    </x-button>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @foreach ($brandboardDirections as $direction)
                        <button class="rounded-card border border-border p-3 text-start transition hover:border-accent hover:bg-accent/[4%]">
                            <div class="mb-3 inline-flex size-10 items-center justify-center rounded-full bg-accent/[8%] text-accent">
                                <x-dynamic-component :component="'tabler-' . $direction['icon']" class="size-5" />
                            </div>
                            <div class="text-sm font-medium leading-5">{{ $direction['label'] }}</div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6" x-show="tab === 'kits'" x-cloak>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($brandboardKits as $kit)
                <div class="overflow-hidden rounded-card border border-border">
                    <div class="flex h-28 items-center justify-center bg-gradient-to-br from-accent/[10%] to-foreground/[4%]">
                        <div class="flex size-14 items-center justify-center rounded-2xl border border-border bg-background text-sm font-semibold">
                            {{ $kit['thumb'] }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h5 class="mb-2 text-base">{{ $kit['title'] }}</h5>
                        <p class="mb-4 text-sm text-foreground/70">{{ $kit['desc'] }}</p>
                        <x-button class="w-full justify-center" href="{{ $kit['route'] }}">
                            <x-tabler-wand class="size-4" />
                            <span>{{ __('Create') }}</span>
                        </x-button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6" x-show="tab === 'library'" x-cloak>
        <div class="mb-4 inline-flex flex-wrap gap-2">
            <span class="rounded-full border border-border px-3 py-1 text-xs font-medium">{{ __('All') }}</span>
            <span class="rounded-full border border-border px-3 py-1 text-xs font-medium">{{ __('Logos') }}</span>
            <span class="rounded-full border border-border px-3 py-1 text-xs font-medium">{{ __('Badges') }}</span>
            <span class="rounded-full border border-border px-3 py-1 text-xs font-medium">{{ __('Cards') }}</span>
            <span class="rounded-full border border-border px-3 py-1 text-xs font-medium">{{ __('Icons') }}</span>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($brandboardAssets as $asset)
                <div class="rounded-card border border-border p-4">
                    <div class="mb-4 h-24 rounded-card bg-foreground/5"></div>
                    <h5 class="mb-1 text-base">{{ $asset['name'] }}</h5>
                    <p class="text-sm text-foreground/60">{{ $asset['type'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6" x-show="tab === 'canvas'" x-cloak>
        <div class="grid gap-4 lg:grid-cols-[1.2fr,.8fr]">
            <div class="rounded-card border border-border p-4">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="m-0 text-base">{{ __('Canvas Preview') }}</h4>
                    <span class="text-xs text-foreground/60">{{ __('Last edited 12 min ago') }}</span>
                </div>
                <div class="flex min-h-[240px] items-center justify-center rounded-card bg-foreground/5">
                    <div class="text-center">
                        <div class="mx-auto mb-3 flex size-16 items-center justify-center rounded-full bg-accent/[10%] text-accent">
                            <x-tabler-pencil class="size-7" />
                        </div>
                        <p class="mb-1 font-medium">{{ __('Continue your latest design') }}</p>
                        <p class="text-sm text-foreground/60">{{ __('Jump back into the Brand Canvas and keep refining your active asset.') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-card border border-border p-4">
                <h4 class="mb-4 text-base">{{ __('Quick Actions') }}</h4>
                <div class="flex flex-col gap-2">
                    <x-button href="{{ route('dashboard.user.brandboard.canvas') }}">
                        <x-tabler-pencil class="size-4" />
                        <span>{{ __('Open Brand Canvas') }}</span>
                    </x-button>
                    <x-button variant="link" href="{{ route('dashboard.user.brandboard.kits') }}">
                        <span>{{ __('Generate Another Kit') }}</span>
                        <x-tabler-chevron-right class="size-4 rtl:rotate-180" />
                    </x-button>
                    <x-button variant="link" href="{{ route('dashboard.user.brandboard.library') }}">
                        <span>{{ __('Review Saved Assets') }}</span>
                        <x-tabler-chevron-right class="size-4 rtl:rotate-180" />
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</x-card>
