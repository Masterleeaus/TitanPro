@php
    $user_avatar = Auth::user()->avatar;

    if (!Auth::user()->github_token && !Auth::user()->google_token && !Auth::user()->facebook_token) {
        $user_avatar = '/' . $user_avatar;
    }
@endphp

@extends('panel.layout.app', ['disable_tblr' => true])
@section('title', __('Voice TitanOperators'))
@section('titlebar_subtitle')
    {{ __('Build AI voice operators that speak and respond naturally—just like a real person.') }}
@endsection
@section('titlebar_actions')
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
        @click.prevent="$store.externalTitanOperatorEditor.setActiveTitanOperator('new_operator', 1, true);"
        x-data="{}"
    >
        <x-tabler-plus class="size-4" />
        @lang('Add New Voice Titan Operator')
    </x-button>
@endsection

@section('content')
    <div class="py-10">
        <div
            class="lqd-external-titan_operator-edit"
            x-data="externalTitanOperatorEditor"
            @keydown.escape.window="setActiveTitanOperator(null)"
        >
            @include('titan_operator-voice::home.actions-grid')

            @include('titan_operator-voice::home.operators-list', ['operators' => $operators])

            @include('titan_operator-voice::home.edit-window.edit-window', ['avatars' => $avatars])
        </div>

        @include('titan_operator-voice::home.chats-history.chats-history')
    </div>
@endsection

