import client from './client'

export interface QrPayload {
  payid: string
  merchant_name: string
  amount: number | null
  currency: string
  reference: string | null
  session_token: string
  expiry_timestamp: number
}

export interface CreateSessionPayload {
  amount?: number
  currency?: string
  reference?: string
}

export interface Session {
  session_token: string
  qr_payload: QrPayload
  status: string
  created_at: string
}

export const sessionsApi = {
  create: (data: CreateSessionPayload) => client.post<Session>('/api/zeropay/sessions', data),
  get: (token: string) => client.get<Session>(`/api/zeropay/sessions/${token}`),
  pay: (token: string, data: { gateway: string; [key: string]: unknown }) =>
    client.post<{ transaction_id: string; status: string }>(`/api/zeropay/sessions/${token}/pay`, data),
}
