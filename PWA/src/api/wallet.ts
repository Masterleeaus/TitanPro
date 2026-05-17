import client from './client'

export interface WalletBalance { balance: number; currency: string; formatted: string }
export interface ReceiveInfo { payid: string; account_name: string; qr_payload?: string | null }

export const walletApi = {
  getBalance: () => client.get<WalletBalance>('/api/zeropay/wallet'),
  getReceiveInfo: () => client.get<ReceiveInfo>('/api/zeropay/wallet/receive-info'),
}
