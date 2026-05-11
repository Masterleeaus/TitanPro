{{-- Editing Step 3 - Train --}}
<div
	class="col-start-1 col-end-1 row-start-1 row-end-1 transition-all"
	data-step="3"
	x-data="externalTitanOperatorChannel"
	x-show="editingStep === 5"
	x-transition:enter-start="opacity-0 -translate-x-3"
	x-transition:enter-end="opacity-100 translate-x-0"
	x-transition:leave-start="opacity-100 translate-x-0"
	x-transition:leave-end="opacity-0 translate-x-3"
>
	<h2 class="mb-3.5">
		@lang('Client Portal Channels')
	</h2>
	<p class="mb-3 text-xs/5 opacity-60 lg:max-w-[360px]">
		@lang('This step is optional, but you can expand your client portal across supported messaging channels.')
	</p>

	<div class="mt-10 lqd-social-media-cards-grid gap-3 flex ">
		@includeIf('telegram-channel::channel-card')
		@includeIf('whatsapp-channel::channel-card')
		@includeIf('messenger-channel::channel-card')
		@includeIf('instagram-channel::channel-card')
	</div>

	@include('titan_operator::partials.channel-table')
</div>

@push('script')
	<script>

		(() => {
			document.addEventListener('alpine:init', () => {
				Alpine.data('externalTitanOperatorChannel', () => ({
					activeTab: 'channel',
					fetching: false,
					storeChannelFetch: false,
					channelFetch: false,
					titan_operatorChannels: [],
					instagramCredentials: {},
					instagramStatus: null,
					instagramPopupOpen: false,
					init() {
						this.$watch('editingStep', currentStep => {
							if (currentStep === 5) {
								this.fetchChannels();
							}
						})

						window.addEventListener('message', event => {
							if (event.origin !== window.location.origin) {
								return;
							}

							if (event.data?.type === 'titan_operator-instagram:authorized') {
								this.instagramPopupOpen = false;

								const payload = event.data.payload || {};

								if (!payload.access_token) {
									this.instagramStatus = '{{ __('Instagram data could not be retrieved. Please try again.') }}';
									toastr.error('{{ __('Instagram data could not be retrieved. Please try again..') }}');

									return;
								}

								this.instagramCredentials = payload;
								this.instagramStatus = '{{ __('Instagram authorisation has been verified. Channel is being added...') }}';

								this.storeChannel('storeForm-instagram');
							}
						});
					},
					openInstagramOauth() {

						@if(\App\Helpers\Classes\MarketplaceHelper::isRegistered('titan_operator-instagram'))
							if (!this.activeTitanOperator || !this.activeTitanOperator.id) {
								toastr.error('{{ __('First, select a client portal.') }}');

								return;
							}

							const url = new URL('{{ route('titan_operator.instagram.oauth.redirect') }}', window.location.origin);

							url.searchParams.set('titan_operator_id', this.activeTitanOperator.id);
							url.searchParams.set('return_url', window.location.href);

							const popup = window.open(url.toString(), 'instagram-oauth', 'width=520,height=780');

							if (!popup) {
								toastr.error('{{ __('The browser has blocked pop-ups. Please allow them.') }}');

								return;
							}

							this.instagramPopupOpen = true;
							popup.focus();
						@else

							return;

						@endif

					},
					async deleteToClipboard(id) {
						@if($app_is_demo)
						       return toastr.error('{{ trans('This feature is disabled in demo mode.') }}')
						@endif
						if(confirm('If you delete the channel, all conversations linked to it will stop receiving messages. This may lead to some issues.')) {
							this.storeChannelFetch = true;

							const formData = new FormData();
							formData.append('channel_id', id)

							fetch('{{ route('dashboard.titan_operator-multi-channel.delete') }}' , {
								method: 'POST',
								headers: {
									'Accept': 'application/json',
									'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
								},
								body: formData
							}).then(async response => {
								if (!response.ok) {
									const error = await response.json();
									toastr.error(error.message || 'Bir hata oluştu');
									this.storeChannelFetch = false;
									return;
								}

								const data = await response.json();

								if (data.status === 'success') {

									this.titan_operatorChannels = this.titan_operatorChannels.filter(channel => channel.id !== id);

									toastr.success(data.message);

									this.storeChannelFetch = false;
								}
								else {
									toastr.error(data.message || 'An error has occurred');
									this.storeChannelFetch = false;
								}
							}).catch(error => {
								this.storeChannelFetch = false;
								toastr.error(error.message || 'An error occurred while sending the request');
							});
						}
					},
					copyToClipboard(text) {
						navigator.clipboard.writeText(text)
							.then(() => {
								toastr.success('The webhook has been copied to the clipboard.');
							})
							.catch(err => {
								console.error('Copy error:', err);
								toastr.error('The webhook could not be copied.');
							});
					},
					setActiveTab(tab) {
						if (tab === this.activeTab) return;

						this.activeTab = tab;
					},
					async fetchChannels() {
						this.channelFetch = true;

						const formData = new FormData();

						formData.append('titan_operator_id', this.activeTitanOperator.id)

						fetch('{{ route('dashboard.titan_operator-multi-channel.index') }}', {
							method: 'POST',
							headers: {
								'Accept': 'application/json',
								'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
							},
							body: formData
						}).then(async response => {
							if (!response.ok) {
								const error = await response.json();
								toastr.error(error.message || '{{ trans('An error occurred') }}');
								this.channelFetch = false;
								return;
							}

							const data = await response.json();

							if (data.status === 'success') {

								this.titan_operatorChannels = data.data;

								this.channelFetch = false;
							}
						})
							.catch(error => {
								this.channelFetch = false;
								toastr.error(error.message || '{{ trans('An error occurred while sending the request.') }}');
							});
					},
					storeChannel(id) {
						this.storeChannelFetch = true;

						const form = document.getElementById(id);
						const formData = new FormData(form);
						formData.append('titan_operator_id', this.activeTitanOperator.id)

						fetch(form.action, {
							method: 'POST',
							headers: {
								'Accept': 'application/json',
								'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
							},
							body: formData
						}).then(async response => {

							if (!response.ok) {
								const error = await response.json();

								toastr.error(error.message || '{{ trans('An error occurred') }}');

								this.storeChannelFetch = false;

								return;
							}

							const data = await response.json();

							if (data.status === 'success') {

								this.titan_operatorChannels.push(data.data);

								if (id === 'storeForm-instagram') {
									this.instagramStatus = '{{ __('Instagram kanalı bağlandı.') }}';
									this.instagramCredentials = {};
								}

								toastr.success(data.message);

								this.storeChannelFetch = false;
							}
							else {
								if (id === 'storeForm-instagram') {
									this.instagramStatus = data.message || '{{ __('Instagram kanalı eklenirken hata oluştu.') }}';
								}

								toastr.error(data.message || '{{ trans('An error occurred') }}');
								this.storeChannelFetch = false;
							}
						}).catch(error => {
							this.storeChannelFetch = false;

							if (id === 'storeForm-instagram') {
								this.instagramStatus = error.message || '{{ __('Instagram kanalı eklenirken hata oluştu.') }}';
							}

							toastr.error(error.message || '{{ trans('An error occurred while sending the request.') }}');
						});
					}

				}));
			});
		})();
	</script>
@endpush
