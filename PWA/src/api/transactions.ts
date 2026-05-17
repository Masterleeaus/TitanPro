import client from './client'

export interface Transaction {
  id: number
  type: 'payment' | 'refund' | 'fee'
  direction: 'sent' | 'received'
  amount: number
  currency: string
  fee: number
  status: 'pending' | 'completed' | 'failed'
  reference: string | null
  merchant_name: string | null
  gateway: string
  created_at: string
  updated_at: string
}

export interface TransactionListResponse {
  data: Transaction[]
  meta: { current_page: number; last_page: number; per_page: number; total: number }
}

export const transactionsApi = {
  list: (params?: { page?: number; type?: string; status?: string; search?: string; limit?: number }) =>
    client.get<TransactionListResponse>('/api/zeropay/transactions', { params }),
  get: (id: number | string) => client.get<Transaction>(`/api/zeropay/transactions/${id}`),
}
