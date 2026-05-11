import { openRuntimeDb, setMeta, withStore } from './db.js';

const endpointFromMeta = (name, fallback) => document.querySelector(`meta[name="${name}"]`)?.getAttribute('content') || fallback;
const runtimeVersion = () => document.querySelector('meta[name="titan-runtime-version"]')?.getAttribute('content') || 'pass14';
const BOOTSTRAP_ENDPOINT = endpointFromMeta('titan-zero-bootstrap', '/dashboard/user/titanzero/api/pwa/bootstrap');
const HANDSHAKE_ENDPOINT = endpointFromMeta('titan-zero-handshake', '/dashboard/user/titanzero/api/pwa/handshake');

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function buildNodeId() {
  return `pwa_${navigator.platform || 'web'}_${crypto.randomUUID()}`.replace(/[^a-zA-Z0-9_\-.]/g, '');
}

export async function getMeta(key) {
  const records = await withStore('tz_runtime_meta', 'readonly', (store) => store.get(key));
  return records?.result?.value || null;
}

export async function bootstrapRuntime() {
  await openRuntimeDb();

  let node = await getMeta('node_identity');
  if (!node?.node_id) {
    node = { node_id: buildNodeId() };
    await setMeta('node_identity', node);
  }

  const bootstrapResponse = await fetch(BOOTSTRAP_ENDPOINT, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken(),
    },
    body: JSON.stringify({ node_id: node.node_id }),
  });

  if (!bootstrapResponse.ok) {
    throw new Error(`Titan runtime bootstrap failed with status ${bootstrapResponse.status}`);
  }

  const payload = await bootstrapResponse.json();
  await setMeta('bootstrap', payload);

  if (!node.node_secret) {
    const handshakeResponse = await fetch(HANDSHAKE_ENDPOINT, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken(),
      },
      body: JSON.stringify({
        node_id: node.node_id,
        node_origin: 'pwa',
        trust_level: 'standard',
        device_label: navigator.userAgent,
        platform: navigator.platform || 'web',
        app_version: runtimeVersion(),
      }),
    });

    if (!handshakeResponse.ok) {
      throw new Error(`Titan runtime handshake failed with status ${handshakeResponse.status}`);
    }

    const handshake = await handshakeResponse.json();
    node = {
      node_id: handshake.node_id,
      node_secret: handshake.node_secret,
      trust_level: handshake.trust_level,
    };
    await setMeta('node_identity', node);
    await setMeta('handshake', handshake);
  }

  return payload;
}

export async function registerServiceWorker() {
  if (!('serviceWorker' in navigator)) {
    return { ok: false, reason: 'unsupported' };
  }

  const serviceWorkerUrl = endpointFromMeta('titan-runtime-service-worker', '/pwa-runtime/sw.js');
  const registration = await navigator.serviceWorker.register(serviceWorkerUrl, { scope: '/' });
  return { ok: true, registration };
}


export async function runtimeSnapshot() {
  return {
    bootstrap: await getMeta('bootstrap'),
    node: await getMeta('node_identity'),
    handshake: await getMeta('handshake'),
  };
}
