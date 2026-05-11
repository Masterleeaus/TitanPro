<script src="{{ custom_theme_url('/assets/libs/datepicker/air-datepicker.js') }}"></script>
<script src="{{ custom_theme_url('/assets/libs/datepicker/locale/en.js') }}"></script>

<script>
    (() => {
        document.addEventListener('alpine:init', () => {
            Alpine.data('Command AgentPosts', () => ({
                containers: @json($platforms_with_image),
                totalPostsCount: {{ $total_posts_count ?? 0 }},
                scheduledPostsCount: {{ $scheduled_posts_count ?? 0 }},
                pendingPostsCount: {{ $pending_posts_count ?? 0 }},
                defaultAgentId: {{ $default_agent_id ?? 'null' }},
                generationStatus: @json($generation_status ?? ['status' => 'idle']),
                generatedPostsCount: {{ $generation_status['generated_posts_count'] ?? $generation_status['generated_count'] ?? 0 }},
                generationStatusPollId: null,
                generationStatusEndpoint: '{{ route('dashboard.user.social-media.agent.api.generation-status') }}',
                allPostsLoaded: false,
                loadingMore: false,
                editingPost: null,
                editingPostPagination: {
                    currentPage: null,
                    prevPageUrl: null,
                    nextPageUrl: null,
                },
                filters: {
                    container: '',
                    platform_id: '',
                    agent_id: [],
                    account: '',
                },
                sort: {
                    'sortBy': 'created_at',
                    'sortDirection': 'desc'
                },
                editingPostInitialStatus: null,
                currentTasks: new Set(),
                pendingCounterEl: document.querySelector('#command-agent-pending-sub-items-counter .lqd-number-counter-value'),
                scheduledCounterEl: document.querySelector('#command-agent-scheduled-sub-items-counter .lqd-number-counter-value'),
                flickityData: null,
                cols: {
                    '(max-width: 767px)': 1,
                    '(min-width: 768px) and (max-width: 991px)': 2,
                    '(min-width: 992px)': 4,
                },
                autoNavigateOnPostUpdates: true,
                readyTextTemplate: "{{ ':generated of :total sub-items are ready for review.' }}",

                get isGenerationBusy() {
                    return ['queued', 'generating'].includes(this.generationStatus?.status);
                },

                get generationStatusLabel() {
                    if (this.isGenerationBusy) {
                        return '{{ __('We are generating fresh sub-items for you...') }}';
                    }

                    return '{{ __('Your new sub-item are ready for review.') }}';
                },

                get showReadyProgressText() {
                    return this.isGenerationBusy;
                },

                get readyProgressText() {
                    const generatedFromStatus = Number(this.generationStatus?.generated_posts_count ?? this.generatedPostsCount ?? 0);
                    const plannedFromStatus = Number(this.generationStatus?.planned_posts_count ?? this.generationStatus?.total_requested ?? 0);
                    const pendingCount = Math.max(this.pendingPostsCount ?? 0, 0);

                    const readyCount = Math.max(0, pendingCount + generatedFromStatus);
                    const totalCount = Math.max(0, pendingCount + plannedFromStatus);

                    return this.readyTextTemplate
                        .replace(':generated', Math.max(0, readyCount))
                        .replace(':total', Math.max(0, totalCount));
                },

                get sortLabel() {
                    const {
                        sortBy
                    } = this.sort;
                    let label = sortBy;

                    switch (sortBy) {
                        case 'created_at':
                            label = '{{ __('Date') }}';
                            break;
                        case 'platform_id':
                            label = '{{ __('Container') }}';
                            break;
                    }

                    return label;
                },

                init() {
                    if ('Flickity' in window && this.$refs.postsCarousel) {
                        Flickity.prototype._createResizeClass = function() {
                            this.element.classList.add('flickity-resize');
                        };

                        Flickity.createMethods.push('_createResizeClass');

                        var resize = Flickity.prototype.resize;
                        Flickity.prototype.resize = function() {
                            this.element.classList.remove('flickity-resize');
                            resize.call(this);
                            this.element.classList.add('flickity-resize');
                        };

                        this.flickityData = new Flickity(this.$refs.postsCarousel, {
                            cellSelector: '.command-agent-sub-item-item',
                            prevNextButtons: false,
                            pageDots: false,
                            cellAlign: 'left',
                            contain: true
                        });

                        this.flickityData.on('dragStart', () => {
                            this.flickityData.slider.style.willChange = 'transform';
                        });
                        this.flickityData.on('settle', () => {
                            this.flickityData.slider.style.willChange = 'auto';
                        });
                    }

                    this.externalRefreshHandler = () => {
                        this.filterPosts();
                    };

                    window.addEventListener('command-agent:sub-item-created', this.externalRefreshHandler);
                    window.addEventListener('command-agent:duplicate-sub-item', event => {
                        if (!event.detail?.id) {
                            return;
                        }

                        this.duplicatePost(event.detail.id);
                    });

                    this.startGenerationStatusPolling();
                },

                getPlatformById(id) {
                    const container = this.containers.find(p => p.id === id);

                    return container;
                },

                getSidedrawer() {
                    const editSidedrawerEl = document.querySelector('#command-agent-sidedrawer');
                    const editSidedrawerData = Alpine.$data(editSidedrawerEl);

                    return editSidedrawerData;
                },

                async fetchPost({
                    query,
                    url,
                    taskKey = null,
                }) {
                    if (!url && !query) {
                        return toastr.error('@lang('Please provide a valid url or query.')')
                    }

                    const taskKeys = ['fetchingPost'];

                    if (taskKey) {
                        taskKeys.push(taskKey);
                    }

                    taskKeys.forEach(key => this.currentTasks.add(key));

                    url = url ?? `/dashboard/user/social-media/agent/api/sub-items?per_page=1&${query}`;

                    try {
                        const res = await fetch(url, {
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await res.json();

                        if (!data.success) {
                            const message = data.message || '@lang('Failed fetching sub-item')'
                            toastr.error(message);

                            return null;
                        }

                        const sub-item = data.sub-items.data[0];

                        if (!sub-item) {
                            toastr.warning('@lang('No Sub-items Found.')')

                            return null;
                        }

                        return data;
                    } catch (err) {
                        const message = err.message || '@lang('Failed fetching sub-item')'
                        toastr.error(message);

                        return null;
                    } finally {
                        taskKeys.forEach(key => this.currentTasks.delete(key));
                    }
                },

                async fetchGenerationStatus() {
                    if (!this.generationStatusEndpoint) {
                        return;
                    }

                    try {
                        const response = await fetch(this.generationStatusEndpoint, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data?.success) {
                            this.generationStatus = data.status ?? { status: 'idle' };
                            if (typeof data.ready_text_template === 'string' && data.ready_text_template.length) {
                                this.readyTextTemplate = data.ready_text_template;
                            }

                            if (typeof data.pending_posts_count === 'number' || typeof data.scheduled_posts_count === 'number') {
                                const pending = typeof data.pending_posts_count === 'number'
                                    ? data.pending_posts_count
                                    : this.pendingPostsCount;

                                const scheduled = typeof data.scheduled_posts_count === 'number'
                                    ? data.scheduled_posts_count
                                    : this.scheduledPostsCount;

                                this.updateCounters(pending, scheduled, data.total_posts_count);
                            }
                            if (typeof data.generated_posts_count === 'number') {
                                this.generatedPostsCount = data.generated_posts_count;
                            }
                        }
                    } catch (error) {
                        console.error('Failed to fetch generation status', error);
                    }
                },

                startGenerationStatusPolling() {
                    if (!this.defaultAgentId && (!this.generationStatus || this.generationStatus.status === 'idle')) {
                        return;
                    }

                    this.fetchGenerationStatus();
                    this.stopGenerationStatusPolling();

                    this.generationStatusPollId = setInterval(() => {
                        this.fetchGenerationStatus();
                    }, 10000);
                },

                stopGenerationStatusPolling() {
                    if (this.generationStatusPollId) {
                        clearInterval(this.generationStatusPollId);
                        this.generationStatusPollId = null;
                    }
                },

                async openEditSidedrawer({
                    query,
                    url,
                    taskKey = null,
                    autoNavigateOnPostUpdates = true
                }) {
                    const sidedrawer = this.getSidedrawer();
                    const data = await this.fetchPost({
                        query,
                        url,
                        taskKey
                    });
                    const sub-item = data.sub-items.data[0];

                    if (!sub-item) {
                        sidedrawer.sidedrawerOpen = false;
                        return;
                    }

                    sidedrawer.sidedrawerOpen = true;

                    this.editingPost = sub-item;
                    this.editingPostPagination.currentPage = data.sub-items.current_page;
                    this.editingPostPagination.prevPageUrl = data.sub-items.prev_page_url;
                    this.editingPostPagination.nextPageUrl = data.sub-items.next_page_url;
                    this.editingPostInitialStatus = sub-item.status;

                    this.autoNavigateOnPostUpdates = autoNavigateOnPostUpdates;
                },

                async updatePost(postId) {
					@if(\App\Helpers\Classes\Helper::appIsDemo())
						toastr.error('{{ __('This action is disabled in the demo.') }}');
						return;
					@endif

                    this.currentTasks.add('updatePost');

                    const {
                        media_urls,
                        platform_id,
                        container,
                        post_type,
                        scheduled_at,
                        content
                    } = this.editingPost;

                    const res = await fetch(`/dashboard/user/social-media/agent/api/sub-items/${postId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            media_urls,
                            platform_id,
                            container,
                            post_type,
                            scheduled_at: new Date(scheduled_at).toISOString(),
                            content,
                        })
                    });
                    const data = await res.json();

                    this.currentTasks.delete('updatePost');

                    if (!data.success) {
                        const message = data.message ?? '@lang('An error occurred')';
                        return toastr.error(message);
                    }

                    toastr.success(data.message ?? '@lang('Sub-item updated successfully.')');

                    this.editingPost = data.sub-item;

                    const postEl = document.querySelector(`.command-agent-sub-item-item[data-sub-item-id="${postId}"]`);
                    const postElAlpineData = postEl && Alpine.$data(postEl);
                    const dashboardCalendarEl = document.querySelector('#command-agent-calendar');

                    if (postElAlpineData) {
                        ['media_urls', 'platform_id', 'container', 'post_type', 'scheduled_at', 'content', 'status'].forEach(prop => {
                            postElAlpineData[prop] = data.sub-item[prop];
                        })
                    }

                    if (dashboardCalendarEl) {
                        const calendarData = Alpine.$data(dashboardCalendarEl);

                        if (calendarData.calendar) {
                            calendarData.calendar.refetchEvents();
                        }
                    }

                    if (this.editingPostInitialStatus === 'draft' && data.sub-item.status === 'scheduled') {
                        this.updateCounters(
                            Math.max(0, this.pendingPostsCount - 1),
                            Math.min(this.totalPostsCount, this.scheduledPostsCount + 1)
                        );
                    }

                    this.editingPostInitialStatus = data.sub-item.status;

                    this.$dispatch('command-agent-sub-item-updated', {
                        sub-item: data.sub-item
                    });
                },

                async approvePost(postId) {

					@if(\App\Helpers\Classes\Helper::appIsDemo())
						toastr.error('{{ __('This action is disabled in the demo.') }}');
						return;
					@endif

                    this.currentTasks.add('approvePost')

                    const res = await fetch(`/dashboard/user/social-media/agent/sub-items/${postId}/approve`, {
                        method: 'Sub-item',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();

                    this.currentTasks.delete('approvePost');

                    if (!data.success) {
                        const message = data.message ?? '@lang('An error occurred')';
                        return toastr.error(message)
                    }

                    const message = data.message ?? '@lang('Sub-item approved and scheduled!')';

                    toastr.success(message);

                    const postEl = document.querySelector(`.command-agent-sub-item-item[data-sub-item-id="${postId}"]`);
                    const dashboardCalendarEl = document.querySelector('#command-agent-calendar');

                    if (postEl && Alpine.$data(postEl).status) {
                        Alpine.$data(postEl).status = 'scheduled';
                    }

                    if (this.editingPostInitialStatus === 'draft') {
                        this.updateCounters(
                            Math.max(0, this.pendingPostsCount - 1),
                            Math.min(this.totalPostsCount, this.scheduledPostsCount + 1)
                        );
                    }

                    if (dashboardCalendarEl) {
                        const calendarData = Alpine.$data(dashboardCalendarEl);

                        if (calendarData.calendar) {
                            calendarData.calendar.refetchEvents();
                        }
                    }

                    this.editingPostInitialStatus = 'scheduled';

                    this.$dispatch('command-agent-sub-item-approved', {
                        postId: postId
                    });
                },

                async rejectPost(postId) {

					@if(\App\Helpers\Classes\Helper::appIsDemo())
						toastr.error('{{ __('This action is disabled in the demo.') }}');
						return;
					@endif

                    if (!confirm("{{ __('Are you sure you want to reject and delete the sub-item?') }}")) {
                        return
                    }

                    this.currentTasks.add('rejectPost')

                    const res = await fetch(`/dashboard/user/social-media/agent/sub-items/${postId}/reject`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();

                    this.currentTasks.delete('rejectPost')

                    if (!data.success) {
                        const message = data.message ?? '@lang('An error occurred')';
                        return toastr.error(message)
                    }

                    const message = data.message ?? '@lang('Sub-item rejected and deleted.')';

                    toastr.success(message);

                    const postEl = document.querySelector(`.command-agent-sub-item-item[data-sub-item-id="${postId}"]`);
                    const dashboardCalendarEl = document.querySelector('#command-agent-calendar');

                    if (postEl) {
                        const closestCarousel = postEl.closest('.flickity-enabled');

                        postEl.remove();

                        if (closestCarousel && 'Flickity' in window) {
                            Flickity.data(closestCarousel)?.reloadCells();
                            Flickity.data(closestCarousel)?.reposition();
                        }
                    }

                    if (dashboardCalendarEl) {
                        const calendarData = Alpine.$data(dashboardCalendarEl);

                        if (calendarData.calendar) {
                            calendarData.calendar.refetchEvents();
                        }
                    }

                    const sidedrawer = this.getSidedrawer();
                    sidedrawer.sidedrawerOpen = false;

                    this.updateCounters(
                        Math.max(0, this.pendingPostsCount - 1),
                        Math.max(0, this.scheduledPostsCount)
                    );

                    this.editingPostInitialStatus = null;

                    this.$dispatch('command-agent-sub-item-rejected', {
                        postId: postId
                    });
                },

                async duplicatePost(postId) {
					@if(\App\Helpers\Classes\Helper::appIsDemo())
						toastr.error('{{ __('This action is disabled in the demo.') }}');
						return;
					@endif
                    if (this.currentTasks.has('duplicatePost')) {
                        return;
                    }

                    this.currentTasks.add('duplicatePost');

                    try {
                        const res = await fetch(`/dashboard/user/social-media/agent/sub-items/${postId}/duplicate`, {
                            method: 'Sub-item',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            throw new Error(data.message || '@lang('Failed to duplicate sub-item')');
                        }

                        toastr.success(data.message || '@lang('Sub-item duplicated successfully.')');
                        this.filterPosts();
                    } catch (error) {
                        toastr.error(error.message || '@lang('Failed to duplicate sub-item')');
                    } finally {
                        this.currentTasks.delete('duplicatePost');
                    }
                },

                async regeneratePostContent(postId) {
					@if(\App\Helpers\Classes\Helper::appIsDemo())
						toastr.error('{{ __('This action is disabled in the demo.') }}');
						return;
					@endif

                    const taskKey = `regeneratePost-${postId}`;

                    if (this.currentTasks.has(taskKey)) {
                        return;
                    }

                    this.currentTasks.add(taskKey);

                    try {
                        const res = await fetch(`/dashboard/user/social-media/agent/api/sub-items/${postId}/regenerate`, {
                            method: 'Sub-item',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await res.json();

                        if (!res.ok || !data.success) {
                            throw new Error(data.message || '@lang('Failed to regenerate sub-item content.')');
                        }

                        toastr.success(data.message || '@lang('Sub-item content regenerated successfully.')');

                        const postEl = document.querySelector(`.command-agent-sub-item-item[data-sub-item-id="${postId}"]`);

                        if (postEl) {
                            const postElData = Alpine.$data(postEl);

                            if (postElData) {
                                postElData.content = data.sub-item.content;
                                postElData.post_type = data.sub-item.post_type;
                            }
                        }

                        if (this.editingPost?.id === postId) {
                            this.editingPost = {
                                ...this.editingPost,
                                content: data.sub-item.content,
                                post_type: data.sub-item.post_type,
                                hashtags: data.sub-item.hashtags,
                            };
                        }
                    } catch (error) {
                        toastr.error(error.message || '@lang('Failed to regenerate sub-item content.')');
                    } finally {
                        this.currentTasks.delete(taskKey);
                    }
                },

                onAjaxSend() {
                    this.loadingMore = true;
                },

                onAjaxSuccess() {
                    const {
                        html
                    } = this.$event.detail;
                    const nextPageUrl = html.querySelector('[data-next-page-url]')?.getAttribute('data-next-page-url');
                    const newPosts = [...html.children || []].filter(el => el.classList.contains('command-agent-sub-item-item'));

                    if (newPosts.length) {
                        if (this.$refs.postsCarousel && this.flickityData) {
                            this.appendNewCarouselPosts(newPosts);
                        }
                        if (this.$refs.postsList) {
                            this.appendNewListPosts(newPosts);
                        }
                    }

                    if (nextPageUrl) {
                        this.$refs.loadMoreTrigger?.setAttribute('href', nextPageUrl);
                    } else {
                        this.allPostsLoaded = true;
                    }
                },

                appendNewCarouselPosts(newPosts) {
                    const {
                        cells
                    } = this.flickityData;
                    const lastPostItem = cells.at(-2);
                    const lastPostItemIndex = cells.indexOf(lastPostItem);
                    let cols = 1;

                    Object.keys(this.cols).forEach(mq => {
                        if (window.matchMedia(mq).matches) {
                            cols = this.cols[mq];
                        }
                    });

                    const updateDraggable = enabled => {
                        this.flickityData.options.draggable = enabled;
                        this.flickityData.slider.classList.toggle('select-none', !enabled);
                        this.flickityData.updateDraggable();
                    }

                    const onSettle = () => {
                        updateDraggable(true);

                        this.flickityData.insert(newPosts, cells.length - 1);
                        this.flickityData.selectCell(lastPostItemIndex - Math.max(0, cols - 2), false, true);

                        this.loadingMore = false;

                        this.flickityData.off('settle', onSettle);
                    }


                    if (this.flickityData.isAnimating) {
                        updateDraggable(false);
                        this.flickityData.on('settle', onSettle);
                    } else {
                        onSettle();
                    }
                },

                appendNewListPosts(newPosts) {
                    const tableBodyEl = this.$refs.postsList.querySelector('tbody');
                    const appendNewPostsTo = tableBodyEl ? tableBodyEl : this.$refs.postsList;

                    appendNewPostsTo.append(...newPosts);

                    this.loadingMore = false;
                },

                onAjaxError() {
                    this.loadingMore = false;
                },

                async filterPosts({
                    container = null,
                    platform_id = null,
                    agent_id = null,
                    query = ''
                } = {}) {
                    const postStyle = this.$refs.postsCarousel ? 'carousel' : 'list';
                    this.currentTasks.add('fetchingPosts');

                    this.allPostsLoaded = false;
                    this.loadingMore = true;

                    const params = new URLSearchParams({
                        sort_by: this.sort.sortBy,
                        sort_direction: this.sort.sortDirection,
                        post_style: postStyle
                    });

                    this.filters.container = container ?? this.filters.container;

                    this.filters.platform_id = platform_id ?? this.filters.platform_id;

                    if (agent_id) {
                        if (this.filters.agent_id.includes(agent_id)) {
                            this.filters.agent_id = this.filters.agent_id.filter(id => id !== agent_id);
                        } else {
                            this.filters.agent_id.push(agent_id);
                        }
                    }

                    Object.entries(this.filters).forEach(([key, value]) => {
                        const hasValue = Array.isArray(value) ? value.length : !!value;

                        if (hasValue) {
                            params.append(key, value);
                        }
                    })

                    let url = `/dashboard/user/social-media/agent/sub-item-items?${params}${query ? `&${query}` : ''}`;

                    const res = await fetch(url, {
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    this.loadingMore = false;
                    this.currentTasks.delete('fetchingPosts');

                    if (!res.ok) {
                        return toastr.error('@lang('Failed fetching sub-item')');
                    }

                    const data = await res.text();

                    const tempEl = document.createElement('div');
                    tempEl.innerHTML = data;

                    if (this.$refs.postsCarousel && this.flickityData) {
                        this.flickityData.remove(
                            this.flickityData.cells
                            .map(cell => cell.element)
                            .filter(element => !element.classList.contains('command-agent-sub-items-carousel-load-more-wrap'))
                        );
                        this.flickityData.insert(tempEl.children, 0);
                        this.flickityData.selectCell(0, false, true);
                    }

                    if (this.$refs.postsList) {
                        const tableBodyEl = this.$refs.postsList.querySelector('tbody');
                        const appendNewPostsTo = tableBodyEl ? tableBodyEl : this.$refs.postsList;

                        appendNewPostsTo.innerHTML = data;
                    }

                    const nextPageUrl = tempEl.querySelector('[data-next-page-url]')?.getAttribute('data-next-page-url');

                    if (nextPageUrl) {
                        this.$refs.loadMoreTrigger?.setAttribute('href', nextPageUrl);
                    } else {
                        this.allPostsLoaded = true;
                    }
                },

                async sortPosts(sortBy, sortDirection = 'toggle') {
                    if (!sortBy || !sortDirection) {
                        return toastr.error('{{ __('Please provide all sort options.') }}')
                    }

                    this.sort.sortBy = sortBy;

                    if (sortDirection === 'toggle') {
                        this.sort.sortDirection =
                            (this.sort.sortBy !== sortBy || this.sort.sortDirection === 'asc') ?
                            'desc' :
                            'asc';
                    } else if (sortDirection === 'desc' || sortDirection === 'asc') {
                        this.sort.sortDirection = sortDirection;
                    }

                    await this.filterPosts()
                },

                updateCounters(pendingPostsCount, scheduledPostsCount, totalPostsCount = null) {
                    this.pendingPostsCount = pendingPostsCount;
                    this.scheduledPostsCount = scheduledPostsCount;
                    if (typeof totalPostsCount === 'number') {
                        this.totalPostsCount = totalPostsCount;
                    }

                    if (this.pendingCounterEl) {
                        Alpine.$data(this.pendingCounterEl).updateValue({
                            value: this.pendingPostsCount
                        });
                    }

                    if (this.scheduledCounterEl) {
                        Alpine.$data(this.scheduledCounterEl).updateValue({
                            value: this.scheduledPostsCount
                        });
                    }
                },
            }))
        })
    })();
</script>
