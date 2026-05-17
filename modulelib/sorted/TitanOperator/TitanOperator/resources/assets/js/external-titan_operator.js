(async function () {
	const scriptTag = document.currentScript;
	const url = new URL(scriptTag.getAttribute('src'));
	const iframeWidth = scriptTag.getAttribute('data-iframe-width');
	const iframeHeight = scriptTag.getAttribute('data-iframe-height');
	let language = scriptTag.getAttribute('data-language') ?? 'en';
	const operatorHostOrigin = `${url.origin}`;
	const operatorBotUuid = scriptTag.getAttribute('data-titan_operator-uuid');
	const iFrameUrl = `${operatorHostOrigin}/titan_operator/${operatorBotUuid}/frame`;
	const jsonUrl = `${operatorHostOrigin}/api/v2/titan_operator/${operatorBotUuid}`;

	if (document.querySelector('html')?.getAttribute('lang')) {

		const htmlLang = document.querySelector('html').getAttribute('lang');

		if (htmlLang) {
			language = htmlLang;
		}
	}

	let config = {
		active: false,
		color: '#763ed1',
		trigger_avatar_size: '60px',
		avatar: null,
		trigger_background: ''
	};

	const getTitanOperatorDetails = async () => {
		if (operatorBotUuid) {
			try {
				const response = await fetch(`${jsonUrl}?language=${language}`);
				const data = await response.json();
				const titan_operator = data?.data || {};

				config = {
					...config,
					...titan_operator
				};
			} catch (error) {
				console.error('Failed to fetch titan_operator details:', error);
				return null;
			}
		}
		return null;
	};

	await getTitanOperatorDetails();

	if (!iFrameUrl) {
		console.error('Iframe source is not set');
		return;
	}

	const widgetMarkup = `
<div id="lqd-ext-titan_operator-wrap" data-ready="false" data-window-open="false">
    <style>
        #lqd-ext-titan_operator-wrap {
            --lqd-ext-chat-trigger-background: ${config.trigger_background && config.trigger_background !== '' ? config.trigger_background : 'var(--lqd-ext-chat-primary)'};
            --lqd-ext-chat-trigger-foreground: ${config.trigger_foreground && config.trigger_foreground !== '' ? config.trigger_foreground : 'var(--lqd-ext-chat-primary-foreground)'};
            display: flex;
            flex-direction: column;
            gap: var(--lqd-ext-chat-window-y-offset, 20px);
            position: fixed;
            bottom: var(--lqd-ext-chat-offset-y, 30px);
            left: var(--lqd-ext-chat-offset-y, 30px);
            z-index: 9999;
            transition: transform 0.3s, opacity 0.3s, visibility 0.3s;
            font-family: var(--lqd-ext-chat-font-family, 'inherit');
            pointer-events: none;
        }

        #lqd-ext-titan_operator-wrap .lqd-ext-titan_operator-trigger {
            display: inline-grid;
            place-items: center;
            place-content: center;
            width: var(--lqd-ext-chat-trigger-w);
            height: var(--lqd-ext-chat-trigger-h);
            padding: 0;
            position: relative;
            background-color: var(--lqd-ext-chat-trigger-background);
            color: var(--lqd-ext-chat-trigger-foreground);
            border-radius: var(--lqd-ext-chat-trigger-w);
            border: none;
            overflow: hidden;
            transition: all 0.15s;
            cursor: pointer;
            backdrop-filter: blur(12px) saturate(120%);
            pointer-events: auto;
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
        }
        #lqd-ext-titan_operator-wrap .lqd-ext-titan_operator-trigger:before {
            content: '';
            display: inline-block;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: var(--lqd-ext-chat-primary);
            opacity: 0;
            transform: translateY(3px);
            transition: all 0.15s;
        }
        #lqd-ext-titan_operator-wrap .lqd-ext-titan_operator-trigger-mobile {
            display: inline-grid;
			place-items: center;
			width: 24px;
			height: 24px;
			position: fixed;
			top: 17px;
			inset-inline-end: 16px;
			z-index: 999991;
			visibility: hidden;
			opacity: 0;
			border-radius: 20px;
			background-color: hsl(0 0% 0% / 35%);
			color: #fff;
			backdrop-filter: blur(6px);
			transition: all 0.3s;
        }
        #lqd-ext-titan_operator-wrap .lqd-ext-titan_operator-trigger-mobile:before {
			content: none;
		}
        #lqd-ext-titan_operator-wrap #lqd-ext-titan_operator-trigger-img,
        #lqd-ext-titan_operator-wrap #lqd-ext-titan_operator-trigger-icon {
            grid-row: 1 / 1;
            grid-column: 1 / 1;
            transition: all 0.15s;
        }
        #lqd-ext-titan_operator-wrap #lqd-ext-titan_operator-trigger-img {
            width: ${config.trigger_avatar_size ? `${parseInt(config.trigger_avatar_size, 10) }px` : '100%'};
            height: auto;
            max-width: none;
            position: relative;
            z-index: 1;
        }
        #lqd-ext-titan_operator-wrap #lqd-ext-titan_operator-trigger-icon {
            opacity: 0;
            transform: translateY(3px);
        }
        #lqd-ext-titan_operator-wrap .lqd-ext-titan_operator-trigger:active {
            transform: scale(0.9);
        }

        #lqd-ext-titan_operator-iframe-wrap {
            width: min(var(--lqd-ext-chat-window-w), calc(100vw - (var(--lqd-ext-chat-offset-x) * 2)));
            height: min(var(--lqd-ext-chat-window-h), calc(100vh - (var(--lqd-ext-chat-offset-y) * 2) - var(--lqd-ext-chat-trigger-h) - var(--lqd-ext-chat-window-y-offset)));
            box-shadow: 0 5px 40px hsl(0 0% 0% / 16%);
            border-radius: 12px;
            pointer-events: none;
            transform-origin: bottom left;
            transform: scale(0.975) translateY(6px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.1s;
        }

        #lqd-ext-titan_operator-iframe {
            width: 100%;
            height: 100%;
        }

        #lqd-ext-titan_operator-trigger-bubble {
            padding: 12px 16px;
            border-radius: 12px;
            position: absolute;
            bottom: calc(var(--lqd-ext-chat-trigger-h) + var(--lqd-ext-chat-window-y-offset));
            left: 0;
            color: var(--lqd-ext-chat-primary);
            font-size: 14px;
            font-weight: 500;
            line-height: 1.2em;
            backdrop-filter: blur(12px) saturate(120%) brightness(1.75);
            opacity: 0;
            visibility: hidden;
            transform: translateY(6px);
            transition: all 0.15s;
        }
         #lqd-ext-titan_operator-trigger-bubble:before {
            content: '';
            display: inline-block;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            background-color: var(--lqd-ext-chat-primary);
            opacity: 0.05;
            border-radius: inherit;
        }
        #lqd-ext-titan_operator-trigger-bubble p {
            position: relative;
            z-index: 1;
            margin: 0;
        }

        .lqd-ext-titan_operator-not-loaded {
            margin: 0;
            padding: 1rem;
        }

        #lqd-ext-titan_operator-wrap[data-ready=true] .lqd-ext-titan_operator-trigger:not(.lqd-ext-titan_operator-trigger-mobile),
        #lqd-ext-titan_operator-wrap[data-ready=true] #lqd-ext-titan_operator-trigger-bubble {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        #lqd-ext-titan_operator-wrap[data-window-state=open] #lqd-ext-titan_operator-iframe-wrap {
            transform: translateY(0) scale(1);
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        #lqd-ext-titan_operator-wrap[data-window-state=open] .lqd-ext-titan_operator-trigger:before {
            transform: translateY(0);
            opacity: 1;
        }
        #lqd-ext-titan_operator-wrap[data-window-state=open] #lqd-ext-titan_operator-trigger-icon {
            opacity: 1;
            transform: translateY(0);
        }
        #lqd-ext-titan_operator-wrap[data-window-state=open] #lqd-ext-titan_operator-trigger-img {
            opacity: 0;
            transform: translateY(-3px);
        }
        #lqd-ext-titan_operator-wrap[data-window-state=open] #lqd-ext-titan_operator-trigger-bubble {
            transform: scale(0.95);
            opacity: 0;
            visibility: hidden;
        }

        html[dir=rtl] #lqd-ext-titan_operator-wrap[data-pos-x=left] {
            align-items: end;
        }

        #lqd-ext-titan_operator-wrap[data-pos-x=right] {
            left: auto;
            right: var(--lqd-ext-chat-offset-x, 30px);
            align-items: end;
        }

        #lqd-ext-titan_operator-wrap[data-pos-x=right] #lqd-ext-titan_operator-iframe-wrap {
            transform-origin: bottom right;
        }

        #lqd-ext-titan_operator-wrap[data-pos-x=right] #lqd-ext-titan_operator-trigger-bubble {
            left: auto;
            right: 0;
        }

		html[dir=rtl] #lqd-ext-titan_operator-wrap[data-pos-x=right] {
            align-items: start;
        }

        #lqd-ext-titan_operator-wrap[data-pos-y=top] {
            bottom: auto;
            top: var(--lqd-ext-chat-offset-y, 30px);
            flex-direction: column-reverse;
        }

        #lqd-ext-titan_operator-wrap[data-pos-y=top] #lqd-ext-titan_operator-trigger-bubble {
            bottom: auto;
            top: calc(var(--lqd-ext-chat-trigger-h) + var(--lqd-ext-chat-window-y-offset));
        }

		@media (max-width: 768px) {
			#lqd-ext-titan_operator-wrap {
				width: calc(100vw - (var(--lqd-ext-chat-offset-x) * 2));
				pointer-events: none;
			}
			#lqd-ext-titan_operator-wrap .lqd-ext-titan_operator-iframe-wrap,
			#lqd-ext-titan_operator-wrap .lqd-ext-titan_operator-trigger {
				pointer-events: auto;
			}
			#lqd-ext-titan_operator-iframe-wrap {
				position: fixed;
				z-index: 99999;
				width: 100vw !important;
				height: 100vh !important;
				left: 0 !important;
				right: 0 !important;
				bottom: 0 !important;
				top: 0 !important;
			}
			#lqd-ext-titan_operator-wrap[data-window-state="open"] .lqd-ext-titan_operator-trigger-mobile {
				opacity: 1;
				visibility: visible;
			}
		}
    </style>
    <div id="lqd-ext-titan_operator-iframe-wrap">
        ${iFrameUrl ? `
            <iframe
                src="${iFrameUrl}"
                title="${config.title}"
                frameborder="0"
                allowfullscreen
                allowtransparency
                id="lqd-ext-titan_operator-iframe"
                name="lqd-ext-titan_operator-iframe"
                crossOrigin="anonymous"
                onload="
                    const wrapper = document.querySelector('#lqd-ext-titan_operator-wrap');
                    window.addEventListener('message', event => {
                        if ( event.origin !== '${operatorHostOrigin}' || event.data.type !== 'lqd-ext-titan_operator-response-styling' || !wrapper ) return;
                        const { styles, attrs } = event.data.data;
                        Object.entries(styles).forEach(([key, value]) => {
                            if ( key === '--lqd-ext-chat-window-w' && ${iframeWidth ? true : false} ) {
                                return wrapper.style.setProperty(key, '${parseInt(iframeWidth, 10)}px');
                            } else if ( key === '--lqd-ext-chat-window-h' && ${iframeHeight ? true : false} ) {
                                return wrapper.style.setProperty(key, '${parseInt(iframeHeight, 10)}px');
                            }
                            wrapper.style.setProperty(key, value);
                        });
                        Object.entries(attrs).forEach(([key, value]) => {
                            wrapper.setAttribute(key, value);
                        });
                        wrapper.setAttribute('data-ready', 'true');
                    });

                    this.contentWindow.postMessage({
                        type: 'lqd-ext-titan_operator-request-styling',
                    }, '${operatorHostOrigin}');
                "
            ></iframe>` : `
        <p class="lqd-ext-titan_operator-not-loaded">Could not setup the titan_operator</p>
        `}
    </div>
    ${config.bubble_message && config.bubble_message !== '' ?
		`<div id="lqd-ext-titan_operator-trigger-bubble">
                <p>
                    ${config.bubble_message}
                </p>
            </div>`
		:
		''
	}
    <button
        class="lqd-ext-titan_operator-trigger"
        type="button"
    >
        <img
            id="lqd-ext-titan_operator-trigger-img"
            src="${operatorHostOrigin}${config.avatar}"
            alt="${config.title}"
            width="60"
            height="60"
        />
        <span id="lqd-ext-titan_operator-trigger-icon">
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

    <button
        class="lqd-ext-titan_operator-trigger lqd-ext-titan_operator-trigger-mobile"
        type="button"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" > <path d="M18 6l-12 12" /> <path d="M6 6l12 12" /> </svg>
    </button>
</div>`;

	document.body.insertAdjacentHTML('beforeend', widgetMarkup);

	const operatorWrap = document.querySelector('#lqd-ext-titan_operator-wrap');
	const triggers = document.querySelectorAll('.lqd-ext-titan_operator-trigger');
	let open = false;

	triggers.forEach(trigger => {
		trigger.addEventListener('click', ev => {
			ev.preventDefault();
			open = !open;
			operatorWrap.setAttribute('data-window-state', open ? 'open' : 'close');
		});
	});
})();
