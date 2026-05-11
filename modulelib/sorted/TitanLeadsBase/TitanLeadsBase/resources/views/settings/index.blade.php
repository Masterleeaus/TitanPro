@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Titan Leads Settings'))

@section('titlebar_actions')

@endsection

@section('content')
    <div class="grid grid-cols-1 gap-8 py-10 lg:grid-cols-2">
        <x-card>
            <x-slot name="head">
                {{ __('Titan Leads Telegram') }}
            </x-slot>
            <form
                action="{{ route('dashboard.user.titan-leads.settings.telegram') }}"
                method="post"
            >
                @method('post')
                <x-forms.input
                    id="access_token"
                    size="lg"
                    label="{{ __('Access Token') }}"
                    name="access_token"
                    required
                    value="{{ $app_is_demo ? '**********' : $telegram?->access_token }}"
                />
                @if ($app_is_demo)
                    <x-button
                        class="mt-3"
                        type="button"
                        onclick="return toastr.info('This feature is disabled in Demo version.');"
                    >
                        {{ __('Save') }}
                    </x-button>
                @else
                    <x-button
                        class="mt-3"
                        type="submit"
                    >
                        {{ __('Save') }}
                    </x-button>
                @endif
            </form>
        </x-card>
        <x-card>
            <x-slot name="head">
                {{ __('Titan Leads Whatsapp Settings') }}
            </x-slot>
            <form
                action="{{ route('dashboard.user.titan-leads.settings.whatsapp') }}"
                method="post"
            >
                <input
                    hidden
                    name="channel"
                    value="whatsapp"
                >
                <input
                    hidden
                    name="user_id"
                    value="{{ \Illuminate\Support\Facades\Auth::id() }}"
                >
                @csrf
                <div class="mb-3">
                    <x-forms.input
                        :label="__('Whatsapp sid')"
                        name="whatsapp_sid"
                        size="lg"
                        required
                        value="{{ $app_is_demo ? '**********' : $whatsapp?->whatsapp_sid }}"
                    >
                    </x-forms.input>
                </div>
                <div class="mb-3">
                    <x-forms.input
                        :label="__('Whatsapp token')"
                        name="whatsapp_token"
                        size="lg"
                        required
                        value="{{ $app_is_demo ? '**********' : $whatsapp?->whatsapp_token }}"
                    >
                    </x-forms.input>
                </div>
                <div class="mb-3">
                    <x-forms.input
                        :label="__('Whatsapp phone')"
                        name="whatsapp_phone"
                        size="lg"
                        required
                        value="{{ $app_is_demo ? '**********' : $whatsapp?->whatsapp_phone }}"
                    >
                    </x-forms.input>
                </div>
                <div class="mb-3">
                    <x-forms.input
                        :label="__('Whatsapp sandbox phone')"
                        name="whatsapp_sandbox_phone"
                        size="lg"
                        value="{{ $app_is_demo ? '**********' : $whatsapp?->whatsapp_sandbox_phone }}"
                    >
                    </x-forms.input>
                </div>
                <div class="mb-3">
                    <x-forms.input
                        id="whatsapp_environment"
                        type="select"
                        size="lg"
                        name="whatsapp_environment"
                        label="{{ __('Environment') }}"
                    >
                        <option
                            {{ $whatsapp?->whatsapp_environment === 'sandbox' ? 'selected' : '' }}
                            value="sandbox"
                        >@lang('SANDBOX')</option>
                        <option
                            {{ $whatsapp?->whatsapp_environment === 'production' ? 'selected' : '' }}
                            value="production"
                        >@lang('PRODUCTION')</option>
                    </x-forms.input>
                </div>

                @if ($whatsapp)
                    <div class="mb-3">
                        <x-forms.input
                            :label="__('Webhook Url')"
                            name="webhook"
                            size="lg"
                            value="{{ route('api.titan-leads.whatsapp.webhook', $whatsapp?->id) }}"
                        >
                        </x-forms.input>
                    </div>
                @endif

                @if ($app_is_demo)
                    <x-button
                        type="button"
                        onclick="return toastr.info('This feature is disabled in Demo version.');"
                    >
                        {{ __('Save') }}
                    </x-button>
                @else
                    <x-button
                        class="mt-3"
                        type="submit"
                    >
                        {{ __('Save') }}
                    </x-button>
                @endif
            </form>
        </x-card>

        <x-card>
            <x-slot name="head">
                {{ __('Titan Leads SMS Settings') }}
            </x-slot>
            <form action="{{ route('dashboard.user.titan-leads.settings.sms') }}" method="post">
                @csrf
                <div class="mb-3">
                    <x-forms.input :label="__('Twilio Account SID')" name="account_sid" size="lg" value="{{ $app_is_demo ? '**********' : $sms?->account_sid }}" />
                </div>
                <div class="mb-3">
                    <x-forms.input :label="__('Twilio Auth Token')" name="auth_token" size="lg" value="{{ $app_is_demo ? '**********' : $sms?->auth_token }}" />
                </div>
                <div class="mb-3">
                    <x-forms.input :label="__('From Number')" name="from_number" size="lg" value="{{ $app_is_demo ? '**********' : $sms?->from_number }}" />
                </div>
                <div class="mb-3">
                    <x-forms.input :label="__('Webhook Url')" name="webhook" size="lg" value="{{ route('api.titan-leads.sms.webhook') }}" />
                </div>
                @if ($app_is_demo)
                    <x-button type="button" onclick="return toastr.info('This feature is disabled in Demo version.');">{{ __('Save') }}</x-button>
                @else
                    <x-button class="mt-3" type="submit">{{ __('Save') }}</x-button>
                @endif
            </form>
        </x-card>

        <x-card>
            <x-slot name="head">
                {{ __('Titan Leads Voice Settings') }}
            </x-slot>
            <form action="{{ route('dashboard.user.titan-leads.settings.voice') }}" method="post">
                @csrf
                <div class="mb-3">
                    <x-forms.input :label="__('Twilio Account SID')" name="account_sid" size="lg" value="{{ $app_is_demo ? '**********' : $voice?->account_sid }}" />
                </div>
                <div class="mb-3">
                    <x-forms.input :label="__('Twilio Auth Token')" name="auth_token" size="lg" value="{{ $app_is_demo ? '**********' : $voice?->auth_token }}" />
                </div>
                <div class="mb-3">
                    <x-forms.input :label="__('From Number')" name="from_number" size="lg" value="{{ $app_is_demo ? '**********' : $voice?->from_number }}" />
                </div>
                <div class="mb-3">
                    <x-forms.input :label="__('Webhook Url')" name="webhook" size="lg" value="{{ route('api.titan-leads.voice.webhook') }}" />
                </div>
                @if ($app_is_demo)
                    <x-button type="button" onclick="return toastr.info('This feature is disabled in Demo version.');">{{ __('Save') }}</x-button>
                @else
                    <x-button class="mt-3" type="submit">{{ __('Save') }}</x-button>
                @endif
            </form>
        </x-card>

        <x-card>
            <x-slot name="head">
                {{ __('Titan Leads Email Settings') }}
            </x-slot>
            <form action="{{ route('dashboard.user.titan-leads.settings.email') }}" method="post">
                @csrf
                <div class="mb-3">
                    <x-forms.input :label="__('From Email')" name="from_email" size="lg" value="{{ $app_is_demo ? '**********' : $email?->from_email }}" />
                </div>
                <div class="mb-3">
                    <x-forms.input :label="__('From Name')" name="from_name" size="lg" value="{{ $app_is_demo ? '**********' : $email?->from_name }}" />
                </div>
                <div class="mb-3">
                    <x-forms.input :label="__('Webhook Url')" name="webhook" size="lg" value="{{ route('api.titan-leads.email.webhook') }}" />
                </div>
                <details class="mb-3">
                    <summary class="mb-2">SMTP (optional)</summary>
                    <div class="mb-3"><x-forms.input :label="__('SMTP Host')" name="smtp_host" size="lg" value="{{ $app_is_demo ? '**********' : $email?->smtp_host }}" /></div>
                    <div class="mb-3"><x-forms.input :label="__('SMTP Port')" name="smtp_port" size="lg" value="{{ $app_is_demo ? '**********' : $email?->smtp_port }}" /></div>
                    <div class="mb-3"><x-forms.input :label="__('SMTP Username')" name="smtp_username" size="lg" value="{{ $app_is_demo ? '**********' : $email?->smtp_username }}" /></div>
                    <div class="mb-3"><x-forms.input :label="__('SMTP Password')" name="smtp_password" size="lg" value="{{ $app_is_demo ? '**********' : $email?->smtp_password }}" /></div>
                    <div class="mb-3"><x-forms.input :label="__('SMTP Encryption')" name="smtp_encryption" size="lg" value="{{ $app_is_demo ? '**********' : $email?->smtp_encryption }}" /></div>
                </details>
                @if ($app_is_demo)
                    <x-button type="button" onclick="return toastr.info('This feature is disabled in Demo version.');">{{ __('Save') }}</x-button>
                @else
                    <x-button class="mt-3" type="submit">{{ __('Save') }}</x-button>
                @endif
            </form>
        </x-card>
    </div>
@endsection

@push('script')
@endpush
