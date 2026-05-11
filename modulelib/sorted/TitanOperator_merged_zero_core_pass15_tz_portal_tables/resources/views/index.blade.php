@php
    $user_avatar = Auth::user()->avatar;

    if (!Auth::user()->github_token && !Auth::user()->google_token && !Auth::user()->facebook_token) {
        $user_avatar = '/' . $user_avatar;
    }
    $human_agent_conditions = [
        'When the issue is too complex or ambiguous.',
        'When the customer is frustrated or dissatisfied.',
        'When sensitive topics (legal, financial, medical, etc.) are involved.',
        'When the AI fails to understand after repeated attempts.',
        'When empathy or emotional intelligence is required.',
        'When the request is outside the AI’s scope or permissions.',
        'When the customer explicitly requests a human.',
    ];
@endphp

@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Client Portal Builder'))
@section('titlebar_subtitle')
    {{ __('Build and manage client-facing portals, widgets, and training flows') }}
@endsection
@section('titlebar_actions')
    <x-button href="{{ route('dashboard.user.client_portal.builder') }}" variant="ghost-shadow">{{ __('Builder') }}</x-button>
    <x-button href="{{ route('dashboard.user.client_portal.preview') }}" variant="ghost-shadow">{{ __('Preview') }}</x-button>
    <x-button
        href="#"
        variant="ghost-shadow"
        @click.prevent="$store.externalTitanOperatorHistory.setOpen(true)"
        x-data="{}"
    >
        @lang('Chat History')
    </x-button>

    <x-button
        href="#"
        @click.prevent="$store.externalTitanOperatorEditor.setActiveTitanOperator('new_titan_operator', 1, true);"
        x-data="{}"
    >
        <x-tabler-plus class="size-4" />
        @lang('Add New Client Portal')
    </x-button>
@endsection

@push('head')
    @include('titan_operator::partials.client-portal-pwa-head')
@endpush

@section('content')
    <div class="py-10">
        <div
            class="lqd-external-titan_operator-edit"
            x-data="externalTitanOperatorEditor"
            @keydown.escape.window="setActiveTitanOperator(null)"
        >
            @include('titan_operator::home.actions-grid')

            @include('titan_operator::home.titan_operators-list', ['titan_operators' => $titan_operators])

            @include('titan_operator::home.edit-window.edit-window', ['avatars' => $avatars])
        </div>

        @include('titan_operator::home.chats-history.chats-history')
    </div>
@endsection

