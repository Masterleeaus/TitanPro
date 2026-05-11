(function () {
    const surfaceRoot = document.querySelector('[data-runtime-surface]');
    if (!surfaceRoot) return;

    const key = surfaceRoot.getAttribute('data-runtime-surface');

    fetch(`/api/titan-runtime/surfaces/${key}`, {
        headers: { 'Accept': 'application/json' }
    })
        .then(r => r.json())
        .then(payload => {
            const hub = payload.boot?.hub || 'work';
            const node = payload.boot?.node?.node_key || 'local-web-node';

            const hubEl = surfaceRoot.querySelector('[data-runtime-meta="hub"]');
            const nodeEl = surfaceRoot.querySelector('[data-runtime-meta="node"]');

            if (hubEl) hubEl.textContent = hub;
            if (nodeEl) nodeEl.textContent = node;

            surfaceRoot.dispatchEvent(new CustomEvent('titan-runtime-surface:booted', {
                detail: payload
            }));
        })
        .catch(() => {});
})();
