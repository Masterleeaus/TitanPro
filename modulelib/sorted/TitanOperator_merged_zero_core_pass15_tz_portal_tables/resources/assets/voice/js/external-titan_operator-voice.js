( async function () {
	const scriptTag = document.currentScript;
	const url = new URL( scriptTag.getAttribute( 'src' ) );
	const operatorHostOrigin = `${ url.origin }`;
	const operatorBotUuid = scriptTag.getAttribute( 'data-titan_operator-uuid' );
	const iFrameUrl = `${ operatorHostOrigin }/titan_operator-voice/${ operatorBotUuid }/frame`;

	const widgetMarkup = `
<div id="lqd-ext-voice-titan_operator-wrapper">
	<style>
		#lqd-ext-voice-titan_operator-wrapper {
			position: fixed;
			display: flex;
			align-items: center;
			justify-content: center;
			width: var(--lqd-ext-voice-window-w);
			height: var(--lqd-ext-voice-window-h);
			bottom: var(--lqd-ext-voice-offset-y, 30px);
			left: var(--lqd-ext-voice-offset-x, 30px);
			border-radius: var(--lqd-ext-voice-box-radius);
			z-index: 99999;

			opacity: 0;
            visibility: hidden;
			transition: all 0.1s;
		}

		#lqd-ext-voice-titan_operator-wrapper[data-ready=true] {
			opacity: 1;
            visibility: visible;
		}

		#lqd-ext-voice-titan_operator-wrapper[frame-x-pos=right] {
			left: auto;
			right: var(--lqd-ext-voice-offset-x, 30px);
		}

		#lqd-ext-voice-titan_operator-iframe {
            width: 100%;
            height: 100%;
        }

		.lqd-ext-voice-titan_operator-not-loaded {
            margin: 0;
            padding: 1rem;
        }
	</style>
	${ iFrameUrl ? `
		<iframe
			src="${ iFrameUrl }"
			frameborder="0"
			allowfullscreen
			allowtransparency
			allow="microphone; camera"
			id="lqd-ext-voice-titan_operator-iframe"
			name="lqd-ext-voice-titan_operator-iframe"
			crossOrigin="anonymous"
			onload="
				const wrapper = document.getElementById('lqd-ext-voice-titan_operator-wrapper');
				window.addEventListener('message', event => {
					if ( event.origin !== '${ operatorHostOrigin }' || event.data.type !== 'lqd-ext-voice-titan_operator-response-styling' || !wrapper ) return;
					const { styles, attrs } = event.data.data;
					Object.entries(styles).forEach(([key, value]) => {
						wrapper.style.setProperty(key, value);
					});
					Object.entries(attrs).forEach(([key, value]) => {
						wrapper.setAttribute(key, value);
					});
					wrapper.setAttribute('data-ready', 'true');
				});

				this.contentWindow.postMessage({
					type: 'lqd-ext-voice-titan_operator-request-styling',
				}, '${ operatorHostOrigin }');
			"
		></iframe>` : `
	<p class="lqd-ext-voice-titan_operator-not-loaded">Could not setup the voice titan_operator</p>
	`}
</div>`;

	document.body.insertAdjacentHTML( 'beforeend', widgetMarkup );
} )();