@push('script')
    <link
        rel="stylesheet"
        href="{{ custom_theme_url('/assets/libs/prism/prism.css') }}"
    />
    <link
        rel="stylesheet"
        href="{{ custom_theme_url('assets/libs/jscolorpicker/dist/colorpicker.css') }}"
    >
    <script src="{{ custom_theme_url('assets/libs/jscolorpicker/dist/colorpicker.iife.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/prism/prism.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/beautify-html.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/markdownit/markdown-it.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/turndown.js') }}"></script>

    <script>
        (() => {
            document.addEventListener('alpine:init', () => {
                Alpine.data('externalTitanOperatorEditor', () => ({
                    titan_operators: @json($titan_operators),
                    activeTitanOperator: {},
                    prevActiveTitanOperatorId: null,
                    editingStep: 1,
                    submittingData: false,
                    // used for the titan_operator ui
                    externalTitanOperator: null,
                    // used for the training tab
                    externalTitanOperatorTraining: null,
                    testIframeWidth: 420,
                    testIframeHeight: 745,
                    defaultFormInputs: {
                        id: '',
                        interaction_type: 'automatic_response',
                        title: '{{ $setting->site_name . __(' Client Portal') }}',
                        bubble_message: '{{ __('Hi there, how can we help?') }}',
                        welcome_message: '{{ __('Hi there, how can we help you today?') }}',
                        connect_message: '{{ __('I’ve forwarded your request to a human agent. An agent will connect with you as soon as possible.') }}',
                        instructions: '',
                        do_not_go_beyond_instructions: 0,
                        language: '',
                        ai_model: 'gpt-3.5-turbo',
                        logo: '',
                        avatar: (@json($avatars?->isEmpty() ? [] : $avatars->random()))?.avatar || '{{ $user_avatar }}',
                        color: '#272733',
                        show_logo: true,
                        show_date_and_time: true,
                        show_average_response_time: true,
                        trigger_background: '',
                        trigger_avatar_size: '60px',
                        position: 'right',
                        active: true,
                        footer_link: '',
                        whatsapp_link: '',
                        telegram_link: '',
                        watch_product_tour_link: '',
                        is_email_collect: true,
                        is_contact: true,
                        is_attachment: true,
                        is_emoji: true,
                        is_articles: true,
                        is_links: true,
                        header_bg_type: 'color',
                        header_bg_color: '',
                        header_bg_gradient: '',
                        header_bg_image: '',
                        header_bg_image_blob: null,
                        human_agent_conditions: []
                    },
                    formErrors: {},
                    contactInfo: {
                        activeTab: 'details',
                        editMode: false,
                    },
                    mobile: {
                        filtersVisible: false,
                        contactInfoVisible: false,
                    },

                    init() {
                        this.createNewChatObj();
                        this.initFormErrors();

                        Alpine.store('externalTitanOperatorEditor', this);
                    },
                    createNewChatObj() {
                        this.titan_operators.data.unshift({
                            ...this.defaultFormInputs,
                            id: 'new_titan_operator',
                        })
                    },
                    initFormErrors() {
                        Object.keys(this.defaultFormInputs).forEach(key => {
                            this.formErrors[key] = [];
                        });
                    },
                    setActiveTitanOperator(titan_operatorId, step, skipCRUD = false) {
                        const topNoticeBar = document.querySelector('.top-notice-bar');
                        const navbar = document.querySelector('.lqd-navbar');
                        const pageContentWrap = document.querySelector('.lqd-page-content-wrap');
                        const navbarExpander = document.querySelector('.lqd-navbar-expander');

                        const activeTitanOperatorId = this.activeTitanOperator.id;

                        this.activeTitanOperator = this.titan_operators.data.find(c => c.id === titan_operatorId) || {
                            id: titan_operatorId
                        };

                        if (activeTitanOperatorId) {
                            this.prevActiveTitanOperatorId = activeTitanOperatorId;
                        }

                        if (step) {
                            this.setEditingStep(step, skipCRUD);
                        }

                        this.formErrors = {};

                        document.documentElement.style.overflow = this.activeTitanOperator.id ? 'hidden' : '';

                        if (window.innerWidth >= 992) {

                            if (navbar) {
                                navbar.style.position = this.activeTitanOperator.id ? 'fixed' : '';
                            }

                            if (pageContentWrap && navbar?.offsetWidth > 0) {
                                pageContentWrap.style.paddingInlineStart = this.activeTitanOperator.id ? 'var(--navbar-width)' : '';
                            }

                            if (topNoticeBar) {
                                topNoticeBar.style.visibility = this.activeTitanOperator.id ? 'hidden' :
                                    '';
                            }

                            if (navbarExpander) {
                                navbarExpander.style.visibility = this.activeTitanOperator.id ? 'hidden' :
                                    '';
                                navbarExpander.style.opacity = this.activeTitanOperator.id ? 0 : 1;
                            }
                        }
                    },
                    async setEditingStep(step, skipCRUD = false) {
                        const prevStep = this.editingStep;
                        let editingStep = step;

                        if (step === '>') {
                            editingStep = Math.min(4, this.editingStep + 1);
                        } else if (step === '<') {
                            editingStep = Math.max(1, this.editingStep - 1);
                        }

                        if (
                            !skipCRUD &&
                            prevStep !== editingStep &&
                            prevStep === 1 &&
                            this.activeTitanOperator.id === 'new_titan_operator'
                        ) {
                            await this.createNewTitanOperator();
                            return;
                        }

                        if (
                            !skipCRUD &&
                            prevStep !== editingStep &&
                            (prevStep === 2 || (prevStep === 1 && editingStep === 2)) &&
                            this.activeTitanOperator.id !== 'new_titan_operator'
                        ) {
                            await this.updateTitanOperator();
                        }

                        if (
                            !skipCRUD &&
                            this.externalTitanOperatorTraining != null &&
                            editingStep === 3 &&
                            this.activeTitanOperator.id !== 'new_titan_operator'
                        ) {
                            this.externalTitanOperatorTraining.fetchEmbeddings();
                        }

                        this.prevEditingStep = this.editingStep;
                        this.editingStep = editingStep;
                    },
                    async toggleTitanOperatorActivation(titan_operatorId) {
                        const titan_operator = this.titan_operators.data.find(c => c.id === titan_operatorId);

                        if (!titan_operator) return;

                        await this.updateTitanOperator(titan_operator);
                    },
                    async deleteTitanOperator(event) {
                        if (!confirm(
                                '{{ __('Are you sure you want to delete this titan_operator?') }}')) {
                            return;
                        }

                        const form = event.target;
                        const id = form.elements['id'].value;
                        const titan_operatorIndex = this.titan_operators.data.findIndex(c => c.id == id);

                        this.submittinData = true;

                        const res = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: this.getFormData(this.titan_operators.data.at(titan_operatorIndex))
                        });

                        if (!res.ok) {

                            const data = await res.json();

                            toastr.error(data.message);

                            return;
                        }

                        const data = await res.json();

                        if (data.type !== 'success') {
                            toastr.error(data.message);
                            return;
                        }

                        if (titan_operatorIndex !== -1) {
                            this.titan_operators.data.splice(titan_operatorIndex, 1);
                        }

                        this.submittingData = false;

                        toastr.clear();
                        toastr.success(data.message ||
                            '{{ __('TitanOperator deleted successfully') }}');
                    },
                    training: {
                        activeTab: 'website',
                        setActiveTab(tab) {
                            if (this.activeTab === tab) return;
                            this.activeTab = tab;
                        }
                    },
                    async createNewTitanOperator() {
                        this.submittingData = true;
                        this.formErrors = {};

                        const res = await fetch('{{ route('dashboard.titan_operator.store') }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                // 'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: this.getFormData()
                        });
                        const data = await res.json();
                        const {
                            data: titan_operatorData
                        } = data;

                        this.submittingData = false;

                        if (!res.ok || !titan_operatorData) {
                            if (data.errors) {
                                this.formErrors = data.errors;
                            } else if (data.message) {
                                toastr.error(data.message);
                            }

                            this.setEditingStep(1, true);
                            return;
                        }

                        this.titan_operators.data.shift();

                        this.titan_operators.data.unshift({
                            ...this.defaultFormInputs,
                            ...titan_operatorData
                        });

                        this.setActiveTitanOperator(titan_operatorData.id);
                        this.setEditingStep(2, true);
                        this.createNewChatObj();

                        toastr.clear();
                        toastr.success('{{ __('TitanOperator created successfully') }}');
                    },
                    async updateTitanOperator(titan_operator) {
                        this.submittingData = true;
                        this.formErrors = {};

                        const res = await fetch('{{ route('dashboard.titan_operator.update') }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                // Content-Type header'ını kaldırıyoruz, FormData kendi ayarlayacak
                            },
                            body: this.getFormData(titan_operator)
                        });

                        const data = await res.json();
                        const {
                            data: titan_operatorData
                        } = data;

                        this.submittingData = false;

                        if (!res.ok || !titan_operatorData) {
                            if (data.errors) {
                                this.formErrors = data.errors;
                            } else if (data.message) {
                                toastr.error(data.message);
                            }
                            return;
                        }

                        const titan_operatorIndex = this.titan_operators.data.findIndex(c => c.id === titan_operatorData.id);

                        if (titan_operatorIndex !== -1) {
                            this.titan_operators.data[titan_operatorIndex] = {
                                ...this.titan_operators.data[titan_operatorIndex],
                                ...titan_operatorData
                            };
                        }

                        toastr.clear();
                        toastr.success('{{ __('TitanOperator updated successfully') }}');
                    },

                    onHumanAgentConditionsChange(event) {
                        const checkboxEl = event.currentTarget;
                        const conditionValue = checkboxEl.getAttribute('data-condition')?.trim();

                        if (!conditionValue) return;

                        if (!this.activeTitanOperator.human_agent_conditions) {
                            this.activeTitanOperator.human_agent_conditions = [];
                        }

                        const existingConditionIndex = this.activeTitanOperator.human_agent_conditions.findIndex(condition => condition === conditionValue);

                        if (checkboxEl.checked && existingConditionIndex === -1) {
                            this.activeTitanOperator.human_agent_conditions.push(conditionValue);
                        } else if (!checkboxEl.checked) {
                            this.activeTitanOperator.human_agent_conditions.splice(existingConditionIndex, 1);
                        }
                    },

                    getFormData(titan_operator) {
                        const titan_operatorData = titan_operator || this.activeTitanOperator;
                        const formData = new FormData();

                        Object.keys(titan_operatorData).forEach(key => {
                            const value = titan_operatorData[key];

                            // Dosya kontrolü
                            if (value instanceof File) {
                                formData.append(key, value);
                            }
                            // Array kontrolü (çoklu dosyalar için)
                            else if (Array.isArray(value)) {
                                value.forEach((item, index) => {
                                    if (item instanceof File) {
                                        formData.append(`${key}[${index}]`, item);
                                    } else {
                                        formData.append(`${key}[${index}]`, item);
                                    }
                                });
                            } else if (typeof value === 'boolean') {
                                formData.append(key, value ? 1 : 0);
                            }

                            // Null değerleri atla
                            else if (value !== null && value !== undefined) {
                                formData.append(key, value);
                            }
                        });

                        return formData;
                    }
                }));
            });
        })();
    </script>
@endpush
