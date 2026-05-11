@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('MarketingBot'))
@section('subtitle', __('MarketingBot'))
@section('titlebar_subtitle', __('Smart automations across WhatsApp, Telegram and more.'))
@section('titlebar_actions')
    <x-dropdown.dropdown
        anchor="end"
        offsetY="13px"
    >
        <x-slot:trigger
            variant="none"
        >
            @lang('View Campaigns')
        </x-slot:trigger>

        <x-slot:dropdown
            class="min-w-52 overflow-hidden p-2"
        >
            <x-button
                @class([
                    'w-full justify-start rounded-md px-3 py-2 text-start text-2xs hover:bg-heading-foreground/5 hover:no-underline',
                ])
                variant="link"
                href="{{ route('dashboard.user.marketing-bot.whatsapp-campaign.index') }}"
            >
                <img
                    class="h-auto w-6"
                    src="{{ asset('vendor/marketing-bot/images/whatsapp.png') }}"
                    alt="{{ 'whatsapp' }}"
                />
                WhatsApp
            </x-button>
            <x-button
                @class([
                    'w-full justify-start rounded-md px-3 py-2 text-start text-2xs hover:bg-heading-foreground/5 hover:no-underline',
                ])
                variant="link"
                href="{{ route('dashboard.user.marketing-bot.telegram-campaign.index') }}"
            >
                <img
                    class="h-auto w-6"
                    src="{{ asset('vendor/marketing-bot/images/telegram.png') }}"
                    alt="{{ 'telegram' }}"
                />
                Telegram
            </x-button>

        </x-slot:dropdown>
    </x-dropdown.dropdown>
    <x-dropdown.dropdown
        anchor="end"
        offsetY="13px"
    >
        <x-slot:trigger
            variant="primary"
        >
            <x-tabler-plus class="size-4" />
            @lang('Create New Campaign')
        </x-slot:trigger>

        <x-slot:dropdown
            class="min-w-52 overflow-hidden p-2"
        >
            <x-button
                @class([
                    'w-full justify-start rounded-md px-3 py-2 text-start text-2xs hover:bg-heading-foreground/5 hover:no-underline',
                ])
                variant="link"
                href="{{ route('dashboard.user.marketing-bot.whatsapp-campaign.create') }}"
            >
                <img
                    class="h-auto w-6"
                    src="{{ asset('vendor/marketing-bot/images/whatsapp.png') }}"
                    alt="{{ 'whatsapp' }}"
                />
                WhatsApp
            </x-button>
            <x-button
                @class([
                    'w-full justify-start rounded-md px-3 py-2 text-start text-2xs hover:bg-heading-foreground/5 hover:no-underline',
                ])
                variant="link"
                href="{{ route('dashboard.user.marketing-bot.telegram-campaign.create') }}"
            >
                <img
                    class="h-auto w-6"
                    src="{{ asset('vendor/marketing-bot/images/telegram.png') }}"
                    alt="{{ 'telegram' }}"
                />
                Telegram
            </x-button>

        </x-slot:dropdown>
    </x-dropdown.dropdown>
@endsection

@section('content')
    <div class="py-10">
        <div class="space-y-12">
            @include('marketing-bot::dashboard.components.banner')
        </div>

        @include('marketing-bot::dashboard.components.messaging-overview-card', ['messagingStats' => $messagingStats])

        <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
            @include('marketing-bot::dashboard.components.quick-actions-card', [
                'quickPresets' => $quickPresets,
                'quickConversations' => $quickConversations,
            ])
            @include('marketing-bot::dashboard.components.campaign-card', [
                'campaignFocus' => $campaignFocus,
                'campaignMetrics' => $campaignMetrics,
            ])
        </div>

        <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-2">
            @include('marketing-bot::dashboard.components.campaign-chart', ['data' => $chartCampaigns])
            @include('marketing-bot::dashboard.components.new-contact-chart', ['data' => $chartNewContacts])
        </div>

        @include('marketing-bot::dashboard.components.overview-grid', ['items' => $totals])
        <div id="campaigns-table">
            @include('marketing-bot::dashboard.components.list')
        </div>

        {{-- blade-formatter-disable --}}
        <svg class="absolute h-0 w-0" width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" > <defs> <linearGradient id="social-posts-overview-gradient" x1="9.16667" y1="15.1507" x2="32.6556" y2="31.9835" gradientUnits="userSpaceOnUse" > <stop stop-color="hsl(var(--gradient-from))" /> <stop offset="0.502" stop-color="hsl(var(--gradient-via))" /> <stop offset="1" stop-color="hsl(var(--gradient-to))" /> </linearGradient> </defs> </svg>
		{{-- blade-formatter-enable --}}
    </div>
