import axios from 'axios'
import { applyInterceptors } from './interceptors'

const client = axios.create({
  baseURL: import.meta.env.VITE_API_URL ?? '',
  headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
  timeout: 15000,
})

applyInterceptors(client)

export default client
