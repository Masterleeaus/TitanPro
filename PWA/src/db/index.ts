import { openDB, type DBSchema, type IDBPDatabase } from 'idb'

export interface PendingPayment {
  id: string
  session_token: string
  auth_token?: string
  payload: Record<string, unknown>
  created_at: string
  retry_count: number
}

export interface CachedTransaction {
  id: number
  data: Record<string, unknown>
  synced_at: string
}

export interface CachedSession {
  session_token: string
  qr_payload: Record<string, unknown>
  expires_at: number
}

interface ZeroPayDB extends DBSchema {
  pending_payments: {
    key: string
    value: PendingPayment
    indexes: { by_created: string }
  }
  cached_transactions: {
    key: number
    value: CachedTransaction
    indexes: { by_synced: string }
  }
  cached_sessions: {
    key: string
    value: CachedSession
    indexes: { by_expires: number }
  }
}

let dbInstance: IDBPDatabase<ZeroPayDB> | null = null

export async function getDb(): Promise<IDBPDatabase<ZeroPayDB>> {
  if (dbInstance) return dbInstance
  dbInstance = await openDB<ZeroPayDB>('zeropay-db', 1, {
    upgrade(db) {
      const pp = db.createObjectStore('pending_payments', { keyPath: 'id' })
      pp.createIndex('by_created', 'created_at')

      const ct = db.createObjectStore('cached_transactions', { keyPath: 'id' })
      ct.createIndex('by_synced', 'synced_at')

      const cs = db.createObjectStore('cached_sessions', { keyPath: 'session_token' })
      cs.createIndex('by_expires', 'expires_at')
    },
  })
  return dbInstance
}

export async function queuePendingPayment(payment: PendingPayment): Promise<void> {
  const db = await getDb()
  await db.put('pending_payments', payment)
}

export async function getPendingPayments(): Promise<PendingPayment[]> {
  const db = await getDb()
  return db.getAll('pending_payments')
}

export async function removePendingPayment(id: string): Promise<void> {
  const db = await getDb()
  await db.delete('pending_payments', id)
}

export async function cacheTransactions(transactions: CachedTransaction[]): Promise<void> {
  const db = await getDb()
  const tx = db.transaction('cached_transactions', 'readwrite')
  await Promise.all(transactions.map((t) => tx.store.put(t)))
  await tx.done
}

export async function getCachedTransactions(): Promise<CachedTransaction[]> {
  const db = await getDb()
  return db.getAll('cached_transactions')
}

export async function cacheSession(session: CachedSession): Promise<void> {
  const db = await getDb()
  await db.put('cached_sessions', session)
}

export async function getCachedSession(token: string): Promise<CachedSession | undefined> {
  const db = await getDb()
  return db.get('cached_sessions', token)
}
