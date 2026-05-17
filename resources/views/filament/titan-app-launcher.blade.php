{{-- Titan App Launcher skin for the Filament panel switcher. --}}
<style id="titan-app-launcher-css">
    :root {
        --titan-launcher-bg: radial-gradient(circle at top, rgba(30, 41, 59, .72), rgba(2, 6, 23, .96) 58%, rgba(0, 0, 0, .98));
    }

    .titan-app-launcher-surface {
        background: var(--titan-launcher-bg) !important;
        border: 1px solid rgba(148, 163, 184, .16) !important;
        box-shadow: 0 28px 90px rgba(0, 0, 0, .56) !important;
    }

    .titan-app-launcher-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fit, minmax(11rem, 1fr)) !important;
        gap: 1.5rem !important;
        width: min(100%, 80rem) !important;
        margin-inline: auto !important;
    }

    .titan-app-launcher-card {
        min-height: 13.25rem !important;
        border-radius: 1.1rem !important;
        color: #fff !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        padding: 1.25rem !important;
        border: 1px solid rgba(255, 255, 255, .16) !important;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.18), 0 16px 32px rgba(0,0,0,.28) !important;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease !important;
    }

    .titan-app-launcher-card:hover,
    .titan-app-launcher-card:focus-visible {
        transform: translateY(-3px) scale(1.015) !important;
        border-color: rgba(255, 255, 255, .38) !important;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.26), 0 22px 44px rgba(0,0,0,.36), 0 0 0 4px rgba(59,130,246,.18) !important;
    }

    .titan-app-launcher-icon {
        width: 3.8rem !important;
        height: 3.8rem !important;
        margin-bottom: 1.05rem !important;
        color: #fff !important;
        filter: drop-shadow(0 8px 18px rgba(0,0,0,.25));
    }

    .titan-app-launcher-title {
        font-weight: 800 !important;
        font-size: 1.05rem !important;
        letter-spacing: .065em !important;
        text-transform: uppercase !important;
        color: #fff !important;
        margin: 0 !important;
    }

    .titan-app-launcher-description {
        margin-top: .5rem !important;
        font-size: .88rem !important;
        line-height: 1.25rem !important;
        color: rgba(255,255,255,.9) !important;
    }
