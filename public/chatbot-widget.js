(function () {
    const script = document.currentScript;

    if (!script) return;

    const chatbotUuid = script.getAttribute('data-chatbot-uuid');

    if (!chatbotUuid) {
        console.error('chatbot-widget.js: data-chatbot-uuid is required');
        return;
    }

    const baseUrl = new URL(script.src, window.location.href).origin;
    const endpoint = `${baseUrl}/api/v2/chatbot/${encodeURIComponent(chatbotUuid)}`;

    const css = `
.titan-chatbot-widget{position:fixed;bottom:24px;z-index:9999;display:flex;flex-direction:column;gap:12px;max-width:320px;font-family:Inter,system-ui,-apple-system,sans-serif}
.titan-chatbot-widget[data-position="left"]{left:24px}.titan-chatbot-widget[data-position="right"]{right:24px}
.titan-chatbot-bubble{width:58px;height:58px;border-radius:999px;display:flex;align-items:center;justify-content:center;background:var(--trigger-bg,#2563eb);color:var(--trigger-fg,#fff);cursor:pointer;border:none;box-shadow:0 14px 28px rgba(0,0,0,.2);overflow:hidden}
.titan-chatbot-bubble--promo_banner{width:64px;border-radius:18px}.titan-chatbot-bubble--modern{background:linear-gradient(135deg,var(--trigger-bg,#2563eb),#1d4ed8)}.titan-chatbot-bubble--links{background:linear-gradient(135deg,var(--trigger-bg,#16a34a),#15803d)}.titan-chatbot-bubble--suggestions{background:linear-gradient(135deg,var(--trigger-bg,#9333ea),#7e22ce)}.titan-chatbot-bubble--blank{box-shadow:none;border:1px solid #d1d5db}
.titan-chatbot-bubble img{width:100%;height:100%;object-fit:cover}
.titan-chatbot-banner{position:relative;border-radius:14px;overflow:hidden;color:#fff;background:#0f172a;padding:14px;box-shadow:0 10px 24px rgba(0,0,0,.25)}
.titan-chatbot-banner__bg{position:absolute;inset:0;background-size:cover;background-position:center;opacity:.35}
.titan-chatbot-banner__body{position:relative}.titan-chatbot-banner__dismiss{position:absolute;top:6px;right:8px;border:0;background:transparent;color:#fff;font-size:16px;cursor:pointer}
.titan-chatbot-banner__cta{display:inline-block;margin-top:8px;padding:6px 12px;background:#fff;color:#111827;border-radius:999px;text-decoration:none;font-size:12px;font-weight:600}
`;

    const style = document.createElement('style');
    style.textContent = css;
    document.head.appendChild(style);

    fetch(endpoint)
        .then((res) => res.json())
        .then((payload) => {
            const data = payload?.data || {};
            const position = data.position || 'right';
            const design = data.bubble_design || 'modern';
            const promo = data.promo_banner || {};

            const wrap = document.createElement('div');
            wrap.className = 'titan-chatbot-widget';
            wrap.dataset.position = position;

            if (design === 'promo_banner' && sessionStorage.getItem(`chatbot-promo-dismissed:${chatbotUuid}`) !== '1') {
                const banner = document.createElement('div');
                banner.className = 'titan-chatbot-banner';
                if (promo.image) {
                    const bg = document.createElement('div');
                    bg.className = 'titan-chatbot-banner__bg';
                    bg.style.backgroundImage = `url('${promo.image}')`;
                    banner.appendChild(bg);
                }

                const dismissButton = document.createElement('button');
                dismissButton.className = 'titan-chatbot-banner__dismiss';
                dismissButton.type = 'button';
                dismissButton.setAttribute('aria-label', 'Dismiss');
                dismissButton.textContent = '×';
                banner.appendChild(dismissButton);

                const body = document.createElement('div');
                body.className = 'titan-chatbot-banner__body';

                if (promo.title) {
                    const titleWrap = document.createElement('div');
                    const strong = document.createElement('strong');
                    strong.textContent = promo.title;
                    titleWrap.appendChild(strong);
                    body.appendChild(titleWrap);
                }

                if (promo.description) {
                    const description = document.createElement('div');
                    description.textContent = promo.description;
                    body.appendChild(description);
                }

                if (promo.cta_label) {
                    const cta = document.createElement('a');
                    cta.className = 'titan-chatbot-banner__cta';
                    cta.target = '_blank';
                    cta.rel = 'noopener noreferrer';
                    cta.href = promo.cta_url || '#';
                    cta.textContent = promo.cta_label;
                    body.appendChild(cta);
                }

                banner.appendChild(body);

                dismissButton.addEventListener('click', () => {
                    sessionStorage.setItem(`chatbot-promo-dismissed:${chatbotUuid}`, '1');
                    banner.remove();
                });

                wrap.appendChild(banner);
            }

            const bubble = document.createElement('button');
            bubble.type = 'button';
            bubble.className = `titan-chatbot-bubble titan-chatbot-bubble--${design}`;
            bubble.style.setProperty('--trigger-bg', data.trigger_background || '#2563eb');
            bubble.style.setProperty('--trigger-fg', data.trigger_foreground || '#ffffff');
            bubble.title = data.title || 'Chat';

            if (data.avatar) {
                const avatar = document.createElement('img');
                avatar.src = data.avatar;
                avatar.alt = data.title || 'Chat';
                bubble.appendChild(avatar);
            } else {
                bubble.textContent = '💬';
            }

            wrap.appendChild(bubble);
            document.body.appendChild(wrap);
        })
        .catch((error) => {
            console.error('chatbot-widget.js: failed to initialize widget', error);
        });
})();
