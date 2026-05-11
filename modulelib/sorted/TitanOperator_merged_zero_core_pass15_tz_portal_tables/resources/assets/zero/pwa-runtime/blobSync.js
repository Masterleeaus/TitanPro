import { getAll, upsert, remove } from './db.js';
import { getMeta } from './runtime.js';

const endpointFromMeta = (name, fallback) => document.querySelector(`meta[name="${name}"]`)?.getAttribute('content') || fallback;
const runtimeVersion = () => document.querySelector('meta[name="titan-runtime-version"]')?.getAttribute('content') || 'pass14';
const BLOB_SYNC_ENDPOINT = endpointFromMeta('titan-zero-blobs', '/dashboard/user/titanzero/api/pwa/blobs/ingest');

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

async function buildFormData(node, staged) {
  const form = new FormData();
  form.append('node_id', node?.node_id || '');
  form.append('app_version', runtimeVersion());

  staged.forEach((item, index) => {
    form.append(`files[${index}]`, item.blob, `${item.id}.jpg`);
    form.append(`meta[${index}][id]`, item.id);
    form.append(`meta[${index}][job_id]`, item.job_id || '');
    form.append(`meta[${index}][mime_type]`, item.mime_type || 'image/jpeg');
    form.append(`meta[${index}][captured_at]`, item.captured_at || new Date().toISOString());
    form.append(`meta[${index}][byte_size]`, String(item.byte_size || 0));
  });

  return form;
}

export async function syncQueuedMedia() {
  const staged = (await getAll('tz_blob_staging')).filter((item) => item.sync_status !== 'synced');
  const node = await getMeta('node_identity');

  if (!staged.length) {
    return { ok: true, accepted_count: 0, blobs: [] };
  }

  const form = await buildFormData(node, staged);
  const response = await fetch(BLOB_SYNC_ENDPOINT, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'X-CSRF-TOKEN': csrfToken(),
      'X-Titan-Node-Id': node?.node_id || '',
    },
    body: form,
  });

  if (!response.ok) {
    throw new Error(`Titan media sync failed with status ${response.status}`);
  }

  const data = await response.json();
  const accepted = new Set((data.accepted || []).map((item) => item.id));

  for (const item of staged) {
    if (accepted.has(item.id)) {
      await upsert('tz_blob_staging', { ...item, sync_status: 'synced', synced_at: new Date().toISOString(), blob: null });
      await remove('tz_media_queue', `queue_${item.id}`);
    }
  }

  window.dispatchEvent(new CustomEvent('titan:pwa:media-synced', { detail: data }));
  return data;
}
