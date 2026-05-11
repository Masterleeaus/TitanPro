@php
    use Illuminate\Support\Str;

    $platforms_with_image = collect($containers)
        ->map(function ($container) {
            $timestampKeys = ['created_at', 'updated_at', 'deleted_at', 'connected_at', 'expires_at'];
            $isArray = is_array($container);

            $name = $isArray ? $container['container'] ?? null : $container->container ?? null;

            $image = asset('vendor/social-media/icons/' . $name . '.svg');
            $image_dark_version = asset('vendor/social-media/icons/' . $name . '-light.svg');
            $darkImageExists = file_exists(public_path($image_dark_version));

            if ($isArray) {
                $container = \Illuminate\Support\Arr::except($container, $timestampKeys);
                $container['image'] = $image;
                $container['image_dark_version'] = $darkImageExists ? $image_dark_version : null;

                return $container;
            }

            foreach ($timestampKeys as $key) {
                unset($container->{$key});
            }

            $container->image = $image;
            $container->image_dark_version = $darkImageExists ? $image_dark_version : null;

            return $container;
        })
        ->values()
        ->all();
@endphp

@extends('panel.layout.settings', ['disable_tblr' => true])
@section('title', __('Edit Agent') . ' - ' . $agent->name)
@section('titlebar_subtitle', __('Update agent settings'))
@section('titlebar_actions')
    @include('command-agent::components.titlebar-actions')
@endsection