@endsection

@push('script')
    <script>

        const titantalkPresets = @json($quickPresets);
        let titantalkActivePreset = null;

        const renderTitanTalkPreview = () => {
            const preview = document.getElementById('titantalk-quick-preview');
            const selected = document.getElementById('titantalk-quick-selected');
            const inputsWrap = document.getElementById('titantalk-quick-inputs');
            if (!preview || !selected || !inputsWrap) return;

            if (!titantalkActivePreset) {
                preview.value = '';
                selected.textContent = @json(__('Choose an action to preview the message.'));
                inputsWrap.innerHTML = '';
                return;
            }

            const preset = titantalkPresets[titantalkActivePreset] || {};
            const defaults = preset.defaults || {};
            inputsWrap.innerHTML = Object.keys(defaults).map((key) => `
                <label class="block min-w-[140px] text-2xs font-medium text-foreground/80">
                    <span class="mb-1 block">${key.replaceAll('_', ' ')}</span>
                    <input class="lqd-input lqd-input-sm w-full" data-titantalk-var="${key}" value="${defaults[key] ?? ''}" />
                </label>
            `).join('');

            let message = preset.preview || '';
            const vars = {};
            inputsWrap.querySelectorAll('[data-titantalk-var]').forEach((input) => {
                vars[input.dataset.titantalkVar] = input.value;
            });
            Object.keys(vars).forEach((key) => {
                message = message.replaceAll(`{{${key}}}`, vars[key]).replaceAll(`{{ ${key} }}`, vars[key]);
            });
            preview.value = message.replace(/\s+/g, ' ').trim();
            selected.textContent = `${preset.title || titantalkActivePreset} · ${preset.channel_hint || 'TitanTalk'}`;

            inputsWrap.querySelectorAll('[data-titantalk-var]').forEach((input) => {
                input.addEventListener('input', renderTitanTalkPreview, { once: true });
            });
        };

        $('[data-quick-action]').on('click', function() {
            titantalkActivePreset = $(this).data('quick-action');
            renderTitanTalkPreview();
        });

        $(document).on('input', '[data-titantalk-var]', function() {
            renderTitanTalkPreview();
        });

        $('#titantalk-quick-send').on('click', function() {
            if (!titantalkActivePreset) {
                toastr.error('Select a quick action first.');
                return;
            }

            const conversationId = $('#titantalk-quick-conversation').val();
            if (!conversationId) {
                toastr.error('Select a conversation/contact first.');
                return;
            }

            const payload = {
                _token: '{{ csrf_token() }}',
                conversation_id: conversationId,
                preset: titantalkActivePreset,
            };

            $('[data-titantalk-var]').each(function() {
                payload[$(this).data('titantalk-var')] = $(this).val();
            });

            $.post('{{ route('dashboard.user.titan-talk.quick-messages.store') }}', payload)
                .done(function(response) {
                    toastr.success(response.message || 'Quick message sent.');
                    renderTitanTalkPreview();
                })
                .fail(function(xhr) {
                    toastr.error(xhr?.responseJSON?.message || 'Failed to send quick message.');
                });
        });


        $('[data-delete="delete"]').on('click', function(e) {
            if (!confirm('Are you sure you want to delete this campaign?')) {
                return;
            }

            let deleteLink = $(this).data('delete-link');

            $.ajax({
                url: deleteLink,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.status === 'success') {
                        toastr.success(data.message);

                        setTimeout(function() {
                            window.location.reload();
                        }, 600);

                        return;
                    }

                    if (data.message) {
                        toastr.error(data.message);
                        return;
                    }

                    toastr.error('Something went wrong!');
                },
                error: function(e) {
                    if (e?.responseJSON?.message) {
                        toastr.error(e.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });
    </script>
@endpush
