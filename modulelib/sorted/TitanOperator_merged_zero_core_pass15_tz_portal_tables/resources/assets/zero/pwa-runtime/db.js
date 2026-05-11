const DB_NAME = 'titan_runtime';
const DB_VERSION = 2;

export const STORES = [
  { name: 'tz_jobs', keyPath: 'id' },
  { name: 'tz_customers', keyPath: 'id' },
  { name: 'tz_invoices', keyPath: 'id' },
  { name: 'tz_local_signals', keyPath: 'id' },
  { name: 'tz_sync_queue', keyPath: 'id' },
  { name: 'tz_runtime_meta', keyPath: 'key' },
  { name: 'tz_blob_staging', keyPath: 'id' },
  { name: 'tz_media_queue', keyPath: 'id' },
];

export function openRuntimeDb() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open(DB_NAME, DB_VERSION);

    request.onerror = () => reject(request.error);
    request.onupgradeneeded = () => {
      const db = request.result;
      for (const store of STORES) {
        if (!db.objectStoreNames.contains(store.name)) {
          db.createObjectStore(store.name, { keyPath: store.keyPath });
        }
      }
    };
    request.onsuccess = () => resolve(request.result);
  });
}

export async function withStore(storeName, mode, callback) {
  const db = await openRuntimeDb();

  return new Promise((resolve, reject) => {
    const transaction = db.transaction(storeName, mode);
    const store = transaction.objectStore(storeName);
    const result = callback(store, transaction);

    transaction.oncomplete = () => resolve(result);
    transaction.onerror = () => reject(transaction.error);
    transaction.onabort = () => reject(transaction.error);
  });
}

export function upsert(storeName, value) {
  return withStore(storeName, 'readwrite', (store) => store.put(value));
}

export function remove(storeName, key) {
  return withStore(storeName, 'readwrite', (store) => store.delete(key));
}

export function getAll(storeName) {
  return new Promise(async (resolve, reject) => {
    try {
      const db = await openRuntimeDb();
      const tx = db.transaction(storeName, 'readonly');
      const store = tx.objectStore(storeName);
      const request = store.getAll();
      request.onsuccess = () => resolve(request.result || []);
      request.onerror = () => reject(request.error);
    } catch (error) {
      reject(error);
    }
  });
}

export function setMeta(key, value) {
  return upsert('tz_runtime_meta', { key, value, updated_at: new Date().toISOString() });
}


export function getByKey(storeName, key) {
  return new Promise(async (resolve, reject) => {
    try {
      const db = await openRuntimeDb();
      const tx = db.transaction(storeName, 'readonly');
      const store = tx.objectStore(storeName);
      const request = store.get(key);
      request.onsuccess = () => resolve(request.result || null);
      request.onerror = () => reject(request.error);
    } catch (error) {
      reject(error);
    }
  });
}