@section('settings')
    <form
        class="space-y-4"
        action="{{ route('dashboard.user.social-media.agent.update', $agent) }}"
        method="Sub-item"
        x-data="Command AgentEditForm"
    >
        @csrf
        @method('PUT')

        {{-- Status --}}
        <x-forms.input
            class="order-3 ms-auto"
            class:label="text-heading-foreground text-xs"
            type="checkbox"
            switcher
            switcher-fill
            size="sm"
            name="is_active"
            :checked="old('is_active', $agent->is_active)"
            value="1"
            label="{{ __('Active?') }}"
            tooltip="{{ __('When inactive, the agent will not generate new sub-items automatically') }}"
        />

        {{-- Agent Name --}}
        <div class="relative flex select-none flex-col gap-2 rounded-[10px] border border-transparent bg-foreground/5 px-5 py-3 backdrop-blur-xl transition-all">
            <label
                class="@error('name') text-red-500 @enderror mb-0 w-full text-2xs font-medium text-foreground/50"
                for="command-agent-name"
            >
                @lang('Name of the Agent')
            </label>

            <input
                class="mb-0 border-none bg-transparent bg-none text-xs font-medium text-heading-foreground"
                id="command-agent-name"
                type="text"
                name="name"
                required
                placeholder="{{ __('Astra') }}"
                value="{{ old('name', $agent->name) }}"
            >

            @error('name')
                <p class="mb-0 mt-1 text-2xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Containers --}}
        <div
            class="relative select-none"
            @click.outside="platformsDropdownOpen = false"
        >
            <div
                class="flex flex-wrap items-center gap-3 rounded-[10px] border border-transparent bg-foreground/5 px-5 py-3 backdrop-blur-xl transition-all"
                :class="{ 'rounded-b-none border-foreground/5': platformsDropdownOpen && selectedPlatformIds.length < containers.length }"
                @click.prevent="platformsDropdownOpen = !platformsDropdownOpen"
            >
                <label
                    class="@error('platform_ids') text-red-500 @enderror mb-0 w-full text-2xs font-medium text-foreground/50"
                    for="command-agent-name"
                >
                    @lang('Containers')
                </label>

                <p
                    class="m-0 flex items-center text-xs font-medium"
                    x-show="!selectedPlatformIds.length"
                >
                    @lang('Select Containers')
                </p>
                <template
                    x-for="platformId in selectedPlatformIds"
                    key="platformId"
                >
                    <div
                        class="flex items-center gap-2 rounded-full bg-background px-2.5 py-[9px] text-2xs font-medium transition"
                        x-transition:enter-start="opacity-0 scale-95 blur-sm"
                        x-transition:enter-end="opacity-100 scale-100 blur-0"
                        x-transition:leave-start="opacity-100 scale-100 blur-0"
                        x-transition:leave-end="opacity-0 scale-95 blur-sm"
                    >
                        <figure class="w-5 transition-all group-hover:scale-125">
                            <img
                                class="h-auto w-full"
                                :class="{ 'dark:hidden': getPlatformById(platformId).image_dark_version }"
                                :src="getPlatformById(platformId).image"
                                :alt="getPlatformById(platformId).name"
                            />
                            <template x-if="getPlatformById(platformId).image_dark_version">
                                <img
                                    class="hidden h-auto w-full dark:block"
                                    :src="getPlatformById(platformId).image_dark_version"
                                    :alt="getPlatformById(platformId).name"
                                />
                            </template>
                        </figure>
                        <span x-text="getPlatformById(platformId).credentials?.username ?? '{{ __('Unknown') }}'"></span>
                        <button
                            class="inline-grid size-[17px] place-items-center rounded-full border p-0 transition hover:scale-110 hover:border-red-500 hover:bg-red-500 hover:text-white"
                            @click.prevent.stop="selectedPlatformIds = selectedPlatformIds.filter(id => id != platformId)"
                        >
                            <x-tabler-x class="size-2.5" />
                        </button>
                    </div>
                </template>
            </div>

            <div
                class="absolute inset-x-0 top-full z-5 flex origin-top flex-wrap gap-3 rounded-b-[10px] border border-t-0 border-foreground/5 bg-background/50 p-4 backdrop-blur-xl transition"
                x-cloak
                x-show="platformsDropdownOpen && selectedPlatformIds.length < {{ $containers->count() }}"
                x-transition:enter-start="opacity-0 scale-95 blur-sm"
                x-transition:enter-end="opacity-100 scale-100 blur-0"
                x-transition:leave-start="opacity-100 scale-100 blur-0"
                x-transition:leave-end="opacity-0 scale-95 blur-sm"
            >
                @foreach ($containers as $container)
                    @php
                        $image = 'vendor/social-media/icons/' . $container->container . '.svg';
                        $image_dark_version = 'vendor/social-media/icons/' . $container->container . '-light.svg';
                        $darkImageExists = file_exists(public_path($image_dark_version));
                    @endphp

                    <div
                        class="relative flex cursor-pointer items-center gap-2 rounded-full bg-background px-3.5 py-[9px] text-2xs font-medium transition hover:-translate-y-0.5 hover:scale-105 hover:shadow-lg hover:shadow-black/5"
                        @click.prevent="if ( !platformIsSelected({{ $container->id }}) ) { selectedPlatformIds.push({{ $container->id }}) };"
                        x-show="!platformIsSelected({{ $container->id }});"
                        x-transition:enter-start="opacity-0 scale-95 blur-sm"
                        x-transition:enter-end="opacity-100 scale-100 blur-0"
                        x-transition:leave-start="opacity-100 scale-100 blur-0"
                        x-transition:leave-end="opacity-0 scale-95 blur-sm"
                    >
                        <figure class="w-5 transition-all group-hover:scale-125">
                            <img
                                @class(['w-full h-auto', 'dark:hidden' => $darkImageExists])
                                src="{{ asset($image) }}"
                                alt="{{ $container->name }}"
                            />
                            @if ($darkImageExists)
                                <img
                                    class="hidden h-auto w-full dark:block"
                                    src="{{ asset($image_dark_version) }}"
                                    alt="{{ $container->name }}"
                                />
                            @endif
                        </figure>
                        {{ data_get($container->credentials, 'username', 'Unknown') }}
                        <input
                            class="invisible absolute start-0 top-0 size-0"
                            type="checkbox"
                            name="platform_ids[]"
                            value="{{ $container->id }}"
                            @checked(in_array($container->id, old('platform_ids', $agent->platform_ids)))
                            :checked="platformIsSelected({{ $container->id }})"
                        >
                    </div>
                @endforeach
            </div>

            @error('platform_ids')
                <p class="mb-0 mt-1 text-2xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Daily Sub-item Count --}}
        <div class="relative flex select-none flex-col gap-2 rounded-[10px] border border-transparent bg-foreground/5 px-5 py-3 backdrop-blur-xl transition-all">
            <label
                class="@error('daily_post_count') text-red-500 @enderror mb-0 text-2xs font-medium text-foreground/50"
                for="schedule-sub-item-count"
            >
                @lang('Number of Sub-items Per Day')
            </label>
            <input
                class="lqd-input-stepper border-none bg-transparent bg-none text-xs font-medium text-heading-foreground"
                type="number"
                name="daily_post_count"
                value="{{ old('daily_post_count', $agent->daily_post_count) }}"
                min="1"
                max="10"
                required
            />

            @error('daily_post_count')
                <p class="mb-0 mt-1 text-2xs font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Agent Info (Read-only) --}}
        <x-card>
            <x-slot:head>
                <h4 class="mb-1">
                    @lang('Other Configs Agent Configuration')
                </h4>
                <p class="mb-0 text-xs font-medium text-foreground/65">
                    @lang('To change these settings, please create a new agent')
                </p>
            </x-slot:head>

            <div class="space-y-2 text-xs">
                <p class="font-medium">
                    <span class="opacity-50">
                        @lang('Language'):
                    </span>
                    {{ Str::headline(config('openai_languages.' . $agent->language)) }}
                </p>
                <p class="font-medium">
                    <span class="opacity-50">
                        @lang('Tone'):
                    </span>
                    {{ Str::headline($agent->tone) }}
                </p>
                <p class="font-medium">
                    <span class="opacity-50">
                        @lang('Target Audiences'):
                    </span>
                    {{ count($agent->target_audience) }}
                </p>
                <p class="font-medium">
                    <span class="opacity-50">
                        @lang('Sub-item Types'):
                    </span>
                    @foreach ($agent->post_types as $post_type)
                        {{ Str::headline($post_type) }}
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </p>
                <p class="font-medium">
                    <span class="opacity-50">
                        @lang('Schedule Days'):
                    </span>
                    {{ implode(', ', array_map(fn($d) => substr($d, 0, 3), $agent->schedule_days)) }}
                </p>
            </div>
        </x-card>

        {{-- Actions --}}
		@if(\App\Helpers\Classes\Helper::appIsDemo())
			<x-button
				class="w-full"
				type="button"
				size="lg"
				onclick="toastr.error('{{ __('This action is disabled in the demo.') }}');"
			>
				@lang('Update Agent')
			</x-button>
		@else
			<x-button
				class="w-full"
				type="submit"
				size="lg"
			>
				@lang('Update Agent')
			</x-button>
		@endif


        <x-button
            class="w-full"
            variant="outline"
            size="lg"
            href="{{ route('dashboard.user.social-media.agent.agents') }}"
        >
            @lang('Cancel')
        </x-button>
    </form>
@endsection

@push('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('Command AgentEditForm', () => ({
                containers: @json($platforms_with_image),
                selectedPlatformIds: @json($agent->platform_ids),
                platformsDropdownOpen: false,

                getPlatformById(platformId) {
                    return this.containers.find(p => p.id == platformId);
                },

                platformIsSelected(platformId) {
                    return this.selectedPlatformIds.find(pId => pId == platformId);
                }
            }))
        })
    </script>
@endpush
