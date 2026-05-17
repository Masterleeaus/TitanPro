import client from './client'

export const pushApi = {
  subscribe: (subscription: PushSubscriptionJSON) =>
    client.post('/api/zeropay/push/subscribe', { subscription }),
  unsubscribe: () => client.post('/api/zeropay/push/unsubscribe'),
}
