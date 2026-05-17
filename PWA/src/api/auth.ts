import client from './client'

export interface LoginPayload { email: string; password: string }
export interface RegisterPayload { name: string; email: string; password: string; password_confirmation: string; phone?: string }
export interface AuthResponse { token: string; user: { id: number; name: string; email: string } }

export interface KycPayload {
  first_name: string
  last_name: string
  country: string
  city: string
  zip_code: string
  id_type: string
  id_front: File
  id_back?: File
}

export const authApi = {
  login: (data: LoginPayload) => client.post<AuthResponse>('/api/auth/login', data),
  register: (data: RegisterPayload) => client.post<AuthResponse>('/api/auth/register', data),
  forgotPassword: (email: string) => client.post('/api/auth/forgot-password', { email }),
  verifyEmail: (token: string) => client.post('/api/auth/verify-email', { token }),
  verifyOtp: (otp: string, type: string) => client.post('/api/auth/verify-otp', { otp, type }),
  resendVerification: (email: string) => client.post('/api/auth/resend-verification', { email }),
  submitKyc: (data: KycPayload) => {
    const form = new FormData()
    Object.entries(data).forEach(([k, v]) => {
      if (v === undefined) return
      if (typeof v === 'string') {
        form.append(k, v)
      } else {
        form.append(k, v) // File extends Blob — accepted by FormData.append
      }
    })
    return client.post('/api/auth/kyc', form, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
  },
  logout: () => client.post('/api/auth/logout'),
}