</style>
<script>
    window.TitanAppLauncher = @json(config('titan_panels.launcher_apps', []));

    (function () {
        const iconSvg = {
            shield: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l7 3v5c0 5-3.4 8.7-7 10-3.6-1.3-7-5-7-10V6l7-3z"/></svg>',
            leaf: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 4c-8.5 0-14 4.8-14 12 0 2.2 1.8 4 4 4 7.2 0 10-7.5 10-16z"/><path d="M6 18c3-5 7-8 12-10"/></svg>',
            quote: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.4 6C5.9 7.5 4.5 9.7 4.5 12.7V18h6.1v-6H7.7c.1-1.4.9-2.6 2.4-3.6L8.4 6zm9 0c-2.5 1.5-3.9 3.7-3.9 6.7V18h6.1v-6h-2.9c.1-1.4.9-2.6 2.4-3.6L17.4 6z"/></svg>',
            card: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 9h18"/><path d="M7 15h.01M11 15h2"/></svg>',
            cpu: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="7" y="7" width="10" height="10" rx="2"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"/><path d="M10 12h4"/></svg>',
            truck: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 6h12v10H3z"/><path d="M15 10h3l3 3v3h-6z"/><circle cx="7" cy="18" r="2"/><circle cx="18" cy="18" r="2"/></svg>',
            headset: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 13a8 8 0 0116 0v4a2 2 0 01-2 2h-2"/><path d="M6 13h2v5H6a2 2 0 01-2-2v-1a2 2 0 012-2zm12 0h-2v5h2a2 2 0 002-2v-1a2 2 0 00-2-2z"/><path d="M12 19h4"/></svg>',
            megaphone: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 13h4l10 5V6L8 11H4v2z"/><path d="M8 13l2 6"/><path d="M19 9l2-2M20 13h3M19 17l2 2"/></svg>',
            chart: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M7 16v-4m4 4V8m4 8v-6"/><path d="M8 7l4-3 4 3 4-5"/></svg>',
            lock: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/><path d="M12 14v3"/></svg>',
            money: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 6v12M15.5 8.5c-.8-.9-2-1.3-3.5-1.3-2 0-3.2.9-3.2 2.2 0 3.2 6.4 1.6 6.4 5 0 1.4-1.2 2.4-3.4 2.4-1.5 0-2.9-.5-3.8-1.6"/></svg>',
            pixel: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h7v7H4V4zm9 0h3v3h-3V4zm4 3h3v3h-3V7zM4 13h3v3H4v-3zm5 0h7v7H9v-7zm9 4h2v3h-2v-3z"/></svg>',
            team: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3"/><path d="M5 20a7 7 0 0114 0"/><circle cx="5" cy="10" r="2"/><circle cx="19" cy="10" r="2"/></svg>',
            warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l10 18H2L12 3z"/><path d="M12 9v5M12 17h.01"/></svg>',
            social: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="6" cy="12" r="3"/><circle cx="18" cy="6" r="3"/><circle cx="18" cy="18" r="3"/><path d="M8.7 10.7l6.6-3.4M8.7 13.3l6.6 3.4"/></svg>'
        };

        const apps = window.TitanAppLauncher || [];
        const normalise = value => (value || '').toString().replace(/\s+/g, '').toLowerCase();

        function existingLinks() {
            const links = new Map();
            document.querySelectorAll('a[href]').forEach(anchor => {
                const key = normalise(anchor.textContent);
                if (key && ! links.has(key)) links.set(key, anchor.href);
            });
            return links;
        }

        function locateSwitcher() {
            const headings = Array.from(document.querySelectorAll('h1,h2,h3,[role="heading"],.fi-modal-heading'));
            const heading = headings.find(el => /switch\s*panels|app\s*launcher/i.test(el.textContent || ''));
            if (! heading) return null;
            return heading.closest('[role="dialog"], .fi-modal-window, .fi-modal, section, div');
        }

        function renderLauncher(root) {
            if (! root || root.dataset.titanAppLauncherReady === '1' || ! apps.length) return;
            root.dataset.titanAppLauncherReady = '1';
            root.classList.add('titan-app-launcher-surface');

            const links = existingLinks();
            const closeButton = root.querySelector('button[aria-label="Close"], button[title="Close"]');
            const cards = apps.map(app => {
                const href = links.get(normalise(app.label)) || app.url || ('/' + app.path);
                const icon = iconSvg[app.icon_key] || iconSvg.shield;
                return `<a class="titan-app-launcher-card" href="${href}" style="background:${app.gradient}">
                    <span class="titan-app-launcher-icon">${icon}</span>
                    <span class="titan-app-launcher-title">${app.label}</span>
                    <span class="titan-app-launcher-description">${app.description}</span>
                </a>`;
            }).join('');

            root.innerHTML = `
                ${closeButton ? closeButton.outerHTML : ''}
                <div style="text-align:center;margin:1rem auto 2rem;color:#fff">
                    <div style="width:2.25rem;height:2.25rem;margin:0 auto .65rem;color:#fff">${iconSvg.pixel}</div>
                    <h2 style="margin:0;font-size:clamp(2rem,4vw,3.5rem);font-weight:900;letter-spacing:.12em;text-transform:uppercase;color:#fff">APP LAUNCHER</h2>
                    <p style="margin:.55rem 0 0;color:rgba(255,255,255,.86);font-size:1.05rem">Switch between your apps</p>
                </div>
                <div class="titan-app-launcher-grid">${cards}</div>
            `;
        }

        function bootLauncher() {
            renderLauncher(locateSwitcher());
        }

        document.addEventListener('DOMContentLoaded', bootLauncher);
        document.addEventListener('click', () => setTimeout(bootLauncher, 80), true);
        new MutationObserver(bootLauncher).observe(document.documentElement, { childList: true, subtree: true });
    })();
</script>
