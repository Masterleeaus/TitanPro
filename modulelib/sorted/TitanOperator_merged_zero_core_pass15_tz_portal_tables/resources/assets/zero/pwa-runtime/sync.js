import { getQueuedEvents } from './signalQueue.js';
import { getAll, remove, setMeta, upsert } from './db.js';
import { getMeta } from './runtime.js';
import { signPayload } from './signature.js';
import { syncQueuedMedia } from './blobSync.js';

const endpointFromMeta = (name, fallback) => document.querySelector(`meta[name="${name}"]`)?.getAttribute('content') || fallback;
const runtimeVersion = () => document.querySelector('meta[name="titan-runtime-version"]')?.getAttribute('content') || 'pass14';
const SYNC_ENDPOINT = endpointFromMeta('titan-zero-sync', '/dashboard/user/titanzero/api/signals/ingest');

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

export async function syncQueuedEvents() {
  const events = await getQueuedEvents();
  const localSignals = await getAll('tz_local_signals');
  const node = await getMeta('node_identity');

  if (!events.length) {
    await setMeta('last_sync_result', { accepted: 0, synced_at: new Date().toISOString() });
    return { accepted: 0, events: [] };
  }

  const signals = events.map((event) => {
    const local = localSignals.find((candidate) => candidate.id === event.signal_id);
    return {
      node_id: node?.node_id || null,
      signal_key: event.signal_key,
      signal_stage: local?.signal_stage || 'signal',
      signal_status: 'pending',
      payload: event.payload || {},
      client_created_at: event.created_at,
      timestamp: local?.timestamp || event.created_at,
    };
  });

  const body = JSON.stringify({
    node_id: node?.node_id || null,
    app_version: runtimeVersion(),
    signals,
  });
  const timestamp = new Date().toISOString();
  const signature = await signPayload(body, timestamp, node?.node_secret || '');

  const response = await fetch(SYNC_ENDPOINT, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken(),
      'Accept': 'application/json',
      'X-Titan-Node-Id': node?.node_id || '',
      'X-Titan-Timestamp': timestamp,
      'X-Titan-Signature': signature,
    },
    body,
  });

  if (!response.ok) {
    throw new Error(`Titan runtime sync failed with status ${response.status}`);
  }

  const data = await response.json();

  for (const event of events) {
    await remove('tz_sync_queue', event.id);
  }

  for (const signal of signals) {
    await upsert('tz_local_signals', {
      ...localSignals.find((candidate) => candidate.signal_key === signal.signal_key && candidate.timestamp === signal.timestamp),
      id: localSignals.find((candidate) => candidate.signal_key === signal.signal_key && candidate.timestamp === signal.timestamp)?.id,
      signal_key: signal.signal_key,
      payload: signal.payload,
      signal_stage: signal.signal_stage,
      signal_status: 'synced',
      timestamp: signal.timestamp,
      synced_at: new Date().toISOString(),
    });
  }

  await upsert('tz_runtime_meta', {
    key: 'last_sync_result',
    value: data,
    updated_at: new Date().toISOString(),
  });

  window.dispatchEvent(new CustomEvent('titan:pwa:synced', { detail: data }));

  return data;
}


export async function syncAllRuntimeQueues() {
  const signalResult = await syncQueuedEvents();
  const mediaResult = await syncQueuedMedia();
  return { signalResult, mediaResult };
}
