import { getAll, upsert } from './db.js';

function uuid(prefix = 'sig') {
  return `${prefix}_${crypto.randomUUID()}`;
}

export async function emitSignal(signalKey, payload = {}, entity = {}) {
  const signal = {
    id: uuid('signal'),
    signal_key: signalKey,
    payload,
    entity,
    node_id: entity.node_id || null,
    signal_stage: 'signal',
    signal_status: 'pending',
    timestamp: new Date().toISOString(),
    created_at: new Date().toISOString(),
    retry_count: 0,
  };

  await upsert('tz_local_signals', signal);
  await queueEvent(signal);

  return signal;
}

export async function queueEvent(signal) {
  const queued = {
    id: uuid('queue'),
    signal_id: signal.id,
    signal_key: signal.signal_key,
    payload: signal.payload,
    entity: signal.entity,
    created_at: signal.created_at,
    status: 'queued',
  };

  await upsert('tz_sync_queue', queued);
  window.dispatchEvent(new CustomEvent('titan:pwa:queue-updated', { detail: queued }));

  return queued;
}

export async function getQueuedEvents() {
  const events = await getAll('tz_sync_queue');
  return events.sort((a, b) => String(a.created_at).localeCompare(String(b.created_at)));
}
