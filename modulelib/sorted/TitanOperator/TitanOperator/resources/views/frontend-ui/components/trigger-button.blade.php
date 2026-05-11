@if (!$is_iframe || $is_editor)
    <button
        class="lqd-ext-titan_operator-trigger"
        type="button"
        @click.prevent="toggleWindowState()"
        {{-- blade-formatter-disable --}}
        @if (!$is_editor && @filled($titan_operator['trigger_background']))
			style="background-color: {{ $titan_operator['trigger_background'] }}"
		@endif
		{{-- blade-formatter-enable --}}
    >
        <img
            class="lqd-ext-titan_operator-trigger-img"
            {{-- blade-formatter-disable --}}
            @if ($is_editor)
				:src="() => activeTitanOperator.avatar ? `${window.location.origin}/${activeTitanOperator.avatar}` : ''"
                :style="{ 'width': parseInt(activeTitanOperator.trigger_avatar_size, 10) + 'px' }"
            @else
                src="/{{ $titan_operator['avatar'] }}"
                alt="{{ $titan_operator['title'] }}"
                @if (!empty($titan_operator['trigger_avatar_size']))
                    style="width: {{ (int) $titan_operator['trigger_avatar_size'] }}px"
				@endif
            @endif
			{{-- blade-formatter-enable --}}
            width="60"
            height="60"
        />
        <span class="lqd-ext-titan_operator-trigger-icon">
            <svg
                width="16"
                height="10"
                viewBox="0 0 16 10"
                fill="currentColor"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path d="M8 9.07814L0.75 1.82814L2.44167 0.136475L8 5.69481L13.5583 0.136475L15.25 1.82814L8 9.07814Z" />
            </svg>
        </span>
    </button>
@endif
