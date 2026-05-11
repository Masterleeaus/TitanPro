{{-- This is the frontend ui --}}
@vite('app/Extensions/TitanOperator/resources/assets/voice/scss/external-titan_operator-voice.scss')
@vite('resources/views/default/js/voiceTitanOperator.js')

<div
    class="lqd-ext-titan_operator-voice"
    frame-x-pos="{{ $titan_operator->position }}"
    x-data="elevenLabsConversationalAI('{{ $titan_operator->agent_id }}', '{{ $titan_operator->uuid }}')"
>
    @if (!$titan_operator['active'])
        <p>
            @lang('This titan_operator is not active.')
        </p>
    @else
        <div class="icon">
            <img
                id="lqd-ext-titan_operator-voice-vis-img"
                src="{{ asset($titan_operator->avatar) }}"
                alt="{{ $titan_operator->title }}}"
            >
        </div>
        <div class="lqd-ext-titan_operator-voice-trigger">
            <p id="lqd-ext-titan_operator-voice-bot-status">{{ $titan_operator->bubble_message ?? __('Need help?') }}</p>
            <button
                class="lqd-ext-titan_operator-voice-trigger-btn"
                id="lqd-ext-titan_operator-voice-start-btn"
            >
                <svg
                    viewBox="0 0 640 640"
                    xmlns="http://www.w3.org/2000/svg"
                    height="1em"
                    width="1em"
                    stroke="currentColor"
                    fill="currentColor"
                >
                    <path
                        d="M 82.6 88.6 l 104 -24 c 11.3 -2.6 22.9 3.3 27.5 13.9 l 48 112 c 4.2 9.8 1.4 21.3 -6.9 28 l -60.6 49.6 c 36 76.7 98.9 140.5 177.2 177.2 l 49.6 -60.6 c 6.8 -8.3 18.2 -11.1 28 -6.9 l 112 48 C 572.1 430.5 578 442.1 575.4 453.4 l -24 104 C 548.9 568.2 539.3 576 528 576 c -256.1 0 -464 -207.5 -464 -464 c 0 -11.2 7.7 -20.9 18.6 -23.4 z"
                    ></path>
                </svg>
                <span>{{ __('Voice Chat') }}</span>
            </button>
            <button
                class="lqd-ext-titan_operator-voice-trigger-btn"
                id="lqd-ext-titan_operator-voice-end-btn"
            >
                <svg
                    viewBox="0 0 640 640"
                    xmlns="http://www.w3.org/2000/svg"
                    height="1em"
                    width="1em"
                    stroke="currentColor"
                    fill="currentColor"
                >
                    <path
                        d="M 371.8 445.4 l 49.6 -60.6 c 6.8 -8.3 18.2 -11.1 28 -6.9 l 112 48 c 10.7 4.6 16.5 16.1 13.9 27.5 l -24 104 c -2.5 10.8 -12.1 18.6 -23.4 18.6 c -100.7 0 -193.7 -32.4 -269.7 -86.9 l 80 -61.8 c 10.9 6.5 22.1 12.7 33.6 18.1 z m -365.6 76.7 L 164.9 399.5 C 102.1 320.4 64 220.9 64 112 c 0 -11.2 7.7 -20.9 18.6 -23.4 l 104 -24 c 11.3 -2.6 22.9 3.3 27.5 13.9 l 48 112 c 4.2 9.8 1.4 21.3 -6.9 28 l -60.6 49.6 c 12.2 26.1 27.9 50.3 46 72.8 L 594.5 67.4 C 601.5 62 611.5 63.2 617 70.2 L 636.6 95.4 c 5.4 7 4.2 17 -2.8 22.4 l -588.4 454.7 c -7 5.4 -17 4.2 -22.5 -2.8 l -19.6 -25.3 c -5.4 -6.8 -4.1 -16.9 2.9 -22.3 z"
                    ></path>
                </svg>
                <span>{{ __('End Call') }}</span>
            </button>
        </div>
    @endif
</div>

<script>
    const extVoiceTitanOperatorServer = () => ({
        /**@type {HTMLElement} */
        wrapper: null,
        // init
        init() {
            this.wrapper = document.querySelector('.lqd-ext-titan_operator-voice');

            this.addEvenListenter();
        },
        // add event listeners
        addEvenListenter() {

            if (window.self !== window.top) {
                window.addEventListener("message", (event) => this.handleWindowMessages(event));
            }
        },
        // Handle windows post messages
        handleWindowMessages(event) {
            switch (event.data.type) {
                case 'lqd-ext-voice-titan_operator-request-styling':
                    this.handleStylingResponse(event);
                    break;
            }
        },
        // styling response
        handleStylingResponse(event) {
            const voiceTitanOperatorStyles = getComputedStyle(this.wrapper);
            const styles = {};
            const attrs = {};

            [
                '--lqd-ext-voice-font-family',
                '--lqd-ext-voice-background',
                '--lqd-ext-voice-primary',
                '--lqd-ext-voice-border',
                '--lqd-ext-voice-window-w',
                '--lqd-ext-voice-window-h',
                '--lqd-ext-voice-box-radius',
                '--lqd-ext-voice-offset-y',
                '--lqd-ext-voice-offset-x',
                '--lqd-ext-voice-trigger-padding',
                '--lqd-ext-voice-trigger-padding-inline',
                '--lqd-ext-voice-trigger-radius'
            ].forEach(attr => {
                styles[attr] = voiceTitanOperatorStyles.getPropertyValue(attr) || '';
            })
            attrs['frame-x-pos'] = this.wrapper.getAttribute('frame-x-pos')

            event.source.postMessage({
                type: 'lqd-ext-voice-titan_operator-response-styling',
                data: {
                    styles,
                    attrs
                }
            }, event.origin);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        extVoiceTitanOperatorServer().init();
    });
</script>
