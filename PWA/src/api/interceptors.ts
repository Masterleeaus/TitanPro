import type { AxiosInstance } from 'axios'

export const TOKEN_KEY = 'zeropay_token'

export function applyInterceptors(instance: AxiosInstance): void {
  instance.interceptors.request.use((config) => {
    const token = localStorage.getItem(TOKEN_KEY)
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  })

  instance.interceptors.response.use(
    (res) => res,
    (err: unknown) => {
      if (
        err != null &&
        typeof err === 'object' &&
        'response' in err &&
        (err as { response?: { status?: number } }).response?.status === 401
      ) {
        localStorage.removeItem(TOKEN_KEY)
        localStorage.removeItem('user')
        window.location.href = '/auth/login'
      }
      return Promise.reject(err)
    },
  )
}
