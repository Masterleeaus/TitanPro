import { bootstrapRuntime, registerServiceWorker } from './runtime.js';
import { emitSignal, getQueuedEvents } from './signalQueue.js';
import { syncQueuedEvents } from './sync.js';

async function updateCardState() {
  const card = document.getElementById('titan-pwa-runtime-card');
  if (!card) {
    return;
  }

  const events = await getQueuedEvents();
  let badge = card.querySelector('[data-runtime-queue]');
  if (!badge) {
    badge = document.createElement('div');
    badge.dataset.runtimeQueue = 'true';
    badge.className = 'mt-4 inline-flex items-center rounded-full border border-foreground/10 px-3 py-1 text-xs text-foreground/70';
    card.querySelector('.max-w-3xl')?.appendChild(badge);
  }
  badge.textContent = `${events.length} pending runtime event${events.length === 1 ? '' : 's'}`;
}

async function initRuntime() {
  try {
    await bootstrapRuntime();
    await registerServiceWorker();
    await emitSignal('system.runtime.booted', { source: 'dashboard-card' }, { surface: 'dashboard' });
    await updateCardState();
  } catch (error) {
    console.error(error);
  }
}

window.addEventListener('titan:pwa:queue-updated', updateCardState);
window.addEventListener('online', async () => {
  try {
    await syncQueuedEvents();
    await updateCardState();
  } catch (error) {
    console.error(error);
  }
});
window.addEventListener('DOMContentLoaded', initRuntime);
