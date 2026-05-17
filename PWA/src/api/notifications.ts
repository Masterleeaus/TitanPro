import client from './client'

export interface Notification {
  id: number
  type: string
  title: string
  body: string
  read_at: string | null
  created_at: string
}

export interface NotificationListResponse {
  data: Notification[]
}

export const notificationsApi = {
  list: () => client.get<NotificationListResponse>('/api/zeropay/notifications'),
  markRead: (id: number | string) =>
    client.patch<Notification>(`/api/zeropay/notifications/${id}/read`),
}
