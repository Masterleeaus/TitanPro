import { upsert } from './db.js';

function uuid() {
  return `media_${Date.now()}_${Math.random().toString(16).slice(2)}`;
}

export async function capturePhoto({ jobId = null, source = 'camera' } = {}) {
  const stream = await navigator.mediaDevices.getUserMedia({
    video: { facingMode: { ideal: 'environment' } },
    audio: false,
  });

  try {
    const video = document.createElement('video');
    video.srcObject = stream;
    video.playsInline = true;
    await video.play();
    await new Promise((resolve) => setTimeout(resolve, 350));

    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth || 1280;
    canvas.height = video.videoHeight || 720;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', 0.82));
    const id = uuid();
    const record = {
      id,
      job_id: jobId,
      source,
      mime_type: 'image/jpeg',
      blob,
      captured_at: new Date().toISOString(),
      sync_status: 'queued',
      byte_size: blob?.size || 0,
    };

    await upsert('tz_blob_staging', record);
    await upsert('tz_media_queue', {
      id: `queue_${id}`,
      blob_id: id,
      type: 'photo',
      status: 'pending',
      created_at: new Date().toISOString(),
    });

    window.dispatchEvent(new CustomEvent('titan:pwa:media-staged', { detail: record }));
    return record;
  } finally {
    stream.getTracks().forEach((track) => track.stop());
  }
}