@push('script')
    <link
        rel="stylesheet"
        href="{{ custom_theme_url('/assets/libs/prism/prism.css') }}"
    />
    <script src="{{ custom_theme_url('/assets/libs/prism/prism.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/beautify-html.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/markdown-it.min.js') }}"></script>
    <script src="{{ custom_theme_url('/assets/libs/turndown.js') }}"></script>

    <script>
        (() => {
            document.addEventListener('alpine:init', () => {
                Alpine.data('externalTitanOperatorEditor', () => ({
                    operators: @json($operators),
                    activeTitanOperator: {},
                    prevActiveTitanOperatorId: null,
                    editingStep: 1,
                    submittingData: false,
                    // used for the titan_operator ui
                    externalTitanOperator: null,
                    // used for the training tab
                    externalTitanOperatorTraining: null,
                    defaultFormInputs: {
                        id: '',
                        title: '{{ $setting->site_name . __('Bots') }}',
                        bubble_message: '{{ __('Hey there, How can I help you?') }}',
                        welcome_message: '{{ __('Hi, how can I help you?') }}',
                        connect_message: '{{ __('I’ve forwarded your request to a human agent. An agent will connect with you as soon as possible.') }}',
                        instructions: '',
                        language: '',
                        avatar: (@json($avatars->isEmpty() ? [] : $avatars->random()))?.avatar || '{{ $user_avatar }}',
                        position: 'right',
                        active: true,
                    },
                    formErrors: {},
                    init() {
                        this.createNewChatObj();
                        this.initFormErrors();

                        Alpine.store('externalTitanOperatorEditor', this);
                    },
                    createNewChatObj() {
                        this.operators.data.unshift({
                            ...this.defaultFormInputs,
                            id: 'new_operator',
                        })
                    },
                    initFormErrors() {
                        Object.keys(this.defaultFormInputs).forEach(key => {
                            this.formErrors[key] = [];
                        });
                    },
                    setActiveTitanOperator(operatorId, step, skipCRUD = false) {
                        const topNoticeBar = document.querySelector('.top-notice-bar');
                        const navbar = document.querySelector('.lqd-navbar');
                        const pageContentWrap = document.querySelector('.lqd-page-content-wrap');
                        const navbarExpander = document.querySelector('.lqd-navbar-expander');

                        const activeTitanOperatorId = this.activeTitanOperator.id;

                        this.activeTitanOperator = this.operators.data.find(c => c.id === operatorId) || {
                            id: operatorId
                        };

                        if (activeTitanOperatorId) {
                            this.prevActiveTitanOperatorId = activeTitanOperatorId;
                        }

                        if (step) {
                            this.setEditingStep(step, skipCRUD);
                        }

                        this.formErrors = {};

                        document.documentElement.style.overflow = this.activeTitanOperator.id ? 'hidden' :
                            '';

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
                            this.activeTitanOperator.id === 'new_operator'
                        ) {
                            await this.createNewTitanOperator();
                            return;
                        }

                        if (
                            !skipCRUD &&
                            prevStep !== editingStep &&
                            (prevStep === 2 || (prevStep === 1 && editingStep === 2)) &&
                            this.activeTitanOperator.id !== 'new_operator'
                        ) {
                            await this.updateTitanOperator();
                        }

                        if (
                            !skipCRUD &&
                            this.externalTitanOperatorTraining != null &&
                            editingStep === 3 &&
                            this.activeTitanOperator.id !== 'new_operator'
                        ) {
                            this.externalTitanOperatorTraining.fetchEmbeddings();
                        }

                        this.prevEditingStep = this.editingStep;
                        this.editingStep = editingStep;
                    },
                    async toggleTitanOperatorActivation(operatorId) {
                        const titan_operator = this.operators.data.find(c => c.id === operatorId);

                        if (!titan_operator) return;


                        await this.updateTitanOperator(titan_operator);
                    },
                    async deleteTitanOperator(event) {
                        @if ($app_is_demo)
                            toastr.error(
                                '{{ trans('This feature is disabled in Demo version.') }}');
                            return;
                        @endif

                        if (!confirm(
                                '{{ __('Are you sure you want to delete this titan_operator?') }}')) {
                            return;
                        }

                        const form = event.target;
                        const id = form.elements['id'].value;
                        const operatorIndex = this.operators.data.findIndex(c => c.id == id);

                        this.submittinData = true;

                        const res = await fetch(form.action, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: this.getFormData(this.operators.data.at(operatorIndex))
                        });

                        if (!res.ok) {
                            toastr.error('{{ __('Failed to delete titan_operator') }}');
                            return;
                        }

                        const data = await res.json();

                        if (data.type !== 'success') {
                            toastr.error(data.message);
                            return;
                        }

                        if (operatorIndex !== -1) {
                            this.operators.data.splice(operatorIndex, 1);
                        }

                        this.submittingData = false;

                        toastr.clear();
                        toastr.success(data.message ||
                            '{{ __('Titan Operator deleted successfully') }}');
                    },
                    training: {
                        activeTab: 'url',
                        setActiveTab(tab) {
                            if (this.activeTab === tab) return;
                            this.activeTab = tab;
                        }
                    },
                    async createNewTitanOperator() {
                        @if ($app_is_demo)
                            toastr.error(
                                '{{ trans('This feature is disabled in Demo version.') }}');
                            return;
                        @endif

                        this.submittingData = true;
                        this.formErrors = {};

                        const res = await fetch(
                            '{{ route('dashboard.titan_operator-voice.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: this.getFormData()
                            });
                        const data = await res.json();
                        const {
                            data: operatorData
                        } = data;

                        this.submittingData = false;

                        if (!res.ok || !operatorData) {
                            if (data.errors) {
                                this.formErrors = data.errors;
                            } else if (data.message) {
                                toastr.error(data.message);
                            }

                            this.setEditingStep(1, true);
                            return;
                        }

                        this.operators.data.shift();

                        this.operators.data.unshift({
                            ...this.defaultFormInputs,
                            ...operatorData
                        });

                        this.setActiveTitanOperator(operatorData.id);
                        this.setEditingStep(2, true);
                        this.createNewChatObj();

                        toastr.clear();
                        toastr.success('{{ __('Titan Operator created successfully') }}');
                    },
                    async updateTitanOperator(titan_operator) {
                        this.submittingData = true;
                        this.formErrors = {};

                        const res = await fetch(
                            '{{ route('dashboard.titan_operator-voice.update') }}', {
                                method: 'PUT',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: this.getFormData(titan_operator)
                            });
                        const data = await res.json();
                        const {
                            data: operatorData
                        } = data;

                        this.submittingData = false;

                        if (!res.ok || !operatorData) {
                            if (data.errors) {
                                this.formErrors = data.errors;
                            } else if (data.message) {
                                toastr.error(data.message);
                            }

                            return;
                        }

                        const operatorIndex = this.operators.data.findIndex(c => c.id ===
                            operatorData.id);

                        if (operatorIndex !== -1) {
                            this.operators.data[operatorIndex] = {
                                ...this.operators.data[operatorIndex],
                                ...operatorData
                            };
                        }

                        toastr.clear();
                        toastr.success('{{ __('Titan Operator updated successfully') }}');
                    },
                    getFormData(titan_operator) {
                        const operatorData = titan_operator || this.activeTitanOperator;
                        const formData = {};

                        Object.keys(operatorData).forEach(key => {
                            formData[key] = operatorData[key];
                        });

                        return JSON.stringify(formData);
                    }
                }));
            });
        })();
    </script>
@endpush
