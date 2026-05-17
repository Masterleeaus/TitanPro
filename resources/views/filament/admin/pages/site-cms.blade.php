<x-filament-panels::page>
    <div class="space-y-6">
        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-primary-600">Integrated CMS</p>
                    <h2 class="mt-1 text-2xl font-bold tracking-tight">Site CMS + Tomato Blog</h2>
                    <p class="mt-2 max-w-3xl text-sm text-gray-600 dark:text-gray-400">
                        The custom site CMS manages pages, layouts, menus, media, and SEO. Tomato CMS is integrated as the blog publishing layer for articles and updates.
                    </p>
                </div>
                <a href="{{ url('/blog') }}" target="_blank" class="inline-flex items-center justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-500">
                    View public blog
                </a>
            </div>
        </section>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm text-gray-500">Blog engine</p>
                <p class="mt-2 text-2xl font-bold">{{ $this->blogStats['available'] ? 'Online' : 'Pending' }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $this->blogStats['table'] ? 'Table: '.$this->blogStats['table'] : 'Run Tomato CMS migrations' }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm text-gray-500">Published posts</p>
                <p class="mt-2 text-2xl font-bold">{{ $this->blogStats['published'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Visible on /blog</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <p class="text-sm text-gray-500">Draft / hidden</p>
                <p class="mt-2 text-2xl font-bold">{{ $this->blogStats['draft'] }}</p>
                <p class="mt-1 text-xs text-gray-500">Needs editorial review</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($this->cmsCards as $card)
                <a href="{{ $card['url'] }}" class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-primary-400 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-gray-950 dark:text-white">{{ $card['label'] }}</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $card['description'] }}</p>
                        </div>
                        <span class="rounded-full bg-primary-50 px-2.5 py-1 text-xs font-semibold text-primary-700 dark:bg-primary-500/10 dark:text-primary-300">{{ $card['status'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
